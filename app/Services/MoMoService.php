<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoMoService
{
    /**
     * Tạo URL thanh toán MoMo (Phương thức thanh toán qua ví MoMo - quét mã QR hoặc App)
     * 
     * @param string $orderId Mã đơn hàng (Giao dịch)
     * @param int $amount Số tiền thanh toán
     * @param string $orderInfo Thông tin đơn hàng
     * @return array Trả về mảng chứa payUrl hoặc thông báo lỗi
     */
    public function createPaymentUrl($orderId, $amount, $orderInfo = "Thanh toán dịch vụ Đặt phòng")
    {
        $endpoint = config('momo.endpoint');
        $partnerCode = config('momo.partner_code');
        $accessKey = config('momo.access_key');
        $secretKey = config('momo.secret_key');
        $returnUrl = config('momo.return_url');
        $notifyUrl = config('momo.notify_url');
        
        $requestId = time() . ""; // Yêu cầu mỗi requestId phải là duy nhất
        $requestType = "captureWallet"; // Loại giao dịch thanh toán ví MoMo
        $extraData = "";

        // Sắp xếp các tham số để tạo chữ ký (signature) theo đúng thứ tự tài liệu MoMo
        $rawHash = "accessKey=" . $accessKey .
                   "&amount=" . $amount .
                   "&extraData=" . $extraData .
                   "&ipnUrl=" . $notifyUrl .
                   "&orderId=" . $orderId .
                   "&orderInfo=" . $orderInfo .
                   "&partnerCode=" . $partnerCode .
                   "&redirectUrl=" . $returnUrl .
                   "&requestId=" . $requestId .
                   "&requestType=" . $requestType;

        // Tạo chữ ký bằng thuật toán HMAC SHA256
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        // Body của request gửi lên MoMo
        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "WorkStation",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $returnUrl,
            'ipnUrl' => $notifyUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        try {
            // Gửi HTTP POST request lên MoMo API (bỏ qua xác thực SSL ở môi trường test/local)
            $response = Http::withoutVerifying()->post($endpoint, $data);
            $jsonResult = $response->json();

            Log::info("MoMo Create Payment Response", $jsonResult);

            if (isset($jsonResult['resultCode']) && $jsonResult['resultCode'] == 0) {
                return [
                    'success' => true,
                    'payUrl' => $jsonResult['payUrl'], // URL để chuyển hướng người dùng sang trang thanh toán MoMo
                ];
            }

            return [
                'success' => false,
                'message' => $jsonResult['message'] ?? 'Có lỗi xảy ra khi tạo thanh toán MoMo.',
            ];

        } catch (\Exception $e) {
            Log::error("MoMo Create Payment Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Không thể kết nối đến MoMo: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Xác thực chữ ký dữ liệu trả về từ MoMo để đảm bảo tính toàn vẹn.
     */
    public function verifySignature($data)
    {
        $accessKey = config('momo.access_key');
        $secretKey = config('momo.secret_key');
        
        $rawHash = "accessKey=" . $accessKey .
                   "&amount=" . $data['amount'] .
                   "&extraData=" . $data['extraData'] .
                   "&message=" . $data['message'] .
                   "&orderId=" . $data['orderId'] .
                   "&orderInfo=" . $data['orderInfo'] .
                   "&orderType=" . $data['orderType'] .
                   "&partnerCode=" . $data['partnerCode'] .
                   "&payType=" . $data['payType'] .
                   "&requestId=" . $data['requestId'] .
                   "&responseTime=" . $data['responseTime'] .
                   "&resultCode=" . $data['resultCode'] .
                   "&transId=" . $data['transId'];

        $expectedSignature = hash_hmac("sha256", $rawHash, $secretKey);

        return hash_equals($expectedSignature, $data['signature']);
    }
}
