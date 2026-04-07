<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;

//Route::get('/', function () {
//    return view('LandingPage.welcome');
//});

// đường dẫn qua trang signIn
//Route::post('/register', [AuthController::class, 'register']);
//Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

// Duong dan qua trang LogIn
//Route::post('/logIn', [AuthController::class, 'logIn']);
//Route::get('/logIn', [AuthController::class, 'showLogInForm'])->name('logIn');

// LogOut
//Route::get('/logOut', [AuthController::class, 'logOut'])->name('logOut');

Route::get('/', function () {
    return view('LandingPage.welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.admin.tongquan');
    });
    Route::get('/booking', function () {
        return view('layouts.admin.booking');
    });
    Route::get('/facility', function () {
        return view('layouts.admin.map');
    });
    Route::get('/fnb', function () {
        return view('layouts.admin.fnb');
    });
    Route::get('/marketing', function () {
        return view('layouts.admin.voucher');
    });
    Route::get('/crm', function () {
        return view('layouts.admin.user');
    });
    Route::get('/settings', function () {
        return view('layouts.admin.nhansu');
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
//     return 'Da luu gia tri thanh cong';         //put-get cache truyền thống!!!   
// });

// Route::get('/cache-get', function () {
//     $cache = app()->make('cache');
//     $value = $cache->get('name');
//     return $value;
// });

// //put-get dùng facades
// Route::get('/cache-put', function () {
//     Cache::put('name','Pham Truong Giang',20);
//     return 'da luu vao cache thanh cong';    
// })->name('putCache');

// Route::get('/cache-get', function () {
//     $value = Cache::get('name');
//     return $value;
// })->name('getCache');

// Route::get('/user', function() {
//     session(['name' => 'Truong Giang'], ['age' => 20]);
//     return session('age');
// });

// Route::get('/user', function(Request $request) {

// });