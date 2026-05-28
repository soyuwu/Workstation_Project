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

    public function test_sepay_webhook_rounds_transfer_amount_half_up_before_matching(): void
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

