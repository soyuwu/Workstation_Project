<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'phamgiann2006@gmail.com'],
            [
                'name' => 'Admin System',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('1234'),
                'email_verified_at' => now(),
            ]
        );
        $this->call([
            RoomTypeSeeder::class,
            WorkspaceSeeder::class,
        ]);

        $this->call([
            ServiceSeeder::class,
            ReviewSeeder::class,
        ]);

        // Tạo mã giảm giá mặc định để test
        \App\Models\DiscountCode::updateOrCreate(
            ['code' => 'TEST10'],
            [
                'description' => 'Giảm 10% tối đa 50k',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'max_discount' => 50000.00,
                'usage_limit' => 10,
                'usage_count' => 0,
                'valid_from' => now()->subDay(),
                'valid_until' => now()->addDays(7),
                'min_booking_amount' => 100000.00,
                'status' => 'active',
            ]
        );

        \App\Models\DiscountCode::updateOrCreate(
            ['code' => 'FIXED50'],
            [
                'description' => 'Giảm 50k cố định',
                'discount_type' => 'fixed',
                'discount_value' => 50000.00,
                'usage_limit' => 5,
                'usage_count' => 0,
                'valid_from' => now()->subDay(),
                'valid_until' => now()->addDays(7),
                'min_booking_amount' => 100000.00,
                'status' => 'active',
            ]
        );
    }
}
