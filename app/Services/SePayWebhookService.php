<?php

namespace App\Services;

use App\Models\Booking;
use App\Support\Money;
use Illuminate\Support\Facades\Log;

class SePayWebhookService
{
    public function __construct(private PaymentService $paymentService)
    {
    }

    public function handle(array $payload, ?string $authorizationHeader): array
    {
        $webhookToken = (string) config('sepay.webhook_token', '');

        if ($webhookToken !== '' && $authorizationHeader !== 'Apikey ' . $webhookToken) {
            return [
                'status' => 401,
                'body' => [
                    'success' => false,
                    'message' => 'Unauthorized',
                ],
            ];
        }

        Log::info('SePay Webhook Received:', $payload);

        $bookingCode = $this->extractBookingCode((string) ($payload['content'] ?? ''));
        if (!$bookingCode) {
            return $this->invalidBookingCodeResponse();
        }

        $booking = Booking::where('booking_code', $bookingCode)->first();
        if (!$booking) {
            return $this->invalidBookingCodeResponse();
        }

        $booking = $this->paymentService->syncPayableBooking($booking);

        if (
            in_array($booking->status, ['confirmed', 'completed'], true)
            || $booking->payment?->payment_status === 'completed'
        ) {
            return [
                'status' => 200,
                'body' => [
                    'success' => true,
                    'message' => 'Booking đã được xác nhận thanh toán trước đó.',
                ],
            ];
        }

        if ($booking->status === 'cancelled') {
            return [
                'status' => 409,
                'body' => [
                    'success' => false,
                    'message' => 'Booking da bi huy, can doi soat thu cong neu tien da vao tai khoan.',
                ],
            ];
        }

        $transferAmount = Money::vnd($payload['transferAmount'] ?? 0);
        $expectedAmount = Money::vnd($booking->total_amount);

        if ($transferAmount < $expectedAmount) {
            $this->paymentService->appendBookingNote(
                $booking,
                'Lỗi: Chuyển khoản thiếu tiền. Nhận: ' . number_format($transferAmount) . 'đ'
            );

            return [
                'status' => 400,
                'body' => [
                    'success' => false,
                    'message' => 'Số tiền chuyển khoản không đủ.',
                ],
            ];
        }

        $this->paymentService->markCompleted($booking, [
            'transaction_code' => $payload['referenceCode'] ?? ($payload['id'] ?? null),
            'payment_gateway' => $payload['gateway'] ?? 'SePay',
            'gateway_response' => $payload,
            'note' => 'Thanh toán tự động qua SePay Webhook.',
        ]);

        return [
            'status' => 200,
            'body' => [
                'success' => true,
                'message' => 'Xác nhận thanh toán thành công qua SePay.',
            ],
        ];
    }

    private function extractBookingCode(string $content): ?string
    {
        if (!preg_match('/(BK\d+)/i', $content, $matches)) {
            return null;
        }

        return strtoupper($matches[1]);
    }

    private function invalidBookingCodeResponse(): array
    {
        return [
            'status' => 400,
            'body' => [
                'success' => false,
                'message' => 'Không tìm thấy mã booking hợp lệ trong nội dung chuyển khoản.',
            ],
        ];
    }
}
