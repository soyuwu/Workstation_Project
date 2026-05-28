<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AdminController extends Controller
{
    public function tongquan()
    {
        // Doanh thu trong ngày (confirmed bookings)
        $todayRevenue = Booking::where('status', 'confirmed')
            ->whereDate('created_at', now()->toDateString())
            ->sum('total_amount');
            
        // Doanh thu trong tháng
        $monthRevenue = Booking::where('status', 'confirmed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
            
        // Đơn đặt phòng mới
        $newBookingsCount = Booking::whereDate('created_at', now()->toDateString())->count();
        
        // Hoạt động gần đây
        $activities = Booking::with(['user', 'workspace'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Data for Revenue Chart (7 days)
        $revenueDates = [];
        $revenueValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $revenueDates[] = now()->subDays($i)->format('d/m');
            $revenueValues[] = Booking::where('status', 'confirmed')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
        }

        // Data for Room Type Pie Chart
        $roomTypeStats = Booking::where('bookings.status', 'confirmed')
            ->join('workspaces', 'bookings.workspace_id', '=', 'workspaces.id')
            ->join('room_types', 'workspaces.room_type_id', '=', 'room_types.id')
            ->select('room_types.name', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('room_types.name')
            ->get();
            
        $roomTypeLabels = $roomTypeStats->pluck('name')->toArray();
        $roomTypeCounts = $roomTypeStats->pluck('count')->toArray();

        return view('admin.tongquan', compact(
            'todayRevenue', 
            'monthRevenue', 
            'newBookingsCount', 
            'activities',
            'revenueDates',
            'revenueValues',
            'roomTypeLabels',
            'roomTypeCounts'
        ));
    }

    public function booking()
    {
        $bookings = Booking::with(['user', 'payment', 'workspace'])
            ->orderByRaw("FIELD(status, 'pending') DESC")
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.booking', compact('bookings'));
    }

    public function approveBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'confirmed';
        $booking->save();
        
        $payment = \App\Models\Payment::where('booking_id', $booking->id)->first();
        if ($payment) {
            $payment->payment_status = 'completed';
            $payment->paid_at = $payment->paid_at ?: now();
            $payment->save();
        }
        
        return response()->json(['success' => true]);
    }
    
    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'cancelled';
        $booking->save();
        
        $payment = \App\Models\Payment::where('booking_id', $booking->id)->first();
        if ($payment) {
            $payment->payment_status = 'failed';
            $payment->save();
        }
        
        return response()->json(['success' => true]);
    }

    public function voucher()
    {
        $vouchers = DiscountCode::all();
        return view('admin.voucher', compact('vouchers'));
    }

    public function storeVoucher(Request $request, $id = null)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50',
            'discount_value' => 'required|numeric',
            'max_discount' => 'nullable|numeric',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
            'usage_limit' => 'nullable|integer',
        ]);
        // Set discount type based on value maybe, assuming percentage if > 0 and <= 100
        $data['discount_type'] = $data['discount_value'] <= 100 ? 'percentage' : 'fixed';
        $data['status'] = 'active';

        if ($id) {
            DiscountCode::findOrFail($id)->update($data);
        } else {
            DiscountCode::create($data);
        }

        return response()->json(['success' => true]);
    }

    public function destroyVoucher($id)
    {
        DiscountCode::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function taikhoan()
    {
        $accounts = User::withCount('bookings')
            ->withSum('bookings', 'total_amount')
            ->get();
        return view('admin.taikhoan', compact('accounts'));
    }

    public function storeTaikhoan(Request $request, $id = null)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,staff,customer',
        ]);
        
        if (!$id) {
            $data['password'] = bcrypt('12345678'); // Default password for new accounts
            $data['status'] = 'active';
        }

        if ($id) {
            User::findOrFail($id)->update($data);
        } else {
            User::create($data);
        }

        return response()->json(['success' => true]);
    }

    public function destroyTaikhoan($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function workspace()
    {
        $workspaces = \App\Models\Workspace::with(['area', 'roomType'])->get();
        $areas = \App\Models\Area::all();
        $roomTypes = \App\Models\RoomType::all();
        return view('admin.workspace', compact('workspaces', 'areas', 'roomTypes'));
    }

    public function storeWorkspace(Request $request, $id = null)
    {
        $data = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'room_type_id' => 'required|exists:room_types,id',
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'price_per_hour' => 'required|numeric|min:0',
            'price_per_month' => 'nullable|numeric|min:0',
            'min_booking_hours' => 'required|integer|min:1',
            'status' => 'required|in:active,maintenance,inactive',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $workspace = null;
        if ($id) {
            $workspace = \App\Models\Workspace::findOrFail($id);
            $workspace->update($data);
        } else {
            $workspace = \App\Models\Workspace::create($data);
        }

        if ($request->hasFile('images')) {
            if (!file_exists(public_path('Images/Workspaces'))) {
                mkdir(public_path('Images/Workspaces'), 0777, true);
            }
            foreach (Arr::wrap($request->file('images')) as $index => $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('Images/Workspaces'), $filename);
                
                \App\Models\WorkspaceImage::create([
                    'workspace_id' => $workspace->id,
                    'image_url' => 'Images/Workspaces/' . $filename,
                    'is_primary' => $index === 0 && $workspace->images()->count() === 0,
                    'display_order' => $workspace->images()->count() + 1
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function destroyWorkspace($id)
    {
        \App\Models\Workspace::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function review()
    {
        $reviews = \App\Models\Review::with(['user', 'workspace', 'adminReplies.admin'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.review', compact('reviews'));
    }

    public function replyReview(Request $request, $id)
    {
        $validated = $request->validate([
            'reply_text' => 'required|string|min:1|max:1000',
        ]);

        $review = \App\Models\Review::findOrFail($id);

        $reply = \App\Models\AdminReply::where('review_id', $review->id)->first();
        if ($reply) {
            $reply->update([
                'admin_id' => auth()->id(),
                'reply_text' => $validated['reply_text'],
            ]);
        } else {
            \App\Models\AdminReply::create([
                'review_id' => $review->id,
                'admin_id' => auth()->id(),
                'reply_text' => $validated['reply_text'],
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleReviewVisibility($id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $review->is_approved = !$review->is_approved;
        $review->save();

        return response()->json(['success' => true, 'is_approved' => $review->is_approved]);
    }
}
