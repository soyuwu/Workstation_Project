<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\DiscountCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang cá nhân User Dashboard.
     */
    public function index()
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('logIn')->with('error', 'Vui lòng đăng nhập để truy cập trang cá nhân.');
        }

        $user = User::findOrFail($userId);

        // Lấy tất cả đặt chỗ của user kèm theo thông tin Workspace và Review
        $bookings = Booking::with(['workspace', 'review'])
            ->where('user_id', $user->id)
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        $today = Carbon::today()->toDateString();

        // Phân loại đặt chỗ
        $upcoming = $bookings->filter(function ($b) use ($today) {
            return in_array($b->status, ['pending', 'confirmed']) && $b->booking_date >= $today;
        });

        $active = $bookings->filter(function ($b) use ($today) {
            // Đặt chỗ được coi là Active nếu là hôm nay và trạng thái là confirmed
            return $b->status === 'confirmed' && $b->booking_date === $today;
        });

        $past = $bookings->filter(function ($b) use ($today) {
            return in_array($b->status, ['completed', 'cancelled']) || $b->booking_date < $today;
        });

        // Lấy danh sách hóa đơn / thanh toán
        $payments = Payment::with('booking.workspace')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Lấy danh sách Voucher giảm giá còn hoạt động
        $vouchers = DiscountCode::where('status', 'active')
            ->where(function($q) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
            })
            ->orderBy('valid_until', 'asc')
            ->get();

        return view('profile.index', compact('user', 'upcoming', 'active', 'past', 'payments', 'vouchers'));
    }

    /**
     * Cập nhật thông tin cơ bản (name, phone).
     */
    public function updateInfo(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('logIn');
        }

        $user = User::findOrFail($userId);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Họ tên không được để trống.',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();

        // Cập nhật lại session name
        Session::put('user_name', $user->name);

        return redirect()->route('profile')->with('success', 'Cập nhật thông tin cá nhân thành công!');
    }

    /**
     * Cập nhật mật khẩu bảo mật.
     */
    public function updatePassword(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('logIn');
        }

        $user = User::findOrFail($userId);

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:4|confirmed',
        ], [
            'current_password.required' => 'Mật khẩu hiện tại không được để trống.',
            'password.required' => 'Mật khẩu mới không được để trống.',
            'password.min' => 'Mật khẩu mới phải có tối thiểu 4 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không trùng khớp.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile')->with('error', 'Mật khẩu hiện tại không chính xác.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile')->with('success', 'Đổi mật khẩu thành công!');
    }

    /**
     * Kích hoạt nhanh tài khoản qua email để phục vụ test.
     */
    public function quickVerify()
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('logIn');
        }

        $user = User::findOrFail($userId);
        $user->email_verified_at = now();
        $user->status = 'active';
        $user->save();

        return redirect()->route('profile')->with('success', 'Đã kích hoạt tài khoản của bạn thành công!');
    }

    /**
     * Hủy đặt chỗ từ phía khách hàng.
     */
    public function cancelBooking($id)
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('logIn');
        }

        $booking = Booking::where('id', $id)->where('user_id', $userId)->firstOrFail();

        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return redirect()->route('profile')->with('error', 'Không thể hủy lịch đặt chỗ đã hoàn thành hoặc đã hủy.');
        }

        $booking->status = 'cancelled';
        $booking->cancellation_reason = 'Khách hàng chủ động hủy từ trang cá nhân';
        $booking->save();

        // Nếu có hóa đơn chưa thanh toán, cập nhật thành failed
        $payment = Payment::where('booking_id', $booking->id)->first();
        if ($payment && $payment->payment_status === 'pending') {
            $payment->payment_status = 'failed';
            $payment->save();
        }

        return redirect()->route('profile')->with('success', 'Đã hủy lịch đặt chỗ thành công.');
    }

    /**
     * Gửi đánh giá cho phòng/không gian làm việc.
     */
    public function submitReview(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('logIn');
        }

        $user = User::findOrFail($userId);

        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'workspace_id' => 'required|exists:workspaces,id',
            'rating' => 'required|numeric|min:1|max:5',
            'content' => 'required|string|min:5',
        ], [
            'content.required' => 'Nội dung đánh giá không được để trống.',
            'content.min' => 'Nội dung đánh giá phải dài từ 5 ký tự trở lên.',
        ]);

        // Đảm bảo booking này là của user hiện tại và đã hoàn thành
        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Tạo review
        Review::create([
            'rating' => $request->rating,
            'content' => $request->content,
            'author_name' => $user->name,
            'author_role' => 'Thành viên',
            'is_approved' => true, // Phê duyệt tự động cho môi trường test
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'workspace_id' => $request->workspace_id,
        ]);

        return redirect()->route('profile')->with('success', 'Cảm ơn bạn đã gửi đánh giá không gian làm việc!');
    }

    /**
     * Hiển thị trang giả lập thanh toán lại.
     */
    public function payAgain($paymentId)
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('logIn');
        }

        $payment = Payment::with('booking.workspace')
            ->where('id', $paymentId)
            ->where('user_id', $userId)
            ->firstOrFail();

        if ($payment->payment_status === 'completed') {
            return redirect()->route('profile')->with('error', 'Hóa đơn này đã được thanh toán thành công trước đó.');
        }

        return view('profile.payment_gateway', compact('payment'));
    }

    /**
     * Xác nhận thanh toán thành công (giả lập).
     */
    public function confirmPayment(Request $request, $paymentId)
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return redirect()->route('logIn');
        }

        $payment = Payment::where('id', $paymentId)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Cập nhật trạng thái Payment
        $payment->payment_status = 'completed';
        $payment->transaction_code = 'MOCK-TX-' . strtoupper(bin2hex(random_bytes(6)));
        $payment->paid_at = now();
        $payment->payment_method = $request->input('payment_method', 'momo');
        $payment->payment_gateway = $request->input('payment_method') === 'momo' ? 'MoMo' : 'VietQR';
        $payment->save();

        // Cập nhật trạng thái Booking liên quan thành confirmed
        $booking = Booking::findOrFail($payment->booking_id);
        $booking->status = 'confirmed';
        $booking->save();

        return redirect()->route('profile')->with('success', 'Thanh toán giả lập thành công! Trạng thái đặt chỗ đã được xác nhận.');
    }
}
