<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\RoomType;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class WorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        $defaultArea = Area::firstOrCreate(['name' => 'Tầng 1'], ['floor_number' => 1]);

        $roomTypes = RoomType::query()->pluck('id', 'name');

        $seedWorkspaces = [
            // Hourly
            ['code' => 'HD-01', 'name' => 'Hot Desk 01', 'type' => 'Chỗ ngồi linh hoạt', 'capacity' => 1, 'price_per_hour' => 50000, 'min_booking_hours' => 1],
            ['code' => 'HD-02', 'name' => 'Hot Desk 02', 'type' => 'Chỗ ngồi linh hoạt', 'capacity' => 1, 'price_per_hour' => 50000, 'min_booking_hours' => 1],
            ['code' => 'MR-01', 'name' => 'Meeting Room 01', 'type' => 'Phòng họp tiêu chuẩn', 'capacity' => 8, 'price_per_hour' => 250000, 'min_booking_hours' => 1],
            ['code' => 'EV-01', 'name' => 'Event Space 01', 'type' => 'Không gian sự kiện', 'capacity' => 50, 'price_per_hour' => 500000, 'min_booking_hours' => 2],

            // Monthly
            ['code' => 'DD-01', 'name' => 'Dedicated Desk 01', 'type' => 'Chỗ ngồi cố định', 'capacity' => 1, 'price_per_hour' => 0, 'price_per_month' => 2500000, 'min_booking_hours' => 1],
            ['code' => 'PR-01', 'name' => 'Private Office 01', 'type' => 'Phòng làm việc riêng', 'capacity' => 4, 'price_per_hour' => 0, 'price_per_month' => 8500000, 'min_booking_hours' => 1],
        ];

        foreach ($seedWorkspaces as $ws) {
            $roomTypeId = $roomTypes[$ws['type']] ?? null;
            if (!$roomTypeId) {
                continue;
            }

            Workspace::firstOrCreate(
                ['code' => $ws['code']],
                [
                    'area_id' => $defaultArea->id,
                    'room_type_id' => $roomTypeId,
                    'name' => $ws['name'],
                    'capacity' => $ws['capacity'],
                    'description' => 'Seed workspace',
                    'amenities' => [],
                    'price_per_hour' => $ws['price_per_hour'],
                    'price_per_month' => $ws['price_per_month'] ?? null,
                    'min_booking_hours' => $ws['min_booking_hours'],
                    'status' => 'active',
                ]
            );
        }
    }
}

