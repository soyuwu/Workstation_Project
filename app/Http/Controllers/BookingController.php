<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    // Cấu hình mock data chung
    private $services = [
        'cho-ngoi-linh-hoat' => [
            'name' => 'Chỗ ngồi linh hoạt',
            'type' => 'hourly',
            'icon' => 'event_seat',
            'desc' => 'Tự do chọn chỗ, thanh toán theo giờ hoặc ngày. Không cần đặt cọc.',
        ],
        'cho-ngoi-co-dinh' => [
            'name' => 'Chỗ ngồi cố định',
            'type' => 'monthly',
            'icon' => 'chair',
            'desc' => 'Bàn làm việc riêng, vị trí cố định – không gian quen thuộc mỗi ngày.',
        ],
        'phong-lam-viec-rieng' => [
            'name' => 'Phòng làm việc riêng',
            'type' => 'monthly',
            'icon' => 'corporate_fare',
            'desc' => 'Văn phòng khép kín, riêng tư tuyệt đối cho team 2-10 người.',
        ],
        'phong-hop-tieu-chuan' => [
            'name' => 'Phòng họp tiêu chuẩn',
            'type' => 'hourly',
            'icon' => 'meeting_room',
            'desc' => 'Phòng họp chuyên nghiệp, sức chứa 4-12 người, đầy đủ tiện nghi.',
        ],
        'khong-gian-su-kien' => [
            'name' => 'Không gian sự kiện',
            'type' => 'hourly',
            'icon' => 'celebration',
            'desc' => 'Không gian linh hoạt, sức chứa 20-100 người, phù hợp mọi sự kiện.',
        ],
    ];

    public function index()
    {
        return view('booking.index', ['services' => $this->services]);
    }

    public function monthly($type)
    {
        if (!isset($this->services[$type]) || $this->services[$type]['type'] !== 'monthly') {
            abort(404);
        }

        // Mock data cho 3 không gian mẫu
        $mockRooms = [
            [
                'id' => 1,
                'name' => 'Không gian ' . $this->services[$type]['name'] . ' A',
                'capacity' => '1 người',
                'price' => '2.500.000đ/tháng',
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=600&auto=format&fit=crop'
            ],
            [
                'id' => 2,
                'name' => 'Không gian ' . $this->services[$type]['name'] . ' B',
                'capacity' => '2 người',
                'price' => '4.800.000đ/tháng',
                'image' => 'https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?q=80&w=600&auto=format&fit=crop'
            ],
            [
                'id' => 3,
                'name' => 'Không gian ' . $this->services[$type]['name'] . ' C',
                'capacity' => '4 người',
                'price' => '8.500.000đ/tháng',
                'image' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?q=80&w=600&auto=format&fit=crop'
            ],
        ];

        return view('booking.monthly', [
            'serviceType' => $type,
            'serviceInfo' => $this->services[$type],
            'rooms' => $mockRooms
        ]);
    }

    public function hourly($type)
    {
        if (!isset($this->services[$type]) || $this->services[$type]['type'] !== 'hourly') {
            abort(404);
        }

        // Mock data cho 5 phòng/không gian mẫu
        $mockRooms = [
            [
                'id' => 'R1',
                'name' => $this->services[$type]['name'] . ' 101',
                'capacity' => '2-4 người',
                'price' => 150000,
                'image' => 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?q=80&w=300&auto=format&fit=crop'
            ],
            [
                'id' => 'R2',
                'name' => $this->services[$type]['name'] . ' 102',
                'capacity' => '4-8 người',
                'price' => 250000,
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=300&auto=format&fit=crop'
            ],
            [
                'id' => 'R3',
                'name' => $this->services[$type]['name'] . ' 201',
                'capacity' => '8-12 người',
                'price' => 350000,
                'image' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?q=80&w=300&auto=format&fit=crop'
            ],
            [
                'id' => 'R4',
                'name' => $this->services[$type]['name'] . ' 202',
                'capacity' => '10-20 người',
                'price' => 500000,
                'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=300&auto=format&fit=crop'
            ],
            [
                'id' => 'R5',
                'name' => $this->services[$type]['name'] . ' 301 (VIP)',
                'capacity' => '20+ người',
                'price' => 800000,
                'image' => 'https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?q=80&w=300&auto=format&fit=crop'
            ],
        ];

        return view('booking.hourly', [
            'serviceType' => $type,
            'serviceInfo' => $this->services[$type],
            'rooms' => $mockRooms
        ]);
    }
    public function checkout(Request $request)
    {
        $roomId = $request->query('room_id');
        $date = $request->query('date');
        $startTime = $request->query('start_time');
        $endTime = $request->query('end_time');
        $roomPrice = $request->query('room_price', 0);
        $roomName = $request->query('room_name', 'Phòng không xác định');
        $roomImage = $request->query('room_image', null);
        $roomCapacity = $request->query('room_capacity', 'N/A');

        if (!$roomId || !$date || !$startTime || !$endTime) {
            return redirect()->back()->with('error', 'Thiếu thông tin đặt phòng.');
        }

        // Tính thời lượng
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);
        
        if ($end->lessThanOrEqualTo($start)) {
            return redirect()->back()->with('error', 'Thời gian không hợp lệ.');
        }
        
        $duration = $start->diffInMinutes($end) / 60;

        $subtotal = $duration * $roomPrice;
        $tax = $subtotal * 0.08; // Thuế 8%
        $total = $subtotal + $tax;

        $room = [
            'id' => $roomId,
            'name' => $roomName,
            'price' => $roomPrice,
            'image' => $roomImage,
            'capacity' => $roomCapacity
        ];

        return view('booking.checkout', compact(
            'room', 'roomId', 'date', 'startTime', 'endTime', 'duration', 'subtotal', 'tax', 'total'
        ));
    }
}
