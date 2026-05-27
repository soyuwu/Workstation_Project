<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('booking_id')->nullable()->unique()->after('id')->constrained('bookings')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->after('booking_id')->constrained('users')->nullOnDelete();
            $table->foreignId('workspace_id')->nullable()->after('user_id')->constrained('workspaces')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['booking_id']);
            $table->dropUnique(['booking_id']);
            $table->dropColumn(['workspace_id', 'user_id', 'booking_id']);
        });
    }
};
