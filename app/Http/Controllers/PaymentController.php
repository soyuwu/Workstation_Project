<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        // Nếu dùng số ảo (123456789), nhiều App Ngân hàng sẽ báo lỗi "Mã QR không hợp lệ" và KHÔNG TỰ ĐIỀN SỐ TIỀN/NỘI DUNG.
        $bankId = env('VIETQR_BANK_ID', '970436'); // BIN của Vietcombank
        $accountNo = env('VIETQR_ACCOUNT_NO', '0123456789'); // <--- BẠN CẦN THAY THÀNH SỐ TÀI KHOẢN CỦA BẠN
        $accountName = env('VIETQR_ACCOUNT_NAME', 'WORKSTATION CO LTD'); 

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

        $booking->notes = ($booking->notes ? $booking->notes . ' | ' : '') . 'Khách đã báo chuyển khoản, chờ Admin check biến động số dư.';
        $booking->is_paid = true;
        $booking->save();

        // Chuyển sang trang báo thành công
        return view('payment.success', [
            'booking' => $booking,
            'message' => 'Cảm ơn bạn! Hệ thống đang kiểm tra biến động số dư và sẽ xác nhận đặt phòng trong ít phút.'
        ]);
    }
}
