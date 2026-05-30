<!DOCTYPE html>
<html lang="vi">
<head>
    @include('partials.favicon')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkStation - Đặt lại mật khẩu</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(219,234,254,0.95),_rgba(248,250,252,1)_68%)] px-4 py-6 sm:px-6 lg:px-10">
    <main class="mx-auto flex min-h-[calc(100vh-3rem)] max-w-5xl items-center justify-center">
        <section class="grid overflow-hidden rounded-[2rem] bg-white shadow-2xl lg:grid-cols-[1.05fr_0.95fr]">
            <!-- Cột trái: Banner -->
            <div
                class="hidden min-h-[620px] flex-col justify-end bg-slate-900 p-10 text-white lg:flex"
                style="background-image: linear-gradient(160deg, rgba(37, 99, 235, 0.9), rgba(15, 23, 42, 0.9)), url('{{ asset('4872300.jpg') }}'); background-position: center; background-size: cover;"
            >
                <span class="mb-4 inline-flex w-fit rounded-full border border-white/20 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em]">
                    Secure Access
                </span>
                <h1 class="mb-4 font-headline text-4xl font-bold leading-tight">
                    Thiết lập mật khẩu mới cho tài khoản của bạn
                </h1>
            </div>

            <!-- Cột phải: Form nhập mật khẩu mới -->
            <div class="flex flex-col justify-center px-8 py-10 sm:px-10 lg:px-14">
                <h2 class="mb-2 font-headline text-4xl font-bold text-slate-800">Mật khẩu mới</h2>
                <p class="mb-8 text-sm text-slate-500">Vui lòng nhập mật khẩu mới và xác nhận lại để hoàn tất.</p>

                <x-common.flash-messages class="mb-5" />

                <form action="{{ route('password.update', ['token' => $token]) }}" method="POST" class="space-y-5">
                    @csrf
                    <!-- Các trường ẩn bắt buộc để Backend xác thực -->
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- Mật khẩu mới -->
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-slate-700 ml-1">Mật khẩu mới</label>
                        <input type="password" name="password" required
                               placeholder="Tối thiểu 4 ký tự" 
                               class="w-full rounded-2xl border @error('password') border-red-400 @else border-slate-200 @enderror bg-slate-50 px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        @error('password')
                            <p class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Xác nhận mật khẩu -->
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-slate-700 ml-1">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" required
                               placeholder="Nhập lại mật khẩu mới" 
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-blue-700 active:scale-[0.98] shadow-lg shadow-blue-200">
                        Cập nhật mật khẩu
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
