<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Area;
use App\Models\RoomType;
use App\Models\Workspace;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\DiscountCode;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ProfileMockSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo hoặc lấy Test User
        $user = User::where('email', 'customer@example.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Nguyễn Văn Minh',
                'email' => 'customer@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'active',
                'phone' => '0987654321',
                'email_verified_at' => now(), // Đã kích hoạt qua email
            ]);
        } else {
            $user->update([
                'name' => 'Nguyễn Văn Minh',
                'phone' => '0987654321',
                'email_verified_at' => now(),
            ]);
        }

        // Tạo thêm 1 user chưa kích hoạt để test
        $inactiveUser = User::where('email', 'unverified@example.com')->first();
        if (!$inactiveUser) {
            User::create([
                'name' => 'Trần Quang Huy',
                'email' => 'unverified@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'inactive',
                'phone' => '0901112222',
                'email_verified_at' => null, // Chưa kích hoạt
            ]);
        }

        // 2. Tạo Khu vực (Areas)
        $area1 = Area::firstOrCreate(['name' => 'Khu A - Tầng trệt'], ['description' => 'Không gian sôi động năng động, gần quầy Bar']);
        $area2 = Area::firstOrCreate(['name' => 'Khu B - Lầu 1'], ['description' => 'Không gian tập trung yên tĩnh cao độ']);

        // 3. Tạo Loại phòng (Room Types)
        $rtDesk = RoomType::firstOrCreate(['name' => 'Bàn làm việc'], [
            'description' => 'Chỗ ngồi làm việc thoải mái đầy đủ tiện nghi',
            'default_capacity' => 1,
            'default_hourly_rate' => 30000
        ]);
        
        $rtMeeting = RoomType::firstOrCreate(['name' => 'Phòng họp'], [
            'description' => 'Phòng họp tiêu chuẩn trang bị màn hình & bảng trắng',
            'default_capacity' => 8,
            'default_hourly_rate' => 150000
        ]);

        $rtOffice = RoomType::firstOrCreate(['name' => 'Văn phòng riêng'], [
            'description' => 'Văn phòng làm việc khép kín cho cả đội nhóm',
            'default_capacity' => 10,
            'default_hourly_rate' => 400000
        ]);

        // 4. Tạo Không gian làm việc (Workspaces)
        $wsSharedDesk = Workspace::firstOrCreate(['code' => 'C-10'], [
            'area_id' => $area1->id,
            'room_type_id' => $rtDesk->id,
            'name' => 'Bàn chia sẻ C-10',
            'capacity' => 1,
            'description' => 'Chỗ ngồi tự do tại khu vực sảnh chính sôi động.',
            'price_per_hour' => 25000,
            'min_booking_hours' => 1,
            'status' => 'active'
        ]);

        $wsDedicatedDesk = Workspace::firstOrCreate(['code' => 'F-05'], [
            'area_id' => $area2->id,
            'room_type_id' => $rtDesk->id,
            'name' => 'Bàn làm việc cố định F-05',
            'capacity' => 1,
            'description' => 'Bàn làm việc cố định tại khu yên tĩnh lầu 1.',
            'price_per_hour' => 35000,
            'min_booking_hours' => 1,
            'status' => 'active'
        ]);

        $wsMeetingSmall = Workspace::firstOrCreate(['code' => 'M-01'], [
            'area_id' => $area2->id,
            'room_type_id' => $rtMeeting->id,
            'name' => 'Phòng họp nhỏ M-01 (4-6 người)',
            'capacity' => 6,
            'description' => 'Phòng họp khép kín lý tưởng cho nhóm nhỏ brainstorming.',
            'price_per_hour' => 120000,
            'min_booking_hours' => 1,
            'status' => 'active'
        ]);

        $wsMeetingLarge = Workspace::firstOrCreate(['code' => 'M-02'], [
            'area_id' => $area1->id,
            'room_type_id' => $rtMeeting->id,
            'name' => 'Phòng họp lớn M-02 (10-12 người)',
            'capacity' => 12,
            'description' => 'Phòng họp lớn trang bị thiết bị họp trực tuyến hiện đại.',
            'price_per_hour' => 200000,
            'min_booking_hours' => 2,
            'status' => 'active'
        ]);

        $wsPrivatePod = Workspace::firstOrCreate(['code' => 'P-01'], [
            'area_id' => $area1->id,
            'room_type_id' => $rtDesk->id,
            'name' => 'Pod cá nhân P-01',
            'capacity' => 1,
            'description' => 'Cabin cách âm tuyệt đối dành riêng cho học tập và họp cá nhân online.',
            'price_per_hour' => 40000,
            'min_booking_hours' => 1,
            'status' => 'active'
        ]);

        // 5. Tạo Mã giảm giá (Discount Codes / Vouchers)
        DiscountCode::firstOrCreate(['code' => 'WELCOME10'], [
            'description' => 'Giảm 10% cho lần đặt đầu tiên',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_discount' => 50000,
            'usage_limit' => 100,
            'valid_from' => now()->subDays(5),
            'valid_until' => now()->addDays(30),
            'status' => 'active'
        ]);

        DiscountCode::firstOrCreate(['code' => 'WORKHARD'], [
            'description' => 'Ưu đãi hè giảm ngay 50.000đ',
            'discount_type' => 'fixed',
            'discount_value' => 50000,
            'max_discount' => 50000,
            'usage_limit' => 50,
            'valid_from' => now()->subDays(2),
            'valid_until' => now()->addDays(15),
            'status' => 'active'
        ]);

        DiscountCode::firstOrCreate(['code' => 'UITSTUDENT'], [
            'description' => 'Đồng hành sinh viên UIT giảm 20%',
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'max_discount' => 100000,
            'usage_limit' => 200,
            'valid_from' => now()->subDays(10),
            'valid_until' => now()->addDays(90),
            'status' => 'active'
        ]);

        DiscountCode::firstOrCreate(['code' => 'EXPIRED50'], [
            'description' => 'Ưu đãi đặc biệt đã hết hạn',
            'discount_type' => 'percentage',
            'discount_value' => 50,
            'max_discount' => 200000,
            'usage_limit' => 10,
            'usage_count' => 10,
            'valid_from' => now()->subDays(30),
            'valid_until' => now()->subDays(1),
            'status' => 'expired'
        ]);

        // Xóa sạch bookings và payments cũ của user để seeder sạch sẽ
        Booking::where('user_id', $user->id)->delete();

        // 6. Seed Đặt chỗ mẫu (Bookings & Payments)

        // --- Booking 1: Upcoming (Sắp diễn ra) ---
        $b1 = Booking::create([
            'booking_code' => 'WS-UP-9872',
            'user_id' => $user->id,
            'workspace_id' => $wsMeetingSmall->id,
            'booking_date' => Carbon::tomorrow()->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'duration_hours' => 2,
            'base_price' => 240000, // 120000 * 2
            'tax' => 24000,
            'total_amount' => 264000,
            'status' => 'confirmed',
            'qr_code' => 'QR_CODE_UPCOMING_9872',
            'notes' => 'Cần hỗ trợ 1 bảng trắng lớn và bút viết.',
        ]);
        Payment::create([
            'booking_id' => $b1->id,
            'user_id' => $user->id,
            'amount' => 240000,
            'tax' => 24000,
            'final_amount' => 264000,
            'payment_method' => 'momo',
            'payment_status' => 'completed',
            'transaction_code' => 'MOMO-TX-99882211',
            'paid_at' => now()->subHours(5),
            'payment_gateway' => 'MoMo',
        ]);

        // --- Booking 2: Active (Đang sử dụng) ---
        $b2 = Booking::create([
            'booking_code' => 'WS-ACT-3321',
            'user_id' => $user->id,
            'workspace_id' => $wsDedicatedDesk->id,
            'booking_date' => now()->toDateString(), // Hôm nay
            'start_time' => '08:00:00',
            'end_time' => '18:00:00',
            'duration_hours' => 10,
            'base_price' => 350000, // 35000 * 10
            'tax' => 35000,
            'total_amount' => 385000,
            'status' => 'confirmed', // hoặc đang dùng
            'qr_code' => 'QR_CODE_ACTIVE_3321',
            'notes' => 'Đang làm việc tại chỗ ngồi F-05.',
        ]);
        Payment::create([
            'booking_id' => $b2->id,
            'user_id' => $user->id,
            'amount' => 350000,
            'tax' => 35000,
            'final_amount' => 385000,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'completed',
            'transaction_code' => 'BANK-TX-554433221',
            'paid_at' => now()->subHours(2),
            'payment_gateway' => 'VietQR',
        ]);

        // --- Booking 3: Past Completed & Has Review (Lịch sử - Đã đánh giá) ---
        $b3 = Booking::create([
            'booking_code' => 'WS-PST-1102',
            'user_id' => $user->id,
            'workspace_id' => $wsPrivatePod->id,
            'booking_date' => now()->subDays(3)->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '13:00:00',
            'duration_hours' => 4,
            'actual_check_in' => now()->subDays(3)->setTime(9, 2, 0),
            'actual_check_out' => now()->subDays(3)->setTime(13, 0, 0),
            'base_price' => 160000, // 40000 * 4
            'tax' => 16000,
            'total_amount' => 176000,
            'status' => 'completed',
        ]);
        Payment::create([
            'booking_id' => $b3->id,
            'user_id' => $user->id,
            'amount' => 160000,
            'tax' => 16000,
            'final_amount' => 176000,
            'payment_method' => 'momo',
            'payment_status' => 'completed',
            'transaction_code' => 'MOMO-TX-11223344',
            'paid_at' => now()->subDays(3)->setTime(8, 45, 0),
            'payment_gateway' => 'MoMo',
        ]);
        Review::create([
            'rating' => 5,
            'content' => 'Cabin P-01 có khả năng cách âm cực tốt, làm việc vô cùng tập trung. Wi-Fi tốc độ cao, sẽ tiếp tục đặt!',
            'author_name' => 'Nguyễn Văn Minh',
            'author_role' => 'Software Engineer',
            'is_approved' => true,
            'user_id' => $user->id,
            'booking_id' => $b3->id,
            'workspace_id' => $wsPrivatePod->id,
        ]);

        // --- Booking 4: Past Completed & Has NO Review (Lịch sử - Chưa đánh giá để test nút Đánh giá) ---
        $b4 = Booking::create([
            'booking_code' => 'WS-PST-4491',
            'user_id' => $user->id,
            'workspace_id' => $wsSharedDesk->id,
            'booking_date' => now()->subDays(7)->toDateString(),
            'start_time' => '13:00:00',
            'end_time' => '17:00:00',
            'duration_hours' => 4,
            'actual_check_in' => now()->subDays(7)->setTime(12, 58, 0),
            'actual_check_out' => now()->subDays(7)->setTime(17, 0, 0),
            'base_price' => 100000, // 25000 * 4
            'tax' => 10000,
            'total_amount' => 110000,
            'status' => 'completed',
        ]);
        Payment::create([
            'booking_id' => $b4->id,
            'user_id' => $user->id,
            'amount' => 100000,
            'tax' => 10000,
            'final_amount' => 110000,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'completed',
            'transaction_code' => 'BANK-TX-998877665',
            'paid_at' => now()->subDays(7)->setTime(12, 30, 0),
            'payment_gateway' => 'VietQR',
        ]);

        // --- Booking 5: Unpaid & Pending Payment (Chờ thanh toán - Có nút thanh toán lại) ---
        $b5 = Booking::create([
            'booking_code' => 'WS-PEND-5562',
            'user_id' => $user->id,
            'workspace_id' => $wsMeetingLarge->id,
            'booking_date' => Carbon::now()->addDays(5)->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'duration_hours' => 3,
            'base_price' => 600000, // 200000 * 3
            'tax' => 60000,
            'total_amount' => 660000,
            'status' => 'pending',
        ]);
        Payment::create([
            'booking_id' => $b5->id,
            'user_id' => $user->id,
            'amount' => 600000,
            'tax' => 60000,
            'final_amount' => 660000,
            'payment_method' => 'momo',
            'payment_status' => 'pending', // Chờ thanh toán
            'payment_gateway' => 'MoMo',
        ]);

        // --- Booking 6: Failed Payment (Thanh toán lỗi - Có nút thanh toán lại) ---
        $b6 = Booking::create([
            'booking_code' => 'WS-FAIL-2299',
            'user_id' => $user->id,
            'workspace_id' => $wsMeetingSmall->id,
            'booking_date' => Carbon::now()->addDays(3)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'duration_hours' => 2,
            'base_price' => 240000, // 120000 * 2
            'tax' => 24000,
            'total_amount' => 264000,
            'status' => 'pending',
        ]);
        Payment::create([
            'booking_id' => $b6->id,
            'user_id' => $user->id,
            'amount' => 240000,
            'tax' => 24000,
            'final_amount' => 264000,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'failed', // Thanh toán thất bại
            'payment_gateway' => 'VietQR',
        ]);
    }
}
