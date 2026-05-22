<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Area;
use App\Models\RoomType;
use App\Models\Workspace;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\DiscountCode;
use Illuminate\Support\Facades\Hash;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $workspace;
    protected $booking;
    protected $payment;

    protected function setUp(): void
    {
        parent::setUp();

        // Unguard models to allow easy factory creation
        \Illuminate\Database\Eloquent\Model::unguard();

        // 1. Create a test user
        $this->user = User::create([
            'name' => 'Nguyễn Văn Minh',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'active',
            'phone' => '0987654321',
            'email_verified_at' => now(),
        ]);

        // 2. Create Area and Room Type
        $area = Area::create([
            'name' => 'Khu A - Tầng trệt',
            'description' => 'Không gian sôi động',
        ]);

        $roomType = RoomType::create([
            'name' => 'Bàn làm việc',
            'description' => 'Chỗ ngồi thoải mái',
            'default_capacity' => 1,
            'default_hourly_rate' => 30000,
        ]);

        // 3. Create Workspace
        $this->workspace = Workspace::create([
            'code' => 'C-10',
            'area_id' => $area->id,
            'room_type_id' => $roomType->id,
            'name' => 'Bàn chia sẻ C-10',
            'capacity' => 1,
            'description' => 'Chỗ ngồi sảnh chính',
            'price_per_hour' => 25000,
            'min_booking_hours' => 1,
            'status' => 'active',
        ]);

        // 4. Create Booking
        $this->booking = Booking::create([
            'booking_code' => 'WS-UP-9872',
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'duration_hours' => 2,
            'base_price' => 50000,
            'tax' => 5000,
            'total_amount' => 55000,
            'status' => 'pending',
            'qr_code' => 'QR_CODE_9872',
        ]);

        // 5. Create Payment
        $this->payment = Payment::create([
            'booking_id' => $this->booking->id,
            'user_id' => $this->user->id,
            'amount' => 50000,
            'tax' => 5000,
            'final_amount' => 55000,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'pending',
        ]);
    }

    /**
     * Test user cannot access profile without session.
     */
    public function test_cannot_access_profile_without_session(): void
    {
        $response = $this->get(route('profile'));

        $response->assertRedirect(route('logIn'));
        $response->assertSessionHas('error');
    }

    /**
     * Test user can access profile with session.
     */
    public function test_can_access_profile_with_session(): void
    {
        $response = $this->withSession(['user_id' => $this->user->id])
            ->get(route('profile'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.index');
        $response->assertViewHas('user');
        $response->assertViewHas('upcoming');
    }

    /**
     * Test updating basic profile info.
     */
    public function test_can_update_profile_info(): void
    {
        $response = $this->withSession(['user_id' => $this->user->id])
            ->post(route('profile.update'), [
                'name' => 'Nguyễn Minh Quân',
                'phone' => '0912345678',
            ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Nguyễn Minh Quân',
            'phone' => '0912345678',
        ]);
    }

    /**
     * Test quick verify account helper.
     */
    public function test_can_quick_verify_account(): void
    {
        $unverifiedUser = User::create([
            'name' => 'Chưa kích hoạt',
            'email' => 'unverified@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'inactive',
            'email_verified_at' => null,
        ]);

        $response = $this->withSession(['user_id' => $unverifiedUser->id])
            ->post(route('profile.verify_quick'));

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $unverifiedUser->id,
            'status' => 'active',
        ]);
        $this->assertNotNull($unverifiedUser->fresh()->email_verified_at);
    }

    /**
     * Test changing password successfully.
     */
    public function test_can_change_password_with_correct_current_password(): void
    {
        $response = $this->withSession(['user_id' => $this->user->id])
            ->post(route('profile.password'), [
                'current_password' => 'password',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('success');

        $this->assertTrue(Hash::check('newpassword123', $this->user->fresh()->password));
    }

    /**
     * Test changing password fails with incorrect current password.
     */
    public function test_cannot_change_password_with_incorrect_current_password(): void
    {
        $response = $this->withSession(['user_id' => $this->user->id])
            ->post(route('profile.password'), [
                'current_password' => 'wrongpassword',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('error', 'Mật khẩu hiện tại không chính xác.');
        $this->assertTrue(Hash::check('password', $this->user->fresh()->password));
    }

    /**
     * Test canceling a booking.
     */
    public function test_can_cancel_booking(): void
    {
        $response = $this->withSession(['user_id' => $this->user->id])
            ->post(route('profile.booking.cancel', $this->booking->id));

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('success');

        $this->assertEquals('cancelled', $this->booking->fresh()->status);
        $this->assertEquals('failed', $this->payment->fresh()->payment_status);
    }

    /**
     * Test accessing payment gateway mock.
     */
    public function test_can_access_payment_gateway(): void
    {
        $response = $this->withSession(['user_id' => $this->user->id])
            ->get(route('profile.payment.pay', $this->payment->id));

        $response->assertStatus(200);
        $response->assertViewIs('profile.payment_gateway');
        $response->assertViewHas('payment');
    }

    /**
     * Test confirming payment successfully.
     */
    public function test_can_confirm_payment(): void
    {
        $response = $this->withSession(['user_id' => $this->user->id])
            ->post(route('profile.payment.confirm', $this->payment->id), [
                'payment_method' => 'momo',
            ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('success');

        $this->assertEquals('completed', $this->payment->fresh()->payment_status);
        $this->assertEquals('confirmed', $this->booking->fresh()->status);
        $this->assertNotNull($this->payment->fresh()->transaction_code);
    }

    /**
     * Test submitting a workspace review.
     */
    public function test_can_submit_review_for_completed_booking(): void
    {
        // Change booking status to completed so it can be reviewed
        $this->booking->update(['status' => 'completed']);

        $response = $this->withSession(['user_id' => $this->user->id])
            ->post(route('profile.booking.review'), [
                'booking_id' => $this->booking->id,
                'workspace_id' => $this->workspace->id,
                'rating' => 5,
                'content' => 'Không gian làm việc tuyệt vời, nhân viên chu đáo!',
            ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'booking_id' => $this->booking->id,
            'workspace_id' => $this->workspace->id,
            'user_id' => $this->user->id,
            'rating' => 5,
            'content' => 'Không gian làm việc tuyệt vời, nhân viên chu đáo!',
        ]);
    }
}
