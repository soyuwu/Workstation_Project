<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkStation - Tài khoản</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(219,234,254,0.95),_rgba(248,250,252,1)_68%)] px-4 py-6 sm:px-6 lg:px-10">
    <main class="mx-auto flex min-h-[calc(100vh-3rem)] max-w-6xl items-center justify-center">
        <section id="container" data-auth-container class="auth-card {{ request()->is('register') ? 'is-register' : '' }}">
            <div class="absolute left-1/2 top-6 z-20 w-[calc(100%-2rem)] max-w-md -translate-x-1/2 space-y-3">
                @if (session('success'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 shadow-sm">
                        {{ session('warning') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 shadow-sm">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="auth-mobile-toggle absolute left-6 right-6 top-6 z-20 rounded-2xl bg-slate-100 p-1">
                <button type="button" data-auth-target="login" data-auth-toggle class="flex-1 rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 transition-all duration-200">
                    Đăng nhập
                </button>
                <button type="button" data-auth-target="register" data-auth-toggle class="flex-1 rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 transition-all duration-200">
                    Đăng ký
                </button>
            </div>

            <div class="auth-form-panel auth-form-panel--signin px-8 py-12 sm:px-10 lg:px-14">
                <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">
                    <a href="{{ url('/') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-primary">
                        <span class="text-lg">←</span>
                        Quay lại trang chủ
                    </a>

                    <span class="mb-4 inline-flex w-fit rounded-full bg-primary-light px-4 py-2 text-sm font-semibold text-primary">
                        Welcome back
                    </span>
                    <h1 class="mb-3 font-headline text-4xl font-bold text-on-surface">Đăng nhập</h1>
                    <p class="mb-8 text-slate-500">
                        Tiếp tục làm việc với tài khoản WorkStation của bạn.
                    </p>

                    <div class="mb-8 grid grid-cols-3 gap-3">
                        <button type="button" class="rounded-2xl border border-slate-200 px-4 py-3 text-slate-500 transition-colors hover:border-primary hover:text-primary">
                            <i class="fa-brands fa-google"></i>
                        </button>
                        <button type="button" class="rounded-2xl border border-slate-200 px-4 py-3 text-slate-500 transition-colors hover:border-primary hover:text-primary">
                            <i class="fa-brands fa-github"></i>
                        </button>
                        <button type="button" class="rounded-2xl border border-slate-200 px-4 py-3 text-slate-500 transition-colors hover:border-primary hover:text-primary">
                            <i class="fa-brands fa-facebook-f"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ url('/logIn') }}" class="space-y-4">
                        @csrf

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Email</span>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10">
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Mật khẩu</span>
                            <input type="password" name="password" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10">
                        </label>

                        <div class="flex items-center justify-between gap-4 text-sm">
                            <span class="text-slate-500">Đăng nhập để tiếp tục đặt chỗ và quản lý booking.</span>
                            <a href="{{ route('forgot.password') }}" class="font-semibold text-primary transition-colors hover:text-primary-dark">
                                Quên mật khẩu?
                            </a>
                        </div>

                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-primary px-6 py-3.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-primary-dark active:scale-[0.99]">
                            Đăng nhập
                        </button>
                    </form>
                </div>
            </div>

            <div class="auth-form-panel auth-form-panel--signup px-8 py-12 sm:px-10 lg:px-14">
                <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">
                    <a href="{{ url('/') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-primary">
                        <span class="text-lg">←</span>
                        Quay lại trang chủ
                    </a>

                    <span class="mb-4 inline-flex w-fit rounded-full bg-accent-light px-4 py-2 text-sm font-semibold text-accent">
                        New member
                    </span>
                    <h1 class="mb-3 font-headline text-4xl font-bold text-on-surface">Tạo tài khoản</h1>
                    <p class="mb-8 text-slate-500">
                        Tạo tài khoản mới để trải nghiệm không gian làm việc và quản lý lịch đặt chỗ thuận tiện hơn.
                    </p>

                    <form method="POST" action="{{ url('/register') }}" class="space-y-4">
                        @csrf

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Họ và tên</span>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10">
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Email</span>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10">
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Mật khẩu</span>
                            <input type="password" name="password" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10">
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Xác nhận mật khẩu</span>
                            <input type="password" name="password_confirmation" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10">
                        </label>

                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-primary px-6 py-3.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-primary-dark active:scale-[0.99]">
                            Tạo tài khoản
                        </button>
                    </form>
                </div>
            </div>

            <div class="auth-overlay">
                <div
                    class="auth-overlay-track"
                    style="background-image: linear-gradient(135deg, rgba(37, 99, 235, 0.92), rgba(15, 23, 42, 0.88)), url('{{ asset('4872300.jpg') }}'); background-position: center; background-size: cover;"
                >
                    <div class="auth-overlay-panel text-white">
                        <span class="mb-4 rounded-full border border-white/20 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em]">
                            WorkStation
                        </span>
                        <h2 class="mb-4 font-headline text-4xl font-bold">Hello, Friend!</h2>
                        <p class="mb-8 max-w-sm text-base text-blue-100">
                            Tạo tài khoản để khám phá không gian làm việc linh hoạt, quản lý booking và nhận các ưu đãi mới nhất từ WorkStation.
                        </p>
                        <button type="button" data-auth-target="register" class="rounded-2xl border border-white/30 px-8 py-3 text-sm font-semibold text-white transition-all duration-300 hover:bg-white/10">
                            Đăng ký ngay
                        </button>
                    </div>

                    <div class="auth-overlay-panel text-white">
                        <span class="mb-4 rounded-full border border-white/20 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em]">
                            Welcome back
                        </span>
                        <h2 class="mb-4 font-headline text-4xl font-bold">Rất vui được gặp lại bạn</h2>
                        <p class="mb-8 max-w-sm text-base text-blue-100">
                            Đăng nhập để tiếp tục đặt lịch nhanh hơn, theo dõi lịch sử sử dụng và làm việc cùng cộng đồng sáng tạo của bạn.
                        </p>
                        <button type="button" data-auth-target="login" class="rounded-2xl border border-white/30 px-8 py-3 text-sm font-semibold text-white transition-all duration-300 hover:bg-white/10">
                            Đăng nhập
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>