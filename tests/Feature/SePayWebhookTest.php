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

class SePayWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_sepay_webhook_requires_valid_authorization_when_token_is_configured(): void
    {
        config(['sepay.webhook_token' => 'secret-token']);

        $response = $this->postJson(route('payment.sepay_webhook'), [
            'content' => 'Thanh toan BK123456',
            'transferAmount' => 100000,
        ]);

        $response->assertUnauthorized();
        $response->assertJson([
            'success' => false,
            'message' => 'Unauthorized',
        ]);
    }

    public function test_sepay_webhook_rejects_payload_without_valid_booking_code(): void
    {
        $response = $this->postJson(route('payment.sepay_webhook'), [
            'content' => 'Khong co ma booking',
            'transferAmount' => 100000,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Không tìm thấy mã booking hợp lệ trong nội dung chuyển khoản.',
        ]);
    }

    public function test_sepay_webhook_rounds_transfer_amount_half_up_before_matching(): void
    {
        $user = User::factory()->create();
        $workspace = $this->workspace();

        $booking = $this->createPendingBooking($user, $workspace, 100000);

        $payload = [
            'content' => 'Thanh toan ' . $booking->booking_code,
            'transferAmount' => 99999.6,
            'referenceCode' => 'ref-1',
            'gateway' => 'SePay',
        ];

        $response = $this->postJson(route('payment.sepay_webhook'), $payload);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $booking->refresh();
        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('completed', $booking->payment->payment_status);
    }

    public function test_sepay_webhook_returns_success_when_booking_was_already_confirmed(): void
    {
        $user = User::factory()->create();
        $workspace = $this->workspace();
        $booking = $this->createPendingBooking($user, $workspace, 100000);

        $booking->status = 'confirmed';
        $booking->save();
        $booking->payment->forceFill([
            'payment_status' => 'completed',
            'paid_at' => now(),
        ])->save();

        $response = $this->postJson(route('payment.sepay_webhook'), [
            'content' => 'Thanh toan ' . $booking->booking_code,
            'transferAmount' => 100000,
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Booking đã được xác nhận thanh toán trước đó.',
        ]);
    }

    public function test_sepay_webhook_returns_conflict_for_cancelled_booking(): void
    {
        $user = User::factory()->create();
        $workspace = $this->workspace();
        $booking = $this->createPendingBooking($user, $workspace, 100000);

        $booking->status = 'cancelled';
        $booking->save();

        $response = $this->postJson(route('payment.sepay_webhook'), [
            'content' => 'Thanh toan ' . $booking->booking_code,
            'transferAmount' => 100000,
        ]);

        $response->assertStatus(409);
        $response->assertJson([
            'success' => false,
            'message' => 'Booking da bi huy, can doi soat thu cong neu tien da vao tai khoan.',
        ]);
    }

    public function test_sepay_webhook_marks_insufficient_transfer_without_completing_payment(): void
    {
        $user = User::factory()->create();
        $workspace = $this->workspace();
        $booking = $this->createPendingBooking($user, $workspace, 100000);

        $response = $this->postJson(route('payment.sepay_webhook'), [
            'content' => 'Thanh toan ' . $booking->booking_code,
            'transferAmount' => 90000,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Số tiền chuyển khoản không đủ.',
        ]);

        $booking->refresh();
        $this->assertSame('pending', $booking->status);
        $this->assertSame('pending', $booking->payment->payment_status);
        $this->assertStringContainsString('Lỗi: Chuyển khoản thiếu tiền.', (string) $booking->notes);
    }

    public function test_sepay_webhook_is_idempotent_on_repeated_success_calls(): void
    {
        $user = User::factory()->create();
        $workspace = $this->workspace();
        $booking = $this->createPendingBooking($user, $workspace, 100000);

        $payload = [
            'content' => 'Thanh toan ' . $booking->booking_code,
            'transferAmount' => 100000,
            'referenceCode' => 'ref-repeat',
            'gateway' => 'SePay',
        ];

        $this->postJson(route('payment.sepay_webhook'), $payload)->assertOk();
        $this->postJson(route('payment.sepay_webhook'), $payload)->assertOk();

        $booking->refresh();
        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('completed', $booking->payment->payment_status);
        $this->assertSame(1, substr_count((string) $booking->notes, 'Thanh toán tự động qua SePay Webhook.'));
    }

    private function createPendingBooking(User $user, Workspace $workspace, int $totalAmount): Booking
    {
        $booking = Booking::create([
            'booking_code' => 'BK' . random_int(100000, 999999),
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'booking_date' => now()->addDay()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'duration_hours' => 2,
            'base_price' => $totalAmount - 7407,
            'tax' => 7407,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'amount' => $totalAmount - 7407,
            'tax' => 7407,
            'final_amount' => $totalAmount,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'pending',
        ]);

        return $booking->load('payment');
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
