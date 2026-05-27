<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\RoomType;
use App\Models\Service;
use App\Models\User;
use App\Models\Workspace;
use App\Models\DiscountCode;
use Carbon\Carbon;
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

        $roomType = RoomType::where('name', $service->name)->first();

        $workspaces = collect();
        if ($roomType) {
            $workspaces = Workspace::query()
                ->with([
                    'images' => fn($query) => $query
                        ->orderByDesc('is_primary')
                        ->orderBy('display_order')
                        ->orderBy('id'),
                ])
                ->where('room_type_id', $roomType->id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        }

        $rooms = $workspaces
            ->filter(fn(Workspace $workspace) => !is_null($workspace->price_per_month) && $workspace->price_per_month > 0)
            ->map(function (Workspace $workspace) use ($service) {
                $primaryImage = $workspace->images->first();
                $imageUrl = $primaryImage
                    ? asset($primaryImage->image_url)
                    : ($service->hero_image ?? 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=600&auto=format&fit=crop');
                $pricePerMonth = (float) $workspace->price_per_month;

                return [
                    'id' => (string) $workspace->id,
                    'name' => $workspace->name,
                    'capacity' => $workspace->capacity . ' người',
                    'price_raw' => $pricePerMonth,
                    'price' => number_format($pricePerMonth, 0, ',', '.') . 'đ/tháng',
                    'image' => $imageUrl,
                ];
            })
            ->values()
            ->toArray();

        return view('booking.monthly', [
            'serviceType' => $type,
            'serviceInfo' => $service,
            'rooms' => $rooms,
        ]);
    }

    /**
     * Hiển thị trang Checkout cho đặt chỗ theo tháng.
     */
    public function monthlyCheckout(Request $request)
    {
        $roomId = $request->query('room_id');
        $startDate   = $request->query('start_date');
        $durationMonths = (int) $request->query('duration_months', 1);

        // Xác định trang danh sách trước để redirect khi có lỗi, tránh loop redirect()->back()
        $fallbackUrl = route('booking.index');
        if ($roomId) {
            $ws = Workspace::with('roomType')->find($roomId);
            if ($ws && $ws->roomType) {
                $srv = Service::where('name', $ws->roomType->name)->first();
                if ($srv) {
                    $fallbackUrl = route('booking.monthly', $srv->slug);
                }
            }
        }

        if (!$roomId || !$startDate) {
            return redirect($fallbackUrl)->with('error', 'Thiếu thông tin đặt chỗ.');
        }

        // Kiểm tra xem đã có booking pending nào trùng lặp chưa
        $existingBooking = \App\Models\Booking::where('user_id', auth()->id())
            ->where('workspace_id', $roomId)
            ->where('booking_date', Carbon::parse($startDate)->toDateString())
            ->where('status', 'pending')
            ->first();

        if ($existingBooking) {
            // Trả về trang thông báo giao dịch chưa hoàn tất đẹp mắt
            return view('booking.pending_warning', [
                'booking' => $existingBooking,
                'fallbackUrl' => $fallbackUrl
            ]);
        }

        $workspace = Workspace::query()
            ->with([
                'images' => fn($query) => $query
                    ->orderByDesc('is_primary')
                    ->orderBy('display_order')
                    ->orderBy('id'),
            ])
            ->where('id', $roomId)
            ->where('status', 'active')
            ->firstOrFail();

        $roomPrice = (float) ($workspace->price_per_month ?? 0);
        if ($roomPrice <= 0) {
            return redirect($fallbackUrl)->with('error', 'Không thể đặt gói tháng vì workspace chưa cấu hình giá theo tháng.');
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
            'name'      => $workspace->name,
            'price_raw' => $roomPrice,
            'image'     => ($workspace->images->first() ? asset($workspace->images->first()->image_url) : null),
            'capacity'  => $workspace->capacity . ' người',
        ];

        return view('booking.checkout_monthly', compact(
            'room',
            'startDate',
            'durationMonths',
            'subtotal',
            'discount',
            'discountPercent',
            'tax',
            'total'
        ));
    }

    /**
     * Xử lý đặt chỗ theo tháng: lưu DB và redirect sang VietQR.
     */
    public function processMonthlyBooking(Request $request)
    {
        $validated = $request->validate([
            'room_id'         => 'required|integer|exists:workspaces,id',
            'start_date'      => 'required|date',
            'duration_months' => 'required|integer|min:1',
            'payment_method'  => 'required|in:bank_transfer',
            'discount_code'   => 'nullable|string|exists:discount_codes,code',
        ]);

        $durationMonths  = (int) $validated['duration_months'];
        $workspace = Workspace::query()
            ->where('id', $validated['room_id'])
            ->where('status', 'active')
            ->firstOrFail();

        $roomPrice = (float) ($workspace->price_per_month ?? 0);
        if ($roomPrice <= 0) {
            return redirect()->back()->with('error', 'Workspace chưa cấu hình giá theo tháng.');
        }
        $discountRates   = [1 => 0, 3 => 0.05, 6 => 0.10, 12 => 0.15];
        $discountRate    = $discountRates[$durationMonths] ?? 0;

        $subtotal        = $roomPrice * $durationMonths;
        $durationDiscount = $subtotal * $discountRate;
        $baseForManualDiscount = $subtotal - $durationDiscount;

        // Xử lý mã giảm giá thủ công
        $manualDiscountAmount = 0;
        $discountId = null;

        if (!empty($validated['discount_code'])) {
            $discount = DiscountCode::where('code', $validated['discount_code'])->first();
            if ($discount && $discount->status === 'active') {
                $now = now();
                $validDates = (!$discount->valid_from || $now->gte($discount->valid_from)) &&
                             (!$discount->valid_until || $now->lte($discount->valid_until));
                $validUsage = $discount->usage_limit === null || $discount->usage_count < $discount->usage_limit;
                $validMinAmount = $subtotal >= $discount->min_booking_amount;
                $validWorkspace = empty($discount->applicable_workspaces) || in_array($workspace->id, $discount->applicable_workspaces);

                if ($validDates && $validUsage && $validMinAmount && $validWorkspace) {
                    $discountId = $discount->id;
                    if ($discount->discount_type === 'percentage') {
                        $manualDiscountAmount = $baseForManualDiscount * ($discount->discount_value / 100);
                        if ($discount->max_discount !== null && $discount->max_discount > 0) {
                            $manualDiscountAmount = min($manualDiscountAmount, $discount->max_discount);
                        }
                    } else {
                        $manualDiscountAmount = min($discount->discount_value, $baseForManualDiscount);
                    }
                    $manualDiscountAmount = round($manualDiscountAmount);
                }
            }
        }

        $totalDiscount = $durationDiscount + $manualDiscountAmount;
        $afterDiscount = $subtotal - $totalDiscount;
        $tax           = $afterDiscount * 0.08;
        $totalAmount   = $afterDiscount + $tax;

        $startDate   = $validated['start_date'];
        $endDate     = Carbon::parse($startDate)->addMonths($durationMonths)->toDateString();
        $bookingCode = 'BK' . time() . rand(100, 999);
        $userId = auth()->id();

        $booking = \App\Models\Booking::create([
            'booking_code'   => $bookingCode,
            'user_id'        => $userId,
            'workspace_id'   => $workspace->id,
            'booking_date'   => $startDate,
            'start_time'     => '08:00:00',
            'end_time'       => '18:00:00',
            'duration_hours' => $durationMonths * 30 * 8,
            'base_price'     => $subtotal,
            'tax'            => $tax,
            'id_discount'    => $discountId,
            'total_amount'   => $totalAmount,
            'status'         => 'pending',
            'notes'          => 'Đặt tháng | Workspace: ' . ($workspace->code ?? $workspace->id) . ' | Tháng: ' . $durationMonths . ' | Kết thúc: ' . $endDate,
        ]);

        \App\Models\Payment::create([
            'booking_id'     => $booking->id,
            'user_id'        => $userId,
            'amount'         => $subtotal,
            'discount'       => $totalDiscount,
            'tax'            => $tax,
            'final_amount'   => $totalAmount,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'pending',
        ]);

        return redirect()->route('payment.vietqr', ['booking_code' => $bookingCode]);
    }

    public function hourly($type)
    {
        $service = Service::where('slug', $type)
            ->where('booking_type', 'hourly')
            ->where('is_active', true)
            ->firstOrFail();

        $roomType = RoomType::where('name', $service->name)->first();

        $workspaces = collect();
        if ($roomType) {
            $workspaces = Workspace::query()
                ->with([
                    'images' => fn($query) => $query
                        ->orderByDesc('is_primary')
                        ->orderBy('display_order')
                        ->orderBy('id'),
                ])
                ->where('room_type_id', $roomType->id)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        }

        $rooms = $workspaces
            ->map(function (Workspace $workspace) use ($service) {
                $primaryImage = $workspace->images->first();
                $imageUrl = $primaryImage
                    ? asset($primaryImage->image_url)
                    : ($service->hero_image ?? 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=600&auto=format&fit=crop');

                return [
                    'id' => (string) $workspace->id,
                    'name' => $workspace->name,
                    'capacity' => $workspace->capacity . ' người',
                    'price' => (int) $workspace->price_per_hour,
                    'image' => $imageUrl,
                ];
            })
            ->values()
            ->toArray();

        $workspaceIds = $workspaces->pluck('id');
        $confirmedBookings = [];
        if ($workspaceIds->isNotEmpty()) {
            $confirmedBookings = Booking::query()
                ->whereIn('workspace_id', $workspaceIds)
                ->where('status', '!=', 'cancelled')
                ->whereDate('booking_date', '>=', Carbon::today()->toDateString())
                ->get(['workspace_id', 'booking_date', 'start_time', 'end_time', 'status'])
                ->map(fn(Booking $booking) => [
                    'room_id' => (string) $booking->workspace_id,
                    'date' => Carbon::parse($booking->booking_date)->toDateString(),
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'status' => $booking->status,
                ])
                ->values()
                ->toArray();
        }

        return view('booking.hourly', [
            'serviceType' => $type,
            'serviceInfo' => $service,
            'rooms' => $rooms,
            'confirmedBookings' => $confirmedBookings
        ]);
    }
    public function checkout(Request $request)
    {
        $roomId = $request->query('room_id');
        $date = $request->query('date');
        $startTime = $request->query('start_time');
        $endTime = $request->query('end_time');

        // Xác định trang danh sách trước để redirect khi có lỗi, tránh loop redirect()->back()
        $fallbackUrl = route('booking.index');
        if ($roomId) {
            $ws = Workspace::with('roomType')->find($roomId);
            if ($ws && $ws->roomType) {
                $srv = Service::where('name', $ws->roomType->name)->first();
                if ($srv) {
                    $fallbackUrl = route('booking.hourly', $srv->slug);
                }
            }
        }

        if (!$roomId || !$date || !$startTime || !$endTime) {
            return redirect($fallbackUrl)->with('error', 'Thiếu thông tin đặt phòng.');
        }

        $workspace = Workspace::query()
            ->with([
                'images' => fn($query) => $query
                    ->orderByDesc('is_primary')
                    ->orderBy('display_order')
                    ->orderBy('id'),
            ])
            ->where('id', $roomId)
            ->where('status', 'active')
            ->firstOrFail();

        $bookingDate = Carbon::parse($date)->toDateString();
        if (Carbon::parse($bookingDate)->lessThan(Carbon::today())) {
            return redirect($fallbackUrl)->with('error', 'Ngày đặt không hợp lệ.');
        }

        // Tính thời lượng
        $start = Carbon::parse($bookingDate . ' ' . $startTime);
        $end = Carbon::parse($bookingDate . ' ' . $endTime);

        if ($end->lessThanOrEqualTo($start)) {
            return redirect($fallbackUrl)->with('error', 'Thời gian không hợp lệ.');
        }

        $duration = $start->diffInMinutes($end) / 60;

        if ($duration < (int) $workspace->min_booking_hours) {
            return redirect($fallbackUrl)->with('error', 'Thời lượng đặt tối thiểu là ' . $workspace->min_booking_hours . ' giờ.');
        }

        $existingBooking = Booking::query()
            ->where('workspace_id', $workspace->id)
            ->where('status', '!=', 'cancelled')
            ->whereDate('booking_date', $bookingDate)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->first();

        if ($existingBooking) {
            if ($existingBooking->user_id === auth()->id() && $existingBooking->status === 'pending') {
                // Trả về trang thông báo giao dịch chưa hoàn tất đẹp mắt
                return view('booking.pending_warning', [
                    'booking' => $existingBooking,
                    'fallbackUrl' => $fallbackUrl
                ]);
            }
            return redirect($fallbackUrl)->with('error', 'Khung giờ bạn chọn đã có người đặt. Vui lòng chọn khung giờ khác.');
        }

        $roomPrice = (float) $workspace->price_per_hour;
        $subtotal = $duration * $roomPrice;
        $tax = $subtotal * 0.08; // Thuế 8%
        $total = $subtotal + $tax;

        $primaryImage = $workspace->images->first();
        $roomImage = $primaryImage ? asset($primaryImage->image_url) : null;

        $room = [
            'id' => $roomId,
            'name' => $workspace->name,
            'price' => $roomPrice,
            'image' => $roomImage,
            'capacity' => $workspace->capacity . ' người',
        ];

        return view('booking.checkout_hourly', compact(
            'room',
            'roomId',
            'date',
            'startTime',
            'endTime',
            'duration',
            'subtotal',
            'tax',
            'total'
        ));
    }

    /**
     * Xử lý yêu cầu đặt phòng theo giờ – luôn dùng VietQR.
     */
    public function processBooking(Request $request)
    {
        $validated = $request->validate([
            'room_id'        => 'required|integer|exists:workspaces,id',
            'date'           => 'required|date',
            'start_time'     => 'required',
            'end_time'       => 'required',
            'payment_method' => 'required|in:bank_transfer',
            'discount_code'  => 'nullable|string|exists:discount_codes,code',
        ]);

        $workspace = Workspace::query()
            ->where('id', $validated['room_id'])
            ->where('status', 'active')
            ->firstOrFail();

        $bookingDate = Carbon::parse($validated['date'])->toDateString();
        if (Carbon::parse($bookingDate)->lessThan(Carbon::today())) {
            return redirect()->back()->with('error', 'Ngày đặt không hợp lệ.');
        }

        $start    = Carbon::parse($bookingDate . ' ' . $validated['start_time']);
        $end      = Carbon::parse($bookingDate . ' ' . $validated['end_time']);
        $duration = $start->diffInMinutes($end) / 60;

        if ($end->lessThanOrEqualTo($start)) {
            return redirect()->back()->with('error', 'Thời gian không hợp lệ.');
        }

        if ($duration < (int) $workspace->min_booking_hours) {
            return redirect()->back()->with('error', 'Thời lượng đặt tối thiểu là ' . $workspace->min_booking_hours . ' giờ.');
        }

        $existingBooking = Booking::query()
            ->where('workspace_id', $workspace->id)
            ->where('status', '!=', 'cancelled')
            ->whereDate('booking_date', $bookingDate)
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->first(); // Thay đổi từ exists() sang first() để lấy data kiểm tra

        if ($existingBooking) {
            // Cứu cánh cho tình huống Double-Click: 
            // Nếu chính user này vừa tạo ra booking trùng giờ đó và nó đang pending, 
            // thì coi như họ bấm nhầm 2 lần, đưa thẳng sang trang thanh toán luôn.
            if ($existingBooking->user_id === auth()->id() && $existingBooking->status === 'pending') {
                return redirect()->route('payment.vietqr', ['booking_code' => $existingBooking->booking_code]);
            }

            // Nếu thực sự là người khác đặt
            return redirect()->back()->with('error', 'Khung giờ bạn chọn đã có người đặt. Vui lòng chọn khung giờ khác.');
        }

        $basePrice   = ((float) $workspace->price_per_hour) * $duration;

        // Xử lý mã giảm giá
        $discountAmount = 0;
        $discountId = null;

        if (!empty($validated['discount_code'])) {
            $discount = DiscountCode::where('code', $validated['discount_code'])->first();
            if ($discount && $discount->status === 'active') {
                $now = now();
                $validDates = (!$discount->valid_from || $now->gte($discount->valid_from)) &&
                             (!$discount->valid_until || $now->lte($discount->valid_until));
                $validUsage = $discount->usage_limit === null || $discount->usage_count < $discount->usage_limit;
                $validMinAmount = $basePrice >= $discount->min_booking_amount;
                $validWorkspace = empty($discount->applicable_workspaces) || in_array($workspace->id, $discount->applicable_workspaces);

                if ($validDates && $validUsage && $validMinAmount && $validWorkspace) {
                    $discountId = $discount->id;
                    if ($discount->discount_type === 'percentage') {
                        $discountAmount = $basePrice * ($discount->discount_value / 100);
                        if ($discount->max_discount !== null && $discount->max_discount > 0) {
                            $discountAmount = min($discountAmount, $discount->max_discount);
                        }
                    } else {
                        $discountAmount = min($discount->discount_value, $basePrice);
                    }
                    $discountAmount = round($discountAmount);
                }
            }
        }

        $afterDiscount = $basePrice - $discountAmount;
        $tax           = $afterDiscount * 0.08;
        $totalAmount   = $afterDiscount + $tax;

        $bookingCode = 'BK' . time() . rand(100, 999);
        $userId = auth()->id();

        $booking = \App\Models\Booking::create([
            'booking_code'  => $bookingCode,
            'user_id'       => $userId,
            'workspace_id'  => $workspace->id,
            'booking_date'  => $bookingDate,
            'start_time'    => $validated['start_time'],
            'end_time'      => $validated['end_time'],
            'duration_hours' => $duration,
            'base_price'    => $basePrice,
            'tax'           => $tax,
            'id_discount'   => $discountId,
            'total_amount'  => $totalAmount,
            'status'        => 'pending',
            'notes'         => 'Workspace: ' . ($workspace->code ?? $workspace->id),
        ]);

        \App\Models\Payment::create([
            'booking_id'    => $booking->id,
            'user_id'       => $userId,
            'amount'        => $basePrice,
            'discount'      => $discountAmount,
            'tax'           => $tax,
            'final_amount'  => $totalAmount,
            'payment_method' => 'bank_transfer',
            'payment_status' => 'pending',
        ]);

        return redirect()->route('payment.vietqr', ['booking_code' => $bookingCode]);
    }

    /**
     * Áp dụng mã giảm giá qua AJAX.
     */
    public function applyDiscount(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string',
                'workspace_id' => 'required|integer|exists:workspaces,id',
                'subtotal' => 'required|numeric|min:0',
            ]);

            $code = $validated['code'];
            $workspaceId = $validated['workspace_id'];
            $subtotal = (float) $validated['subtotal'];

            $discount = DiscountCode::where('code', $code)->first();

            if (!$discount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không tồn tại.'
                ]);
            }

            if ($discount->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không còn hoạt động.'
                ]);
            }

            $now = now();
            if ($discount->valid_from && $now->lt($discount->valid_from)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá chưa đến thời gian áp dụng.'
                ]);
            }

            if ($discount->valid_until && $now->gt($discount->valid_until)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá đã hết hạn sử dụng.'
                ]);
            }

            if ($discount->usage_limit !== null && $discount->usage_count >= $discount->usage_limit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá đã hết lượt sử dụng.'
                ]);
            }

            if ($subtotal < $discount->min_booking_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($discount->min_booking_amount) . 'đ để sử dụng mã này.'
                ]);
            }

            if (!empty($discount->applicable_workspaces)) {
                if (!in_array($workspaceId, $discount->applicable_workspaces)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã giảm giá không áp dụng cho không gian này.'
                    ]);
                }
            }

            // Tính số tiền giảm
            $discountAmount = 0;
            if ($discount->discount_type === 'percentage') {
                $discountAmount = $subtotal * ($discount->discount_value / 100);
                if ($discount->max_discount !== null && $discount->max_discount > 0) {
                    $discountAmount = min($discountAmount, $discount->max_discount);
                }
            } else {
                $discountAmount = min($discount->discount_value, $subtotal);
            }

            $discountAmount = round($discountAmount);

            return response()->json([
                'success' => true,
                'code' => $discount->code,
                'discount_amount' => $discountAmount,
                'message' => 'Áp dụng mã giảm giá thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi kiểm tra mã giảm giá: ' . $e->getMessage()
            ]);
        }
    }
}
