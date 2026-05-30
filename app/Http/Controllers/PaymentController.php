<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MoMoService;
use App\Services\PaymentService;
use App\Services\SePayWebhookService;
use App\Services\VietQrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private VietQrService $vietQrService,
        private SePayWebhookService $sePayWebhookService,
        private MoMoService $momoService,
    ) {
    }

    /**
     * Xử lý kết quả trả về khi người dùng thanh toán xong trên MoMo và bị redirect về.
     */
    public function momoReturn(Request $request)
    {
        // Thông tin MoMo trả về trên URL
        $resultCode = $request->query('resultCode');
        $message = $request->query('message');
        $orderId = $request->query('orderId'); // Trùng với ID của booking

        // Trạng thái thành công
        if ($resultCode == 0) {
            $status = 'success';
            $msg = 'Thanh toán thành công qua ví MoMo. Mã đơn hàng: ' . $orderId;
        } else {
            $status = 'error';
            $msg = 'Thanh toán thất bại hoặc đã bị hủy. Lý do: ' . $message;
        }

        return view('payment.momo_return', compact('status', 'msg', 'orderId'));
    }

    /**
     * Webhook MoMo gọi ngầm (Server to Server) để báo kết quả giao dịch.
     * Ở đây chúng ta sẽ cập nhật trạng thái đơn hàng vào Database.
     */
    public function momoIPN(Request $request)
    {
        $data = $request->all();

        // 1. Xác thực chữ ký
        if (!$this->momoService->verifySignature($data)) {
            Log::error("MoMo IPN - Chữ ký không hợp lệ", $data);
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $orderId = $data['orderId'] ?? null;
        $resultCode = (int) ($data['resultCode'] ?? -1);

        $booking = Booking::where('booking_code', $orderId)->first();
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($resultCode == 0) {
            $this->paymentService->markCompleted($booking, [
                'transaction_code' => $data['transId'] ?? null,
                'payment_gateway' => 'MoMo',
                'gateway_response' => $data,
            ]);
        } else {
            $this->paymentService->markFailed($booking, [
                'payment_gateway' => 'MoMo',
                'gateway_response' => $data,
            ]);
        }

        // Bắt buộc trả về HTTP 204 cho MoMo biết mình đã nhận IPN thành công
        return response()->noContent();
    }

    /**
     * Hiển thị trang quét mã VietQR
     */
    public function vietqr($booking_code)
    {
        $booking = Booking::where('booking_code', $booking_code)->firstOrFail();
        $booking = $this->paymentService->syncPayableBooking($booking);

        if ($booking->status === 'cancelled') {
            return redirect()->route('payment.success', ['booking_code' => $booking_code]);
        }

        $vietQrData = $this->vietQrService->dataFor($booking);
        if (!$vietQrData) {
            return response()
                ->view('payment.vietqr_unavailable', compact('booking'), 503);
        }

        return view('payment.vietqr', array_merge(['booking' => $booking], $vietQrData));
    }

    /**
     * Xử lý khi khách hàng bấm "Tôi đã chuyển khoản"
     */
    public function confirmVietqr(Request $request, $booking_code)
    {
        $booking = Booking::where('booking_code', $booking_code)->firstOrFail();
        $booking = $this->paymentService->syncPayableBooking($booking);

        if ($booking->status === 'cancelled') {
            return redirect()->route('payment.success', ['booking_code' => $booking_code])
                ->with('error', 'Đơn hàng đã quá thời gian thanh toán và bị hủy.');
        }

        $this->paymentService->markManualTransferReported($booking);

        // Redirect sang trang success (GET) để tránh bị reload form
        return redirect()->route('payment.success', ['booking_code' => $booking_code]);
    }

    /**
     * Lấy trạng thái Booking hiện tại (dùng cho AJAX Polling kiểm tra realtime)
     */
    public function checkStatus($booking_code)
    {
        $booking = Booking::with('payment')->where('booking_code', $booking_code)->first();

        if ($booking) {
            $booking = $this->paymentService->syncPayableBooking($booking)->load('payment');

            return response()->json([
                'status' => $booking->status,
                'payment_status' => $booking->payment?->payment_status
            ]);
        }

        return response()->json(['error' => 'Booking not found'], 404);
    }

    /**
     * Hiển thị trang kết quả đặt phòng (GET)
     */
    public function successPage($booking_code)
    {
        $booking = Booking::with('payment')->where('booking_code', $booking_code)->firstOrFail();
        $booking = $this->paymentService->syncPayableBooking($booking)->load('payment');

        if ($booking->status === 'cancelled') {
            $message = $booking->cancellation_reason ?: 'Đơn hàng đã bị hủy.';
        } elseif ($booking->payment && $booking->payment->payment_status === 'completed') {
            $message = 'Cảm ơn bạn! Giao dịch của bạn đã được hệ thống xác nhận thanh toán tự động thành công.';
        } else {
            $message = 'Cảm ơn bạn! Hệ thống đang chờ kiểm tra biến động số dư và sẽ xác nhận đặt phòng trong ít phút.';
        }

        return view('payment.success', compact('booking', 'message'));
    }

    /**
     * Tích hợp Webhook tự động từ SePay khi có biến động số dư ngân hàng
     */
    public function sepayWebhook(Request $request)
    {
        $result = $this->sePayWebhookService->handle(
            $request->all(),
            $request->header('Authorization'),
        );

        return response()->json($result['body'], $result['status']);
    }
}
