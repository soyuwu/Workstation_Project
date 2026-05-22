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
});

// User Profile & Dashboard
use App\Http\Controllers\ProfileController;
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile/update', [ProfileController::class, 'updateInfo'])->name('profile.update');
Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
Route::post('/profile/verify-quick', [ProfileController::class, 'quickVerify'])->name('profile.verify_quick');
Route::post('/profile/booking/{id}/cancel', [ProfileController::class, 'cancelBooking'])->name('profile.booking.cancel');
Route::post('/profile/booking/review', [ProfileController::class, 'submitReview'])->name('profile.booking.review');
Route::get('/profile/payment/{payment_id}/pay', [ProfileController::class, 'payAgain'])->name('profile.payment.pay');
Route::post('/profile/payment/{payment_id}/confirm', [ProfileController::class, 'confirmPayment'])->name('profile.payment.confirm');


Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.tongquan');
    });
    Route::get('/booking', function () {
        return view('admin.booking');
    });
    Route::get('/facility', function () {
        return view('admin.map');
    });
    Route::get('/fnb', function () {
        return view('admin.fnb');
    });
    Route::get('/marketing', function () {
        return view('admin.voucher');
    });
    Route::get('/crm', function () {
        return view('admin.user');
    });
    Route::get('/settings', function () {
        return view('admin.nhansu');
    });
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