<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
    }
}
