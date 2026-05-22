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
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('reviews', 'booking_id')) {
                $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            }
            if (!Schema::hasColumn('reviews', 'workspace_id')) {
                $table->foreignId('workspace_id')->nullable()->constrained('workspaces')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['booking_id']);
            $table->dropForeign(['workspace_id']);
            $table->dropColumn(['user_id', 'booking_id', 'workspace_id']);
        });
    }
};
