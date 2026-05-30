<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(private BookingLifecycleService $bookingLifecycle)
    {
    }

    public function syncPayableBooking(Booking $booking): Booking
    {
        return $this->bookingLifecycle
            ->syncBooking($booking->loadMissing('payment'))
            ->load('payment');
    }

    public function markManualTransferReported(Booking $booking): void
    {
        $booking->loadMissing('payment');

        DB::transaction(function () use ($booking): void {
            $payment = $booking->payment;

            if ($payment) {
                $payment->forceFill([
                    'payment_status' => 'pending',
                    'payment_gateway' => $payment->payment_gateway ?: 'manual',
                    'reported_at' => $payment->reported_at ?: now(),
                ])->save();
            }

            if ($this->applyNote($booking, 'Khách đã báo chuyển khoản, chờ Admin check biến động số dư.')) {
                $booking->save();
            }
        });
    }

    public function markCompleted(Booking $booking, array $meta = []): void
    {
        $booking->loadMissing('payment');

        DB::transaction(function () use ($booking, $meta): void {
            if ($booking->status !== 'completed') {
                $booking->status = 'confirmed';
            }

            $bookingChanged = $booking->isDirty('status');

            if ($this->applyNote($booking, $meta['note'] ?? null)) {
                $bookingChanged = true;
            }

            if ($bookingChanged) {
                $booking->save();
            }

            if ($booking->payment) {
                $this->updatePayment($booking->payment, 'completed', $meta);
            }
        });
    }

    public function markFailed(Booking $booking, array $meta = []): void
    {
        $booking->loadMissing('payment');

        DB::transaction(function () use ($booking, $meta): void {
            $cancelBooking = $meta['cancel_booking'] ?? true;
            $updatePayment = $meta['update_payment'] ?? true;

            if ($cancelBooking) {
                $booking->status = 'cancelled';
            }

            $bookingChanged = $cancelBooking && $booking->isDirty('status');

            if ($this->applyNote($booking, $meta['note'] ?? null)) {
                $bookingChanged = true;
            }

            if ($bookingChanged) {
                $booking->save();
            }

            if ($updatePayment && $booking->payment) {
                $this->updatePayment($booking->payment, 'failed', $meta);
            }
        });
    }

    public function appendBookingNote(Booking $booking, string $note): void
    {
        if (!$this->applyNote($booking, $note)) {
            return;
        }

        $booking->save();
    }

    private function updatePayment(Payment $payment, string $status, array $meta = []): void
    {
        $attributes = [
            'payment_status' => $status,
        ];

        if ($status === 'completed') {
            $attributes['paid_at'] = $meta['paid_at'] ?? $payment->paid_at ?? now();
        }

        if (array_key_exists('reported_at', $meta)) {
            $attributes['reported_at'] = $meta['reported_at'];
        }

        if (array_key_exists('payment_gateway', $meta) && $meta['payment_gateway'] !== null) {
            $attributes['payment_gateway'] = $meta['payment_gateway'];
        }

        if (array_key_exists('transaction_code', $meta)) {
            $attributes['transaction_code'] = $meta['transaction_code'];
        }

        if (array_key_exists('transaction_reference', $meta)) {
            $attributes['transaction_reference'] = $meta['transaction_reference'];
        }

        if (array_key_exists('gateway_response', $meta)) {
            $attributes['gateway_response'] = $meta['gateway_response'];
        }

        $payment->forceFill($attributes)->save();
    }

    private function applyNote(Booking $booking, ?string $note): bool
    {
        $note = trim((string) $note);

        if ($note === '' || str_contains((string) $booking->notes, $note)) {
            return false;
        }

        $booking->notes = $booking->notes
            ? $booking->notes . ' | ' . $note
            : $note;

        return true;
    }
}
