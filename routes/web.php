<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;

Route::get('/', function () {
    $reviews = \App\Models\Review::where('is_approved', true)->get();
    return view('LandingPage.welcome', compact('reviews'));
});

// đường dẫn qua trang signIn/signUp dùng chung 1 form
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register', [AuthController::class, 'showAuthForm'])->name('register');

// Duong dan qua trang LogIn
Route::post('/logIn', [AuthController::class, 'logIn']);
Route::get('/logIn', [AuthController::class, 'showAuthForm'])->name('logIn');

// LogOut
Route::get('/logOut', [AuthController::class, 'logOut'])->name('logOut');

Route::get('activate', [AuthController::class, 'activate'])->name('activate');
// Trang Dịch vụ
Route::get('/khong-gian', function () {
    return view('services.khong-gian');
})->name('khongGian');

Route::get('/dich-vu', function () {
    return view('services.dich-vu');
})->name('dichVu');

// Chi tiết dịch vụ từng loại phòng
Route::get('/dich-vu/{slug}', [ServiceController::class, 'detail'])->name('dichvu.detail');

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'showForgetPasswordForm'])->name('forgot.password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail']);
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.update');

// Booking System
Route::prefix('booking')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/monthly/{type}', [BookingController::class, 'monthly'])->name('booking.monthly');
    Route::get('/hourly/{type}', [BookingController::class, 'hourly'])->name('booking.hourly');
    Route::get('/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
    Route::post('/process', [BookingController::class, 'processBooking'])->name('booking.process');
    // Luồng đặt tháng
    Route::get('/monthly-checkout', [BookingController::class, 'monthlyCheckout'])->name('booking.monthly.checkout');
    Route::post('/monthly-process', [BookingController::class, 'processMonthlyBooking'])->name('booking.monthly.process');
});

// Payment System
use App\Http\Controllers\PaymentController;
Route::prefix('payment')->group(function () {
    // Thanh toán MoMo
    Route::get('/momo-return', [PaymentController::class, 'momoReturn'])->name('payment.momo_return');
    Route::post('/momo-ipn', [PaymentController::class, 'momoIPN'])->name('payment.momo_ipn');

    // Thanh toán VietQR
    Route::get('/vietqr/{booking_code}', [PaymentController::class, 'vietqr'])->name('payment.vietqr');
    Route::post('/vietqr/confirm/{booking_code}', [PaymentController::class, 'confirmVietqr'])->name('payment.vietqr.confirm');

    // Webhook tự động từ SePay
    Route::post('/webhook', [PaymentController::class, 'sepayWebhook'])->name('payment.sepay_webhook');
});


use App\Http\Controllers\AdminController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/tongquan', [AdminController::class, 'tongquan'])->name('tongquan');
    Route::get('/booking', [AdminController::class, 'booking'])->name('booking');
    Route::post('/booking/{id}/approve', [AdminController::class, 'approveBooking'])->name('booking.approve');
    Route::post('/booking/{id}/cancel', [AdminController::class, 'cancelBooking'])->name('booking.cancel');
    Route::get('/facility', function () {
        return view('admin.map');
    })->name('facility');
    Route::get('/fnb', function () {
        return view('admin.fnb');
    })->name('fnb');
    
    Route::get('/voucher', [AdminController::class, 'voucher'])->name('voucher');
    Route::post('/voucher', [AdminController::class, 'storeVoucher'])->name('voucher.store');
    Route::put('/voucher/{id}', [AdminController::class, 'storeVoucher']);
    Route::delete('/voucher/{id}', [AdminController::class, 'destroyVoucher']);
    
    Route::get('/taikhoan', [AdminController::class, 'taikhoan'])->name('taikhoan');
    Route::post('/taikhoan', [AdminController::class, 'storeTaikhoan'])->name('taikhoan.store');
    Route::put('/taikhoan/{id}', [AdminController::class, 'storeTaikhoan']);
    Route::delete('/taikhoan/{id}', [AdminController::class, 'destroyTaikhoan']);

    Route::get('/workspace', [AdminController::class, 'workspace'])->name('workspace');
    Route::post('/workspace', [AdminController::class, 'storeWorkspace'])->name('workspace.store');
    Route::put('/workspace/{id}', [AdminController::class, 'storeWorkspace']);
    Route::delete('/workspace/{id}', [AdminController::class, 'destroyWorkspace']);


});


// Route::get('/test-mail', function () {
//     $auth = new \App\Http\Controllers\Auth\AuthController();
//     $result = $auth->sendActivationEmail('24520422@gm.uit.edu.vn', 'Test User', 'http://127.0.0.1:8000/test');
//     return $result ? "Mail đã gửi thành công!" : "Gửi mail thất bại, hãy kiểm tra log.";
// }); TEST TINH NANG GUI MAIL TU DONG 

// Route::get('/signin', function () {
//     return view('auth.SignIn');
// })->name('SignIn');

// Route::get('/thongbao', function () {
//     $so1 = app('thongBaoDauTien'); 
//     return $so1->index();
// });

// Route::get('/cache-put', function () {
//     $cache = app()->make('cache');
//     $cache->put('name','Giang',20);
//     return 'Da luu gia tri thanh cong';         //put-get cache truyền thống!!!   
// });

// Route::get('/cache-get', function () {
//     $cache = app()->make('cache');
//     $value = $cache->get('name');
//     return $value;
// });

// //put-get dùng facades
// Route::get('/cache-put', function () {
//     Cache::put('name','Pham Truong Giang',20);
//     return 'da luu vao cache thanh cong';    
// })->name('putCache');

// Route::get('/cache-get', function () {
//     $value = Cache::get('name');
//     return $value;
// })->name('getCache');

// Route::get('/user', function () {
//     session(['name' => 'Truong Giang'], ['age' => 20]);
//     echo (Session->name);
//     return session('age');
// });

// Route::get('/user', function(Request $request) {

// });