<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('refund_receiver_name')->nullable()->after('refund_amount');
            $table->string('refund_bank_name', 120)->nullable()->after('refund_receiver_name');
            $table->string('refund_bank_account_number', 50)->nullable()->after('refund_bank_name');
            $table->string('cancellation_reason_code', 60)->nullable()->after('refund_bank_account_number');
            $table->text('cancellation_reason_detail')->nullable()->after('cancellation_reason_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'refund_receiver_name',
                'refund_bank_name',
                'refund_bank_account_number',
                'cancellation_reason_code',
                'cancellation_reason_detail',
            ]);
        });
    }
};
