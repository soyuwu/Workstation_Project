<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
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
    public function momoIPN(Request $request, \App\Services\MoMoService $momoService)
    {
        $data = $request->all();

        // 1. Xác thực chữ ký
        if (!$momoService->verifySignature($data)) {
            Log::error("MoMo IPN - Chữ ký không hợp lệ", $data);
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $orderId = $data['orderId'];
        $resultCode = $data['resultCode'];

        // 2. Tìm Booking và Payment trong DB
        $booking = \App\Models\Booking::where('booking_code', $orderId)->first();
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $payment = \App\Models\Payment::where('booking_id', $booking->id)->first();

        // 3. Cập nhật trạng thái
        if ($resultCode == 0) {
            // Thanh toán thành công
            if ($booking->status !== 'confirmed') {
                $booking->status = 'confirmed';
                $booking->save();

                if ($payment) {
                    $payment->payment_status = 'completed';
                    $payment->transaction_code = $data['transId'];
                    $payment->paid_at = now();
                    $payment->save();
                }
            }
        } else {
            // Thanh toán thất bại
            $booking->status = 'cancelled';
            $booking->save();

            if ($payment) {
                $payment->payment_status = 'failed';
                $payment->save();
            }
        }

        // Bắt buộc trả về HTTP 204 cho MoMo biết mình đã nhận IPN thành công
        return response()->noContent();
    }

    /**
     * Hiển thị trang quét mã VietQR
     */
    public function vietqr($booking_code)
    {
        $booking = \App\Models\Booking::where('booking_code', $booking_code)->firstOrFail();

        // Lấy thông tin tài khoản từ cấu hình (Khách hàng cần tự điền số tài khoản thật của họ vào file .env)
        $bankId = env('VIETQR_BANK_ID', '970416'); // BIN của Vietcombank
        $accountNo = env('VIETQR_ACCOUNT_NO', '27800607'); // <--- BẠN CẦN THAY THÀNH SỐ TÀI KHOẢN CỦA BẠN
        $accountName = env('VIETQR_ACCOUNT_NAME', 'LUONG LAM KHANH');

        $template = 'compact';
        // Ép kiểu ép số tiền về số nguyên (bỏ số thập phân .00) để API VietQR đọc được
        $amount = (int) $booking->total_amount;
        $addInfo = urlencode($booking_code);

        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-{$template}.png?amount={$amount}&addInfo={$addInfo}&accountName=" . urlencode($accountName);

        return view('payment.vietqr', compact('booking', 'qrUrl', 'accountNo', 'accountName'));
    }

    /**
     * Xử lý khi khách hàng bấm "Tôi đã chuyển khoản"
     */
    public function confirmVietqr(Request $request, $booking_code)
    {
        $booking = \App\Models\Booking::where('booking_code', $booking_code)->firstOrFail();
        $payment = \App\Models\Payment::where('booking_id', $booking->id)->first();

        // Đổi trạng thái thanh toán
        // Vì enum trong DB chỉ có ['pending', 'completed', 'failed', 'refunded']
        // Nên ta giữ nguyên 'pending' cho payment, nhưng thêm ghi chú vào booking để Admin biết khách đã báo chuyển khoản
        if ($payment) {
            $payment->payment_status = 'pending';
            $payment->save();
        }

        if (!str_contains((string)$booking->notes, 'Khách đã báo chuyển khoản')) {
            $booking->notes = ($booking->notes ? $booking->notes . ' | ' : '') . 'Khách đã báo chuyển khoản, chờ Admin check biến động số dư.';
        }
        $booking->is_paid = true;
        $booking->save();

        // Chuyển sang trang báo thành công
        return view('payment.success', [
            'booking' => $booking,
            'message' => 'Cảm ơn bạn! Hệ thống đang kiểm tra biến động số dư và sẽ xác nhận đặt phòng trong ít phút.'
        ]);
    }

    /**
     * Tích hợp Webhook tự động từ SePay khi có biến động số dư ngân hàng
     */
    public function sepayWebhook(Request $request)
    {
        // 1. Kiểm tra API Key (Secure Token) từ SePay cấu hình trong file .env để bảo mật
        $authHeader = $request->header('Authorization');
        $sepayToken = env('SEPAY_WEBHOOK_TOKEN');

        if ($sepayToken && $authHeader !== 'Apikey ' . $sepayToken) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // 2. Lấy dữ liệu payload từ SePay gửi về
        $payload = $request->all();

        // Ghi log để tiện theo dõi và đối soát
        Log::info('SePay Webhook Received:', $payload);

        $content = $payload['content'] ?? '';
        $transferAmount = (float) ($payload['transferAmount'] ?? 0);

        // 3. Tìm mã booking (định dạng BK + chuỗi chữ số, ví dụ BK1716123456) trong nội dung chuyển khoản
        if (preg_match('/(BK\d+)/i', $content, $matches)) {
            $bookingCode = strtoupper($matches[1]);

            // Tìm Booking tương ứng trong cơ sở dữ liệu
            $booking = \App\Models\Booking::where('booking_code', $bookingCode)->first();

            if ($booking) {
                // Nếu đơn hàng đã được xác nhận từ trước
                if ($booking->status === 'confirmed') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Booking đã được xác nhận thanh toán trước đó.'
                    ], 200);
                }

                // Kiểm tra số tiền chuyển khoản có khớp hoặc lớn hơn số tiền của Booking không
                if ((int) $booking->total_amount <= (int) $transferAmount) {
                    // Cập nhật trạng thái Booking
                    $booking->status = 'confirmed';
                    $booking->is_paid = true;
                    $booking->notes = ($booking->notes ? $booking->notes . ' | ' : '') . 'Thanh toán tự động qua SePay Webhook.';
                    $booking->save();

                    // Cập nhật trạng thái Payment
                    $payment = \App\Models\Payment::where('booking_id', $booking->id)->first();
                    if ($payment) {
                        $payment->payment_status = 'completed';
                        $payment->transaction_code = $payload['referenceCode'] ?? ($payload['id'] ?? null);
                        $payment->paid_at = now();
                        $payment->payment_gateway = $payload['gateway'] ?? 'SePay';
                        $payment->gateway_response = json_encode($payload);
                        $payment->save();
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Xác nhận thanh toán thành công qua SePay.'
                    ], 200);
                } else {
                    // Cập nhật ghi chú nếu khách chuyển thiếu tiền
                    $booking->notes = ($booking->notes ? $booking->notes . ' | ' : '') . 'Lỗi: Chuyển khoản thiếu tiền. Nhận: ' . number_format($transferAmount) . 'đ';
                    $booking->save();

                    return response()->json([
                        'success' => false,
                        'message' => 'Số tiền chuyển khoản không đủ.'
                    ], 400);
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy mã booking hợp lệ trong nội dung chuyển khoản.'
        ], 400);
    }
}
