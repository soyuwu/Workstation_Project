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

        $result = app(BookingLifecycleService::class)->cancelByUser($booking, $user);

        $booking->refresh();
        $this->assertTrue($result['success']);
        $this->assertSame('cancelled', $booking->status);
        $this->assertSame('refunded', $booking->payment->payment_status);
        $this->assertEquals(200000, (float) $booking->cancel_fee_amount);
        $this->assertEquals(800000, (float) $booking->refund_amount);
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
