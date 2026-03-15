<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Booking - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white p-4 shadow-md mb-10">
        <div class="container mx-auto">
            <a href="/" class="font-bold text-xl">WebBooking</a>
        </div>
    </nav>

    <main class="container mx-auto">
        @yield('content') </main>
</body>
</html>