<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
            $table->decimal('cancel_fee_amount', 15, 2)->default(0)->after('cancelled_at');
            $table->decimal('refund_amount', 15, 2)->default(0)->after('cancel_fee_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['cancelled_at', 'cancel_fee_amount', 'refund_amount']);
        });
    }
};
