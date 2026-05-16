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
        $booking = \App\Models\Booking::find($orderId);
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
}
