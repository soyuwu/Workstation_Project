<?php

namespace App\Services;

use App\Models\Booking;
use App\Support\Money;
use Illuminate\Support\Facades\Log;

class VietQrService
{
    public function dataFor(Booking $booking): ?array
    {
        $bankId = (string) config('vietqr.bank_id', '970416');
        $bankName = (string) config('vietqr.bank_name');
        $accountNo = (string) config('vietqr.account_no', '27800607');
        $accountName = (string) config('vietqr.account_name', 'LUONG LAM KHANH');
        $template = (string) config('vietqr.template', 'compact');

        if ($bankId === '' || $accountNo === '' || $accountName === '') {
            Log::error('VietQR config missing', [
                'booking_code' => $booking->booking_code,
                'bank_id' => $bankId ?: '[missing]',
                'bank_name' => $bankName ?: '[missing]',
                'account_no_set' => $accountNo !== '',
                'account_name_set' => $accountName !== '',
            ]);

            return null;
        }

        $amount = Money::vnd($booking->total_amount);
        $addInfo = urlencode($booking->booking_code);
        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-{$template}.png?amount={$amount}&addInfo={$addInfo}&accountName=" . urlencode($accountName);

        return compact('qrUrl', 'accountNo', 'accountName', 'bankName');
    }
}
