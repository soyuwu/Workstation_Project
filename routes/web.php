<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/thongbao', function () {
    $so1 = app('thongBaoDauTien');
    return $so1->index();
});