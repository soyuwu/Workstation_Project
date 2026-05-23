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

        // Tìm các booking ở trạng thái pending, chưa thanh toán (payment_status != completed) và tạo trước cutoffTime
        $expiredBookings = \App\Models\Booking::query()
            ->where('status', 'pending')
            ->where('created_at', '<', $cutoffTime)
            ->where(function ($query) {
                $query->whereDoesntHave('payment')
                    ->orWhereHas('payment', function ($paymentQuery) {
                        $paymentQuery->where('payment_status', '!=', 'completed')
                            ->where(function ($paymentQuery) {
                                $paymentQuery->where('payment_method', '!=', 'bank_transfer')
                                    ->orWhereNull('reported_at');
                            });
                    });
            })
            ->get();

        $count = $expiredBookings->count();

        if ($count > 0) {
            foreach ($expiredBookings as $booking) {
                // Xóa booking (forceDelete để xóa vĩnh viễn khỏi DB)
                $booking->forceDelete();
            }

            $this->info("Đã dọn dẹp {$count} đơn đặt phòng quá hạn 10 phút.");
            \Illuminate\Support\Facades\Log::info("CleanupPendingBookings: Đã dọn dẹp {$count} đơn đặt phòng quá hạn 10 phút.");
        } else {
            $this->info('Không có đơn đặt phòng pending nào quá hạn cần dọn dẹp.');
        }

        // =========================================================================
        // 2. DỌN DẸP CÁC ĐƠN HÀNG ĐÃ QUA THỜI GIAN SỬ DỤNG (HOÀN THÀNH DỊCH VỤ)
        // =========================================================================
        $today = \Carbon\Carbon::now()->toDateString();
        $currentTime = \Carbon\Carbon::now()->toTimeString();

        // Tìm các đơn hàng đang ở trạng thái 'confirmed' (đã duyệt)
        // mà ngày đặt phòng đã qua (< today) HOẶC (ngày đặt = hôm nay VÀ giờ kết thúc đã qua < currentTime)
        $passedBookings = \App\Models\Booking::where('status', 'confirmed')
            ->whereHas('payment', function ($paymentQuery) {
                $paymentQuery->where('payment_status', 'completed');
            })
            ->where(function ($query) use ($today, $currentTime) {
                $query->where('booking_date', '<', $today)
                      ->orWhere(function ($q) use ($today, $currentTime) {
                          $q->where('booking_date', $today)
                            ->where('end_time', '<', $currentTime);
                      });
            })
            ->get();

        $passedCount = $passedBookings->count();

        if ($passedCount > 0) {
            foreach ($passedBookings as $booking) {
                // Chuyển trạng thái sang 'completed' (đã hoàn thành dịch vụ)
                $booking->status = 'completed';
                $booking->save();

                // Cập nhật payment tương ứng thành completed nếu chưa cập nhật
                \App\Models\Payment::where('booking_id', $booking->id)
                    ->where('payment_status', '!=', 'completed')
                    ->update(['payment_status' => 'completed', 'paid_at' => \Carbon\Carbon::now()]);
            }

            $this->info("Đã chuyển {$passedCount} đơn đặt phòng đã qua thời gian sử dụng sang trạng thái 'completed'.");
            \Illuminate\Support\Facades\Log::info("CleanupPendingBookings: Đã chuyển {$passedCount} đơn đặt phòng đã qua thời gian sử dụng sang trạng thái 'completed'.");
        } else {
            $this->info('Không có đơn đặt phòng nào qua thời gian sử dụng cần cập nhật.');
        }
    }
}
