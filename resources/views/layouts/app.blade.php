<!DOCTYPE html>
<html lang="vi">

<head>
    <link data-default-icon="https://res.cloudinary.com/doj3fpuqb/image/upload/v1780147684/copy_of_711425482_1665934987958157_8586424012407537890_n.jpg" data-badged-icon="https://res.cloudinary.com/doj3fpuqb/image/upload/v1780147684/copy_of_711425482_1665934987958157_8586424012407537890_n.jpg" rel="shortcut icon" href="https://res.cloudinary.com/doj3fpuqb/image/upload/v1780147684/copy_of_711425482_1665934987958157_8586424012407537890_n.jpg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkStation - @yield('title', 'Hệ thống đặt chỗ')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body data-nav-mode="@yield('nav-mode', 'solid')" class="bg-background font-body text-on-surface antialiased @yield('body-class')">
    @include('layouts.nav')

    <div class="fixed left-1/2 top-24 z-[60] w-[calc(100%-2rem)] max-w-xl -translate-x-1/2">
        <x-common.flash-messages />
    </div>

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
