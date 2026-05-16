<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupPendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:cleanup-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động xóa các đơn đặt phòng (bookings) ở trạng thái pending quá 10 phút chưa thanh toán/xác nhận';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffTime = \Carbon\Carbon::now()->subMinutes(10);

        // Tìm các booking ở trạng thái pending và tạo trước cutoffTime
        $expiredBookings = \App\Models\Booking::where('status', 'pending')
            ->where('created_at', '<', $cutoffTime)
            ->get();

        $count = $expiredBookings->count();

        if ($count > 0) {
            foreach ($expiredBookings as $booking) {
                // Xóa payment liên quan trước (nếu có)
                \App\Models\Payment::where('booking_id', $booking->id)->delete();
                // Xóa booking (forceDelete để xóa vĩnh viễn khỏi DB)
                $booking->forceDelete();
            }

            $this->info("Đã dọn dẹp {$count} đơn đặt phòng quá hạn 10 phút.");
            \Illuminate\Support\Facades\Log::info("CleanupPendingBookings: Đã dọn dẹp {$count} đơn đặt phòng quá hạn 10 phút.");
        } else {
            $this->info('Không có đơn đặt phòng nào quá hạn cần dọn dẹp.');
        }
    }
}
