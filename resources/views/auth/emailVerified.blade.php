<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkStation - Quên mật khẩu</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(219,234,254,0.95),_rgba(248,250,252,1)_68%)] px-4 py-6 sm:px-6 lg:px-10">
    <main class="mx-auto flex min-h-[calc(100vh-3rem)] max-w-5xl items-center justify-center">
        <section class="grid overflow-hidden rounded-[2rem] bg-white shadow-[var(--shadow-ambient)] lg:grid-cols-[1.05fr_0.95fr]">
            <div
                class="hidden min-h-[620px] flex-col justify-end bg-slate-900 p-10 text-white lg:flex"
                style="background-image: linear-gradient(160deg, rgba(37, 99, 235, 0.9), rgba(15, 23, 42, 0.9)), url('{{ asset('4872300.jpg') }}'); background-position: center; background-size: cover;"
            >
                <span class="mb-4 inline-flex w-fit rounded-full border border-white/20 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em]">
                    Password reset
                </span>
                <h1 class="mb-4 font-headline text-4xl font-bold leading-tight">
                    Lấy lại quyền truy cập vào tài khoản của bạn
                </h1>
            </div>

            <div class="flex flex-col justify-center px-8 py-10 sm:px-10 lg:px-14">
                <a href="{{ route('logIn') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-primary">
                    <span class="text-lg">←</span>
                    Quay lại đăng nhập
                </a>
                <h2 class="mb-3 font-headline text-4xl font-bold text-on-surface">Đặt lại mật khẩu</h2>
                <form class="space-y-5">
                    <label class="block">
                        <input type="email" placeholder="Nhập email đã đăng ký" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition-all duration-200 focus:border-primary focus:bg-white focus:ring-4 focus:ring-primary/10">
                    </label>

                    <button type="button" class="inline-flex w-full items-center justify-center rounded-2xl bg-primary px-6 py-3.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-primary-dark active:scale-[0.99]">
                        Gửi email
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
