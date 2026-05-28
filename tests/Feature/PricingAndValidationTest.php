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

class PricingAndValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_hourly_booking_pricing_is_rounded_consistently_in_vnd(): void
    {
        $user = User::factory()->create();
        $workspace = $this->workspace([
            'price_per_hour' => 100000,
            'min_booking_hours' => 1,
        ]);

        $date = now()->addDay()->format('Y-m-d');

        $response = $this->actingAs($user)->post(route('booking.process'), [
            'room_id' => $workspace->id,
            'date' => $date,
            'start_time' => '08:00',
            'end_time' => '09:30',
            'payment_method' => 'bank_transfer',
        ]);

        $response->assertRedirect();

        $booking = Booking::query()->firstOrFail();
        $payment = Payment::query()->where('booking_id', $booking->id)->firstOrFail();

        $this->assertSame('pending', $booking->status);
        $this->assertNotEmpty($booking->booking_code);

        $this->assertEquals(150000, (int) round((float) $booking->base_price));
        $this->assertEquals(12000, (int) round((float) $booking->tax));
        $this->assertEquals(162000, (int) round((float) $booking->total_amount));

        $this->assertEquals(150000, (int) round((float) $payment->amount));
        $this->assertEquals(0, (int) round((float) $payment->discount));
        $this->assertEquals(12000, (int) round((float) $payment->tax));
        $this->assertEquals(162000, (int) round((float) $payment->final_amount));
    }

    public function test_monthly_booking_pricing_is_rounded_consistently_in_vnd(): void
    {
        $user = User::factory()->create();
        $workspace = $this->workspace([
            'price_per_hour' => 100000,
            'price_per_month' => 1000000,
            'min_booking_hours' => 1,
        ]);

        $date = now()->addDay()->format('Y-m-d');

        $response = $this->actingAs($user)->post(route('booking.monthly.process'), [
            'room_id' => $workspace->id,
            'start_date' => $date,
            'duration_months' => 3,
            'payment_method' => 'bank_transfer',
        ]);

        $response->assertRedirect();

        $booking = Booking::query()->firstOrFail();
        $payment = Payment::query()->where('booking_id', $booking->id)->firstOrFail();

        $this->assertSame('pending', $booking->status);

        // subtotal = 1,000,000 * 3 = 3,000,000
        // duration discount (5%) = 150,000
        // after discount = 2,850,000
        // tax (8%) = 228,000
        // total = 3,078,000
        $this->assertEquals(3000000, (int) round((float) $booking->base_price));
        $this->assertEquals(228000, (int) round((float) $booking->tax));
        $this->assertEquals(3078000, (int) round((float) $booking->total_amount));

        $this->assertEquals(3000000, (int) round((float) $payment->amount));
        $this->assertEquals(150000, (int) round((float) $payment->discount));
        $this->assertEquals(228000, (int) round((float) $payment->tax));
        $this->assertEquals(3078000, (int) round((float) $payment->final_amount));
    }

    public function test_hourly_checkout_with_invalid_query_does_not_500(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('booking.checkout', [
            'room_id' => 'abc',
            'date' => 'not-a-date',
            'start_time' => 'x',
            'end_time' => 'y',
        ]));

        $response->assertRedirect(route('booking.index'));
        $response->assertSessionHas('error');
    }

    public function test_monthly_checkout_with_invalid_query_does_not_500(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('booking.monthly.checkout', [
            'room_id' => 'abc',
            'start_date' => 'not-a-date',
            'duration_months' => 2,
        ]));

        $response->assertRedirect(route('booking.index'));
        $response->assertSessionHas('error');
    }

    private function workspace(array $overrides = []): Workspace
    {
        $area = Area::create(['name' => 'Test area', 'floor_number' => 1]);
        $roomType = RoomType::create(['name' => 'Test type']);

        return Workspace::create(array_merge([
            'area_id' => $area->id,
            'room_type_id' => $roomType->id,
            'code' => 'T-' . uniqid(),
            'name' => 'Test workspace',
            'capacity' => 4,
            'price_per_hour' => 100000,
            'price_per_month' => 1000000,
            'min_booking_hours' => 1,
            'status' => 'active',
        ], $overrides));
    }
}

