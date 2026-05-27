<?php

namespace App\Console\Commands;

use App\Services\BookingLifecycleService;
use Illuminate\Console\Command;

class CleanupPendingBookings extends Command
{
    protected $signature = 'booking:cleanup-pending';

    protected $description = 'Update expired pending bookings and completed bookings.';

    public function handle(BookingLifecycleService $bookingLifecycle): int
    {
        $cancelledCount = $bookingLifecycle->cancelExpiredPendingBookings();
        $completedCount = $bookingLifecycle->markCompletedBookings();

        $this->info("Updated {$cancelledCount} expired pending bookings to cancelled.");
        $this->info("Updated {$completedCount} used bookings to completed.");

        return self::SUCCESS;
    }
}
