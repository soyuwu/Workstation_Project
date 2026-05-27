<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Services\BookingLifecycleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

    public function showCancelBookingForm(Booking $booking, BookingLifecycleService $bookingLifecycle)
    {
        $user = Auth::user();

        if ((int) $booking->user_id !== (int) $user->id) {
            abort(403, 'Unauthorized');
        }

        $booking = $bookingLifecycle->syncBooking(
            $booking->loadMissing(['workspace', 'payment'])
        );
        $policy = $bookingLifecycle->cancellationPreview($booking);

        if (!$policy['can_cancel']) {
            return redirect()
                ->route('account.bookings')
                ->with('error', $policy['reason']);
        }

        return view('account.cancel-booking', [
            'booking' => $booking,
            'policy' => $policy,
            'bankOptions' => $this->bankOptions(),
            'cancellationReasons' => $this->cancellationReasons(),
            'defaultReceiverName' => $user->name,
            'requiresCancellationDetails' => $this->requiresCancellationDetails($booking),
        ]);
    }

    public function cancelBooking(Request $request, Booking $booking, BookingLifecycleService $bookingLifecycle)
    {
        $user = Auth::user();

        if ((int) $booking->user_id !== (int) $user->id) {
            abort(403, 'Unauthorized');
        }

        $booking = $bookingLifecycle->syncBooking($booking->loadMissing('payment'));
        $policy = $bookingLifecycle->cancellationPreview($booking);

        if (!$policy['can_cancel']) {
            return back()->with('error', $policy['reason']);
        }

        $validated = [];

        if ($this->requiresCancellationDetails($booking)) {
            $bankOptions = $this->bankOptions();
            $cancellationReasons = $this->cancellationReasons();

            $selectedReasons = (array) $request->input('cancellation_reason_codes', []);

            $validated = $request->validate([
                'refund_receiver_name' => ['required', 'string', 'max:255'],
                'refund_bank_name' => ['required', 'string', Rule::in(array_keys($bankOptions))],
                'refund_bank_account_number' => ['required', 'string', 'max:32', 'regex:/^(?=.*\d)[0-9 .-]+$/'],
                'cancellation_reason_codes' => ['required', 'array', 'min:1'],
                'cancellation_reason_codes.*' => ['string', Rule::in(array_keys($cancellationReasons))],
                'cancellation_reason_detail' => [
                    'nullable',
                    'string',
                    'max:1000',
                    Rule::requiredIf(fn () => in_array('other', $selectedReasons, true)),
                ],
            ], [
                'refund_receiver_name.required' => 'Vui lòng nhập họ tên người nhận hoàn tiền.',
                'refund_bank_name.required' => 'Vui lòng chọn ngân hàng thụ hưởng.',
                'refund_bank_name.in' => 'Ngân hàng thụ hưởng không hợp lệ.',
                'refund_bank_account_number.required' => 'Vui lòng nhập số tài khoản ngân hàng.',
                'refund_bank_account_number.regex' => 'Số tài khoản chỉ nên gồm số, khoảng trắng, dấu chấm hoặc dấu gạch ngang.',
                'cancellation_reason_codes.required' => 'Vui lòng chọn ít nhất một lý do hủy phòng.',
                'cancellation_reason_codes.array' => 'Lý do hủy phòng không hợp lệ.',
                'cancellation_reason_codes.min' => 'Vui lòng chọn ít nhất một lý do hủy phòng.',
                'cancellation_reason_codes.*.in' => 'Lý do hủy phòng không hợp lệ.',
                'cancellation_reason_detail.required' => 'Vui lòng nhập lý do chi tiết.',
            ]);

            $validated['refund_bank_name'] = $bankOptions[$validated['refund_bank_name']];
            $reasonCodes = array_values(array_unique($validated['cancellation_reason_codes']));
            $validated['cancellation_reason_code'] = implode(',', $reasonCodes);
            $validated['cancellation_reason_label'] = implode('; ', array_map(
                static fn (string $reasonCode) => rtrim($cancellationReasons[$reasonCode], ". \t\n\r\0\x0B"),
                $reasonCodes
            ));
            unset($validated['cancellation_reason_codes']);
        }

        $result = $bookingLifecycle->cancelByUser($booking, $user, $validated);

        if (!$result['success']) {
            return back()
                ->withInput()
                ->with('error', $result['message']);
        }

        return redirect()
            ->route('account.bookings')
            ->with('success', $result['message']);
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
                    'cancel_url' => $policy['can_cancel'] ? route('account.bookings.cancel.form', $booking) : null,
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

    private function bankOptions(): array
    {
        return [
            'vietcombank' => 'Vietcombank (VCB)',
            'bidv' => 'BIDV',
            'vietinbank' => 'VietinBank',
            'techcombank' => 'Techcombank',
            'mbbank' => 'MB Bank',
            'acb' => 'ACB',
            'vpbank' => 'VPBank',
            'tpbank' => 'TPBank',
            'sacombank' => 'Sacombank',
            'hdbank' => 'HDBank',
            'vib' => 'VIB',
            'msb' => 'MSB',
            'shb' => 'SHB',
            'agribank' => 'Agribank',
            'ocb' => 'OCB',
        ];
    }

    private function cancellationReasons(): array
    {
        return [
            'changed_plan' => 'Đột xuất thay đổi lịch trình/kế hoạch.',
            'wrong_booking' => 'Đặt nhầm ngày/nhầm giờ/nhầm cơ sở.',
            'bad_weather' => 'Thời tiết xấu không thể đến.',
            'found_alternative' => 'Tìm được không gian làm việc khác phù hợp hơn.',
            'changed_mind' => 'Đổi ý không muốn sử dụng dịch vụ nữa.',
            'other' => 'Lý do khác.',
        ];
    }

    private function requiresCancellationDetails(Booking $booking): bool
    {
        return ($booking->payment?->payment_status ?? 'pending') === 'completed';
    }
}
