<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Chỗ ngồi linh hoạt',
            'Chỗ ngồi cố định',
            'Phòng làm việc riêng',
            'Phòng họp tiêu chuẩn',
            'Không gian sự kiện'
        ];

        foreach ($types as $type) {
            \App\Models\RoomType::firstOrCreate(['name' => $type]);
        }
        
        // Tạo thêm 1 khu vực mặc định để tránh lỗi khi thêm phòng
        \App\Models\Area::firstOrCreate(['name' => 'Tầng 1'], ['floor_number' => 1]);
    }
}
