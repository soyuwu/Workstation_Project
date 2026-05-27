<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class BookingLifecycleService
{
    private const PAYMENT_TIMEOUT_MINUTES = 1;
    private const PAID_CANCEL_FEE_RATE = 0.2;

    public function syncForUser(User $user): void
    {
        $this->cancelExpiredPendingBookings($user);
        $this->markCompletedBookings($user);
    }

    public function syncBooking(Booking $booking): Booking
    {
        $booking->loadMissing('payment');

        if ($this->shouldAutoCancel($booking)) {
            $this->cancelWithoutFee($booking, 'Tự động hủy do quá 1 phút chưa thanh toán.');
            return $booking->refresh()->loadMissing('payment');
        }

        if ($this->shouldMarkCompleted($booking)) {
            $booking->forceFill(['status' => 'completed'])->save();
            return $booking->refresh()->loadMissing('payment');
        }

        return $booking;
    }

    public function cancelExpiredPendingBookings(?User $user = null): int
    {
        $query = Booking::query()
            ->with('payment')
            ->where('status', 'pending')
            ->where('created_at', '<=', now()->subMinutes(self::PAYMENT_TIMEOUT_MINUTES))
            ->where(function (Builder $query) {
                $query->whereDoesntHave('payment')
                    ->orWhereHas('payment', function (Builder $paymentQuery) {
                        $paymentQuery
                            ->where('payment_status', 'pending')
                            ->whereNull('reported_at');
                    });
            });

        if ($user) {
            $query->where('user_id', $user->id);
        }

        $expiredBookings = $query->get();

        foreach ($expiredBookings as $booking) {
            $this->cancelWithoutFee($booking, 'Tự động hủy do quá 1 phút chưa thanh toán.');
        }

        return $expiredBookings->count();
    }

    public function markCompletedBookings(?User $user = null): int
    {
        $query = Booking::query()
            ->with('payment')
            ->where('status', 'confirmed')
            ->whereHas('payment', function (Builder $paymentQuery) {
                $paymentQuery->where('payment_status', 'completed');
            });

        if ($user) {
            $query->where('user_id', $user->id);
        }

        $completedCount = 0;

        $query->get()->each(function (Booking $booking) use (&$completedCount) {
            if (!$this->hasEnded($booking)) {
                return;
            }

            $booking->forceFill(['status' => 'completed'])->save();
            $completedCount++;
        });

        return $completedCount;
    }

    public function cancellationPreview(Booking $booking): array
    {
        $booking->loadMissing('payment');
        $payment = $booking->payment;
        $isPaid = $payment?->payment_status === 'completed';
        $total = (float) $booking->total_amount;

        if (in_array($booking->status, ['cancelled', 'completed'], true)) {
            return [
                'can_cancel' => false,
                'reason' => $booking->status === 'completed'
                    ? 'Phòng đã sử dụng xong nên không thể hủy.'
                    : 'Đơn hàng đã hủy.',
                'fee' => (float) ($booking->cancel_fee_amount ?? 0),
                'refund' => (float) ($booking->refund_amount ?? 0),
                'rate' => null,
            ];
        }

        if (!$isPaid) {
            return [
                'can_cancel' => true,
                'reason' => 'Chưa thanh toán: hủy miễn phí, refund 0đ.',
                'fee' => 0.0,
                'refund' => 0.0,
                'rate' => 0,
            ];
        }

        if (now()->lt($this->startAt($booking))) {
            $fee = round($total * self::PAID_CANCEL_FEE_RATE, 2);

            return [
                'can_cancel' => true,
                'reason' => 'Đã thanh toán và hủy trước check-in: phí phạt 20%, refund 80%.',
                'fee' => $fee,
                'refund' => max(0, round($total - $fee, 2)),
                'rate' => self::PAID_CANCEL_FEE_RATE,
            ];
        }

        return [
            'can_cancel' => false,
            'reason' => 'Đã quá thời gian check-in nên không cho phép hủy.',
            'fee' => 0.0,
            'refund' => 0.0,
            'rate' => null,
        ];
    }

    public function cancelByUser(Booking $booking, User $user, array $cancellationData = []): array
    {
        if ((int) $booking->user_id !== (int) $user->id) {
            return ['success' => false, 'message' => 'Bạn không có quyền hủy đơn này.'];
        }

        $booking = $this->syncBooking($booking);
        $policy = $this->cancellationPreview($booking);

        if (!$policy['can_cancel']) {
            return ['success' => false, 'message' => $policy['reason']];
        }

        if (($booking->payment?->payment_status ?? 'pending') === 'completed') {
            $booking->forceFill(array_merge([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancel_fee_amount' => $policy['fee'],
                'refund_amount' => $policy['refund'],
                'cancellation_reason' => $this->userCancellationReason(
                    'Khách hủy trước check-in. Phí phạt 20%, refund 80%.',
                    $cancellationData,
                    'Phí phạt 20%, refund 80%.'
                ),
            ], $this->cancellationMetadata($cancellationData)))->save();

            $booking->payment->forceFill([
                'payment_status' => 'refunded',
            ])->save();

            return [
                'success' => true,
                'message' => 'Đã hủy đơn. Số tiền dự kiến hoàn: ' . number_format($policy['refund'], 0, ',', '.') . ' VND.',
            ];
        }

        $this->cancelWithoutFee(
            $booking,
            $this->userCancellationReason('Khách tự hủy khi chưa thanh toán.', $cancellationData),
            $cancellationData
        );

        return ['success' => true, 'message' => 'Đã hủy đơn thành công.'];
    }

    public function paymentDeadline(Booking $booking): Carbon
    {
        return $booking->created_at->copy()->addMinutes(self::PAYMENT_TIMEOUT_MINUTES);
    }

    public function canReview(Booking $booking): bool
    {
        $booking->loadMissing('review');

        return $booking->status === 'completed' && !$booking->review;
    }

    private function cancelWithoutFee(Booking $booking, string $reason, array $cancellationData = []): void
    {
        $booking->loadMissing('payment');

        $booking->forceFill(array_merge([
            'status' => 'cancelled',
            'cancelled_at' => $booking->cancelled_at ?: now(),
            'cancel_fee_amount' => 0,
            'refund_amount' => 0,
            'cancellation_reason' => $booking->cancellation_reason ?: $reason,
        ], $this->cancellationMetadata($cancellationData)))->save();

        if ($booking->payment && $booking->payment->payment_status !== 'completed') {
            $booking->payment->forceFill(['payment_status' => 'failed'])->save();
        }
    }

    private function cancellationMetadata(array $cancellationData): array
    {
        return array_filter([
            'refund_receiver_name' => $this->nullableString($cancellationData['refund_receiver_name'] ?? null),
            'refund_bank_name' => $this->nullableString($cancellationData['refund_bank_name'] ?? null),
            'refund_bank_account_number' => $this->nullableString($cancellationData['refund_bank_account_number'] ?? null),
            'cancellation_reason_code' => $this->nullableString($cancellationData['cancellation_reason_code'] ?? null),
            'cancellation_reason_detail' => $this->nullableString($cancellationData['cancellation_reason_detail'] ?? null),
        ], static fn ($value) => $value !== null);
    }

    private function userCancellationReason(string $fallback, array $cancellationData, ?string $suffix = null): string
    {
        $label = $this->nullableString($cancellationData['cancellation_reason_label'] ?? null);
        $detail = $this->nullableString($cancellationData['cancellation_reason_detail'] ?? null);

        if (!$label && !$detail) {
            return $fallback;
        }

        $reason = $label ? rtrim($label, ". \t\n\r\0\x0B") : '';
        if ($detail) {
            $reason = $reason ? "{$reason}: {$detail}" : $detail;
        }

        if (!$reason) {
            return $fallback;
        }

        return $suffix ? "{$reason}. {$suffix}" : $reason;
    }

    private function nullableString(mixed $value): ?string
    {
        if (!is_string($value) && !is_numeric($value)) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function shouldAutoCancel(Booking $booking): bool
    {
        if ($booking->status !== 'pending') {
            return false;
        }

        if ($booking->created_at->gt(now()->subMinutes(self::PAYMENT_TIMEOUT_MINUTES))) {
            return false;
        }

        $payment = $booking->payment;

        return !$payment || ($payment->payment_status === 'pending' && !$payment->reported_at);
    }

    private function shouldMarkCompleted(Booking $booking): bool
    {
        return $booking->status === 'confirmed'
            && $booking->payment?->payment_status === 'completed'
            && $this->hasEnded($booking);
    }

    private function hasEnded(Booking $booking): bool
    {
        return now()->greaterThanOrEqualTo($this->endAt($booking));
    }

    private function startAt(Booking $booking): Carbon
    {
        return Carbon::parse(Carbon::parse($booking->booking_date)->toDateString() . ' ' . $booking->start_time);
    }

    private function endAt(Booking $booking): Carbon
    {
        return Carbon::parse(Carbon::parse($booking->booking_date)->toDateString() . ' ' . $booking->end_time);
    }
}
