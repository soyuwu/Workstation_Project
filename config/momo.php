<?php

return [
    'partner_code' => env('MOMO_PARTNER_CODE', 'MOMO'), // Mã đối tác do MoMo cấp
    'access_key' => env('MOMO_ACCESS_KEY', 'MOMO'), // Access Key do MoMo cấp
    'secret_key' => env('MOMO_SECRET_KEY', 'MOMO'), // Secret Key để tạo chữ ký
    'endpoint' => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'), // URL tạo yêu cầu thanh toán (Môi trường test)
    'return_url' => env('APP_URL') . '/payment/momo-return', // Nơi MoMo trả user về sau khi thanh toán
    'notify_url' => env('APP_URL') . '/payment/momo-ipn', // Nơi MoMo gọi webhook ngầm báo trạng thái
];
