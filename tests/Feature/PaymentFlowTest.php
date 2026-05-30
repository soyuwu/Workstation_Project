<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RoomType;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_vietqr_marks_payment_as_reported_without_completing_it(): void
    {
        $booking = $this->createPendingBooking();

        $response = $this->post(route('payment.vietqr.confirm', ['booking_code' => $booking->booking_code]));

        $response->assertRedirect(route('payment.success', ['booking_code' => $booking->booking_code]));

        $booking->refresh();
        $this->assertSame('pending', $booking->status);
        $this->assertSame('pending', $booking->payment->payment_status);
        $this->assertNotNull($booking->payment->reported_at);
        $this->assertSame('manual', $booking->payment->payment_gateway);
        $this->assertStringContainsString('Khách đã báo chuyển khoản', (string) $booking->notes);
    }

    public function test_confirm_vietqr_redirects_with_error_when_booking_has_expired(): void
    {
        $booking = $this->createPendingBooking();
        $booking->forceFill([
            'created_at' => now()->subMinutes(2),
            'updated_at' => now()->subMinutes(2),
        ])->save();

        $response = $this->post(route('payment.vietqr.confirm', ['booking_code' => $booking->booking_code]));

        $response->assertRedirect(route('payment.success', ['booking_code' => $booking->booking_code]));
        $response->assertSessionHas('error', 'Đơn hàng đã quá thời gian thanh toán và bị hủy.');
    }

    public function test_vietqr_page_returns_unavailable_view_when_config_is_missing(): void
    {
        config([
            'vietqr.bank_id' => '',
            'vietqr.account_no' => '',
            'vietqr.account_name' => '',
        ]);

        $booking = $this->createPendingBooking();

        $response = $this->get(route('payment.vietqr', ['booking_code' => $booking->booking_code]));

        $response->assertStatus(503);
        $response->assertViewIs('payment.vietqr_unavailable');
    }

    public function test_momo_ipn_rejects_invalid_signature(): void
    {
        $response = $this->postJson(route('payment.momo_ipn'), [
            'orderId' => 'BK123456',
            'signature' => 'invalid',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Invalid signature']);
    }

    public function test_momo_ipn_marks_booking_as_completed_when_payment_succeeds(): void
    {
        $booking = $this->createPendingBooking();
        $payload = $this->validMomoPayload($booking->booking_code, 0);

        $response = $this->postJson(route('payment.momo_ipn'), $payload);

        $response->assertNoContent();

        $booking->refresh();
        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('completed', $booking->payment->payment_status);
        $this->assertSame((string) $payload['transId'], $booking->payment->transaction_code);
        $this->assertSame('MoMo', $booking->payment->payment_gateway);
    }

    public function test_momo_ipn_marks_booking_as_failed_when_payment_fails(): void
    {
        $booking = $this->createPendingBooking();
        $payload = $this->validMomoPayload($booking->booking_code, 1006);

        $response = $this->postJson(route('payment.momo_ipn'), $payload);

        $response->assertNoContent();

        $booking->refresh();
        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('failed', $booking->payment->payment_status);
        $this->assertSame('MoMo', $booking->payment->payment_gateway);
    }

    public function test_admin_can_approve_booking_via_shared_payment_service(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
        $booking = $this->createPendingBooking();

        $response = $this
            ->actingAs($admin)
            ->postJson(route('admin.booking.approve', ['id' => $booking->id]));

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $booking->refresh();
        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('completed', $booking->payment->payment_status);
        $this->assertNotNull($booking->payment->paid_at);
    }

    public function test_admin_can_cancel_booking_via_shared_payment_service(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
        $booking = $this->createPendingBooking();

        $response = $this
            ->actingAs($admin)
            ->postJson(route('admin.booking.cancel', ['id' => $booking->id]));

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $booking->refresh();
        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('failed', $booking->payment->payment_status);
    }

    private function createPendingBooking(): Booking
    {
        $user = User::factory()->create();
        $workspace = $this->workspace();

        $booking = Booking::create([
            'booking_code' => 'BK' . random_int(100000, 999999),
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'booking_date' => now()->addDay()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'duration_hours' => 2,
            'base_price' => 92593,
            'tax' => 7407,
            'total_amount' => 100000,
            'status' => 'pending',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'amount' => 92593,
            'tax' => 7407,
            'final_amount' => 100000,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'pending',
        ]);

        return $booking->load('payment');
    }

    private function validMomoPayload(string $orderId, int $resultCode): array
    {
        $payload = [
            'partnerCode' => config('momo.partner_code'),
            'accessKey' => config('momo.access_key'),
            'requestId' => 'req-' . uniqid(),
            'amount' => '100000',
            'orderId' => $orderId,
            'orderInfo' => 'Thanh toán dịch vụ Đặt phòng',
            'orderType' => 'momo_wallet',
            'transId' => 'trans-' . uniqid(),
            'message' => $resultCode === 0 ? 'Successful.' : 'Transaction failed.',
            'localMessage' => $resultCode === 0 ? 'Successful.' : 'Transaction failed.',
            'responseTime' => (string) round(microtime(true) * 1000),
            'errorCode' => (string) $resultCode,
            'payType' => 'qr',
            'extraData' => '',
            'resultCode' => (string) $resultCode,
        ];

        $rawHash = 'accessKey=' . $payload['accessKey']
            . '&amount=' . $payload['amount']
            . '&extraData=' . $payload['extraData']
            . '&message=' . $payload['message']
            . '&orderId=' . $payload['orderId']
            . '&orderInfo=' . $payload['orderInfo']
            . '&orderType=' . $payload['orderType']
            . '&partnerCode=' . $payload['partnerCode']
            . '&payType=' . $payload['payType']
            . '&requestId=' . $payload['requestId']
            . '&responseTime=' . $payload['responseTime']
            . '&resultCode=' . $payload['resultCode']
            . '&transId=' . $payload['transId'];

        $payload['signature'] = hash_hmac('sha256', $rawHash, config('momo.secret_key'));

        return $payload;
    }

    private function workspace(): Workspace
    {
        $area = Area::create(['name' => 'Test area', 'floor_number' => 1]);
        $roomType = RoomType::create(['name' => 'Test type']);

        return Workspace::create([
            'area_id' => $area->id,
            'room_type_id' => $roomType->id,
            'code' => 'T-' . uniqid(),
            'name' => 'Test workspace',
            'capacity' => 4,
            'price_per_hour' => 100000,
            'min_booking_hours' => 1,
            'status' => 'active',
        ]);
    }
}
