<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RoomType;
use App\Models\User;
use App\Models\Workspace;
use App\Services\BookingLifecycleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_unpaid_booking_is_cancelled_after_one_minute(): void
    {
        $user = User::factory()->create();
        $workspace = $this->workspace();
        $booking = $this->booking($user, $workspace, [
            'status' => 'pending',
        ]);
        $booking->forceFill([
            'created_at' => now()->subMinutes(2),
            'updated_at' => now()->subMinutes(2),
        ])->save();

        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'amount' => 100000,
            'tax' => 8000,
            'final_amount' => 108000,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'pending',
        ]);

        app(BookingLifecycleService::class)->cancelExpiredPendingBookings($user);

        $booking->refresh();
        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('failed', $booking->payment->payment_status);
    }

    public function test_paid_booking_cancelled_before_checkin_gets_eighty_percent_refund(): void
    {
        $user = User::factory()->create();
        $workspace = $this->workspace();
        $booking = $this->booking($user, $workspace, [
            'booking_date' => now()->addDay()->toDateString(),
            'status' => 'confirmed',
            'total_amount' => 1000000,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'amount' => 925926,
            'tax' => 74074,
            'final_amount' => 1000000,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'completed',
            'paid_at' => now(),
        ]);

        $result = app(BookingLifecycleService::class)->cancelByUser($booking, $user, [
            'refund_receiver_name' => 'Nguyen Van A',
            'refund_bank_name' => 'Vietcombank (VCB)',
            'refund_bank_account_number' => '123456789',
            'cancellation_reason_code' => 'wrong_booking',
            'cancellation_reason_label' => 'Đặt nhầm ngày/nhầm giờ/nhầm cơ sở.',
        ]);

        $booking->refresh();
        $this->assertTrue($result['success']);
        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('refunded', $booking->payment->payment_status);
        $this->assertEquals(200000, (float) $booking->cancel_fee_amount);
        $this->assertEquals(800000, (float) $booking->refund_amount);
        $this->assertSame('Nguyen Van A', $booking->refund_receiver_name);
        $this->assertSame('Vietcombank (VCB)', $booking->refund_bank_name);
        $this->assertSame('123456789', $booking->refund_bank_account_number);
        $this->assertSame('wrong_booking', $booking->cancellation_reason_code);
    }

    public function test_unpaid_booking_can_be_cancelled_without_refund_or_survey_details(): void
    {
        $user = User::factory()->create();
        $workspace = $this->workspace();
        $booking = $this->booking($user, $workspace, [
            'status' => 'pending',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'amount' => 100000,
            'tax' => 8000,
            'final_amount' => 108000,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->post(route('account.bookings.cancel', $booking));

        $response->assertRedirect(route('account.bookings'));

        $booking->refresh();
        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('failed', $booking->payment->payment_status);
        $this->assertNull($booking->refund_receiver_name);
        $this->assertNull($booking->cancellation_reason_code);
    }

    public function test_paid_booking_can_store_multiple_cancellation_reasons(): void
    {
        $user = User::factory()->create();
        $workspace = $this->workspace();
        $booking = $this->booking($user, $workspace, [
            'booking_date' => now()->addDay()->toDateString(),
            'status' => 'confirmed',
            'total_amount' => 1000000,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'amount' => 925926,
            'tax' => 74074,
            'final_amount' => 1000000,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'completed',
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($user)->post(route('account.bookings.cancel', $booking), [
            'refund_receiver_name' => 'Nguyen Van A',
            'refund_bank_name' => 'vietcombank',
            'refund_bank_account_number' => '123456789',
            'cancellation_reason_codes' => ['wrong_booking', 'bad_weather'],
        ]);

        $response->assertRedirect(route('account.bookings'));

        $booking->refresh();
        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('wrong_booking,bad_weather', $booking->cancellation_reason_code);
        $this->assertStringContainsString('Thời tiết xấu', $booking->cancellation_reason);
    }

    public function test_paid_booking_cannot_be_cancelled_after_checkin(): void
    {
        $user = User::factory()->create();
        $workspace = $this->workspace();
        $booking = $this->booking($user, $workspace, [
            'booking_date' => now()->toDateString(),
            'start_time' => now()->subHour()->format('H:i:s'),
            'end_time' => now()->addHour()->format('H:i:s'),
            'status' => 'confirmed',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'amount' => 100000,
            'tax' => 8000,
            'final_amount' => 108000,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'completed',
            'paid_at' => now(),
        ]);

        $result = app(BookingLifecycleService::class)->cancelByUser($booking, $user);

        $this->assertFalse($result['success']);
        $this->assertSame('confirmed', $booking->fresh()->status);
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

    private function booking(User $user, Workspace $workspace, array $overrides = []): Booking
    {
        return Booking::create(array_merge([
            'booking_code' => 'BK' . random_int(100000, 999999),
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'booking_date' => now()->addDay()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'duration_hours' => 2,
            'base_price' => 200000,
            'tax' => 16000,
            'total_amount' => 216000,
            'status' => 'pending',
        ], $overrides));
    }
}
