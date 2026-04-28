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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_hours');
            $table->timestamp('actual_check_in')->nullable();
            $table->timestamp('actual_check_out')->nullable();
            $table->decimal('base_price', 15, 2);
            $table->decimal('surcharge', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->foreignId('id_discount')->nullable()->constrained('discount_codes')->onDelete('set null');
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->string('qr_code')->nullable();
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
