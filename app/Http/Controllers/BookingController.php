<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class BookingController extends Controller
{
        public function index()
        {
            $services = Service::active()->ordered()->get();

            return view('booking.index', compact('services'));
        }

    public function monthly($type)
    {
        $service = Service::where('slug', $type)
            ->where('booking_type', 'monthly')
            ->where('is_active', true)
            ->firstOrFail();

        // Mock data cho 3 không gian mẫu
        $mockRooms = [
            [
                'id' => 1,
                'name' => 'Không gian ' . $service->name . ' A',
                'capacity' => '1 người',
                'price' => '2.500.000đ/tháng',
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=600&auto=format&fit=crop'
            ],
            [
                'id' => 2,
                'name' => 'Không gian ' . $service->name . ' B',
                'capacity' => '2 người',
                'price' => '4.800.000đ/tháng',
                'image' => 'https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?q=80&w=600&auto=format&fit=crop'
            ],
            [
                'id' => 3,
                'name' => 'Không gian ' . $service->name . ' C',
                'capacity' => '4 người',
                'price' => '8.500.000đ/tháng',
                'image' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?q=80&w=600&auto=format&fit=crop'
            ],
        ];

        return view('booking.monthly', [
            'serviceType' => $type,
            'serviceInfo' => $service,
            'rooms' => $mockRooms
        ]);
    }

    public function hourly($type)
    {
        $service = Service::where('slug', $type)
            ->where('booking_type', 'hourly')
            ->where('is_active', true)
            ->firstOrFail();

        // Mock data cho 5 phòng/không gian mẫu
        $mockRooms = [
            [
                'id' => 'R1',
                'name' => $service->name . ' 101',
                'capacity' => '2-4 người',
                'price' => 150000,
                'image' => 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?q=80&w=300&auto=format&fit=crop'
            ],
            [
                'id' => 'R2',
                'name' => $service->name . ' 102',
                'capacity' => '4-8 người',
                'price' => 250000,
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=300&auto=format&fit=crop'
            ],
            [
                'id' => 'R3',
                'name' => $service->name . ' 201',
                'capacity' => '8-12 người',
                'price' => 350000,
                'image' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?q=80&w=300&auto=format&fit=crop'
            ],
            [
                'id' => 'R4',
                'name' => $service->name . ' 202',
                'capacity' => '10-20 người',
                'price' => 500000,
                'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=300&auto=format&fit=crop'
            ],
            [
                'id' => 'R5',
                'name' => $service->name . ' 301 (VIP)',
                'capacity' => '20+ người',
                'price' => 800000,
                'image' => 'https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?q=80&w=300&auto=format&fit=crop'
            ],
        ];

        return view('booking.hourly', [
            'serviceType' => $type,
            'serviceInfo' => $service,
            'rooms' => $mockRooms
        ]);
    }
}
