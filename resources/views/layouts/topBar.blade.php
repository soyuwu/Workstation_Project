<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workstation - @yield('title', 'Hệ thống đặt chỗ')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/topBar.css') }}">
</head>
<body>
    @include('layouts.notifications')
    @include('layouts.nav')     
    <main class="container content-area">
        @yield('content')
    </main>
</body>
</html>