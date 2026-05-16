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

        // Mock data cho 3 không gian mẫu (price_raw dùng để tính toán, price dùng để hiển thị)
        $mockRooms = [
            [
                'id' => 'M1',
                'name' => 'Không gian ' . $this->services[$type]['name'] . ' A',
                'capacity' => '1 người',
                'price_raw' => 2500000,
                'price' => '2.500.000đ/tháng',
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=600&auto=format&fit=crop'
            ],
            [
                'id' => 'M2',
                'name' => 'Không gian ' . $this->services[$type]['name'] . ' B',
                'capacity' => '2 người',
                'price_raw' => 4800000,
                'price' => '4.800.000đ/tháng',
                'image' => 'https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?q=80&w=600&auto=format&fit=crop'
            ],
            [
                'id' => 'M3',
                'name' => 'Không gian ' . $this->services[$type]['name'] . ' C',
                'capacity' => '4 người',
                'price_raw' => 8500000,
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

    /**
     * Hiển thị trang Checkout cho đặt chỗ theo tháng.
     */
    public function monthlyCheckout(Request $request)
    {
        $roomId      = $request->query('room_id');
        $roomPrice   = (float) $request->query('room_price', 0);
        $roomName    = $request->query('room_name', 'Không gian không xác định');
        $roomImage   = $request->query('room_image', null);
        $roomCap     = $request->query('room_capacity', 'N/A');
        $startDate   = $request->query('start_date');
        $durationMonths = (int) $request->query('duration_months', 1);

        if (!$roomId || !$startDate) {
            return redirect()->back()->with('error', 'Thiếu thông tin đặt chỗ.');
        }

        // Bảng chiết khấu theo số tháng
        $discountRates = [1 => 0, 3 => 0.05, 6 => 0.10, 12 => 0.15];
        $discountRate  = $discountRates[$durationMonths] ?? 0;
        $discountPercent = $discountRate * 100;

        $subtotal  = $roomPrice * $durationMonths;
        $discount  = $subtotal * $discountRate;
        $afterDiscount = $subtotal - $discount;
        $tax       = $afterDiscount * 0.08;
        $total     = $afterDiscount + $tax;

        $room = [
            'id'        => $roomId,
            'name'      => $roomName,
            'price_raw' => $roomPrice,
            'image'     => $roomImage,
            'capacity'  => $roomCap,
        ];

        return view('booking.checkout_monthly', compact(
            'room', 'startDate', 'durationMonths', 'subtotal', 'discount', 'discountPercent', 'tax', 'total'
        ));
    }

    /**
     * Xử lý đặt chỗ theo tháng: lưu DB và redirect sang VietQR.
     */
    public function processMonthlyBooking(Request $request)
    {
        $validated = $request->validate([
            'room_id'         => 'required',
            'room_price'      => 'required|numeric',
            'start_date'      => 'required|date',
            'duration_months' => 'required|integer|min:1',
            'payment_method'  => 'required|in:bank_transfer',
        ]);

        $durationMonths  = (int) $validated['duration_months'];
        $roomPrice       = (float) $validated['room_price'];
        $discountRates   = [1 => 0, 3 => 0.05, 6 => 0.10, 12 => 0.15];
        $discountRate    = $discountRates[$durationMonths] ?? 0;

        $subtotal      = $roomPrice * $durationMonths;
        $discount      = $subtotal * $discountRate;
        $afterDiscount = $subtotal - $discount;
        $tax           = $afterDiscount * 0.08;
        $totalAmount   = $afterDiscount + $tax;

        $startDate   = $validated['start_date'];
        $endDate     = \Carbon\Carbon::parse($startDate)->addMonths($durationMonths)->toDateString();
        $bookingCode = 'BK' . time() . rand(100, 999);

        $booking = \App\Models\Booking::create([
            'booking_code'   => $bookingCode,
            'user_id'        => null,
            'workspace_id'   => null,
            'booking_date'   => $startDate,
            'start_time'     => '08:00:00',
            'end_time'       => '18:00:00',
            'duration_hours' => $durationMonths * 30 * 8,
            'base_price'     => $subtotal,
            'tax'            => $tax,
            'total_amount'   => $totalAmount,
            'status'         => 'pending',
            'notes'          => 'Đặt tháng | Room ID: ' . $validated['room_id'] . ' | Tháng: ' . $durationMonths . ' | Kết thúc: ' . $endDate,
        ]);

        \App\Models\Payment::create([
            'booking_id'     => $booking->id,
            'user_id'        => null,
            'amount'         => $subtotal,
            'discount'       => $discount,
            'tax'            => $tax,
            'final_amount'   => $totalAmount,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'pending',
        ]);

        return redirect()->route('payment.vietqr', ['booking_code' => $bookingCode]);
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

        return view('booking.checkout_hourly', compact(
            'room', 'roomId', 'date', 'startTime', 'endTime', 'duration', 'subtotal', 'tax', 'total'
        ));
    }

    /**
     * Xử lý yêu cầu đặt phòng theo giờ – luôn dùng VietQR.
     */
    public function processBooking(Request $request)
    {
        $validated = $request->validate([
            'room_id'        => 'required',
            'room_price'     => 'required|numeric',
            'date'           => 'required|date',
            'start_time'     => 'required',
            'end_time'       => 'required',
            'payment_method' => 'required|in:bank_transfer',
        ]);

        $start    = \Carbon\Carbon::parse($validated['start_time']);
        $end      = \Carbon\Carbon::parse($validated['end_time']);
        $duration = $start->diffInMinutes($end) / 60;

        $basePrice   = $validated['room_price'] * $duration;
        $tax         = $basePrice * 0.08;
        $totalAmount = $basePrice + $tax;

        $bookingCode = 'BK' . time() . rand(100, 999);

        $booking = \App\Models\Booking::create([
            'booking_code'  => $bookingCode,
            'user_id'       => null,
            'workspace_id'  => null,
            'booking_date'  => $validated['date'],
            'start_time'    => $validated['start_time'],
            'end_time'      => $validated['end_time'],
            'duration_hours'=> $duration,
            'base_price'    => $basePrice,
            'tax'           => $tax,
            'total_amount'  => $totalAmount,
            'status'        => 'pending',
            'notes'         => 'Room ID đặt: ' . $validated['room_id'],
        ]);

        \App\Models\Payment::create([
            'booking_id'    => $booking->id,
            'user_id'       => null,
            'amount'        => $basePrice,
            'tax'           => $tax,
            'final_amount'  => $totalAmount,
            'payment_method'=> 'bank_transfer',
            'payment_status'=> 'pending',
        ]);

        return redirect()->route('payment.vietqr', ['booking_code' => $bookingCode]);
    }
}
