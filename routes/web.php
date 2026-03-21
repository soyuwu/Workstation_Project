<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
Route::get('/', function () {
    return view('welcome');
});

// đường dẫn qua trang signIn
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');



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