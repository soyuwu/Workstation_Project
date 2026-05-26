<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Services\BookingLifecycleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        return view('account.profile', [
            'user' => $user,
        ]);
    }

    public function bookings(Request $request, BookingLifecycleService $bookingLifecycle)
    {
        $user = Auth::user();

        $bookingLifecycle->syncForUser($user);

        $bookings = Booking::query()
            ->with([
                'workspace',
                'payment',
                'review',
            ])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $bookingDetails = $this->buildBookingDetails($bookings->getCollection(), $bookingLifecycle);

        return view('account.bookings', [
            'bookings' => $bookings,
            'bookingDetails' => $bookingDetails,
        ]);
    }

    public function cancelBooking(Booking $booking, BookingLifecycleService $bookingLifecycle)
    {
        $result = $bookingLifecycle->cancelByUser($booking, Auth::user());

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function storeReview(Request $request, Booking $booking, BookingLifecycleService $bookingLifecycle)
    {
        $user = Auth::user();

        if ((int) $booking->user_id !== (int) $user->id) {
            abort(403, 'Unauthorized');
        }

        $booking = $bookingLifecycle->syncBooking(
            $booking->loadMissing(['payment', 'review', 'workspace'])
        );

        if (!$bookingLifecycle->canReview($booking)) {
            return back()->with('error', 'Chỉ có thể nhận xét sau khi phòng đã sử dụng xong và mỗi đơn chỉ được nhận xét một lần.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10|max:1000',
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'workspace_id' => $booking->workspace_id,
            'rating' => $validated['rating'],
            'content' => $validated['content'],
            'author_name' => $user->name,
            'author_role' => 'Khách hàng WorkStation',
            'is_approved' => true,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã gửi nhận xét.');
    }

    private function buildBookingDetails($bookings, BookingLifecycleService $bookingLifecycle): array
    {
        return $bookings->mapWithKeys(function (Booking $booking) use ($bookingLifecycle) {
            $payment = $booking->payment;
            $policy = $bookingLifecycle->cancellationPreview($booking);
            $paymentStatus = $payment?->payment_status ?? 'pending';
            $isUnreportedPendingPayment = $booking->status === 'pending'
                && $paymentStatus === 'pending'
                && !$payment?->reported_at;

            $review = $booking->review;

            return [
                $booking->id => [
                    'id' => $booking->id,
                    'code' => $booking->booking_code,
                    'workspace' => $booking->workspace?->name ?? '--',
                    'capacity' => $booking->workspace?->capacity ? $booking->workspace->capacity . ' người' : '--',
                    'date' => Carbon::parse($booking->booking_date)->format('d/m/Y'),
                    'time' => $booking->start_time . ' - ' . $booking->end_time,
                    'created_at' => $booking->created_at?->format('d/m/Y H:i'),
                    'total' => (float) $booking->total_amount,
                    'total_text' => number_format((float) $booking->total_amount, 0, ',', '.') . ' VND',
                    'base_text' => number_format((float) $booking->base_price, 0, ',', '.') . ' VND',
                    'tax_text' => number_format((float) $booking->tax, 0, ',', '.') . ' VND',
                    'status' => $booking->status,
                    'payment_status' => $paymentStatus,
                    'payment_method' => $payment?->payment_method ?? '--',
                    'paid_at' => $payment?->paid_at?->format('d/m/Y H:i') ?? '--',
                    'reported_at' => $payment?->reported_at?->format('d/m/Y H:i') ?? null,
                    'notes' => $booking->notes,
                    'cancellation_reason' => $booking->cancellation_reason,
                    'cancelled_at' => $booking->cancelled_at?->format('d/m/Y H:i'),
                    'cancel_fee_text' => number_format((float) ($booking->cancel_fee_amount ?? $policy['fee']), 0, ',', '.') . ' VND',
                    'refund_text' => number_format((float) ($booking->refund_amount ?? $policy['refund']), 0, ',', '.') . ' VND',
                    'can_pay' => $isUnreportedPendingPayment,
                    'pay_url' => $isUnreportedPendingPayment ? route('payment.vietqr', $booking->booking_code) : null,
                    'payment_deadline' => $isUnreportedPendingPayment ? $bookingLifecycle->paymentDeadline($booking)->toIso8601String() : null,
                    'payment_deadline_text' => $isUnreportedPendingPayment ? $bookingLifecycle->paymentDeadline($booking)->format('d/m/Y H:i:s') : null,
                    'can_cancel' => $policy['can_cancel'],
                    'cancel_reason' => $policy['reason'],
                    'cancel_fee_preview_text' => number_format((float) $policy['fee'], 0, ',', '.') . ' VND',
                    'refund_preview_text' => number_format((float) $policy['refund'], 0, ',', '.') . ' VND',
                    'cancel_url' => $policy['can_cancel'] ? route('account.bookings.cancel', $booking) : null,
                    'can_review' => $bookingLifecycle->canReview($booking),
                    'review_url' => route('account.bookings.review', $booking),
                    'review' => $review ? [
                        'rating' => (float) $review->rating,
                        'content' => $review->content,
                        'created_at' => $review->created_at?->format('d/m/Y H:i'),
                    ] : null,
                ],
            ];
        })->toArray();
    }
}
