<nav class="ws-nav fixed inset-x-0 top-0 z-50 bg-transparent transition-all duration-500">
    <div class="mx-auto flex w-full max-w-[1440px] items-center justify-between px-6 py-5 lg:px-12">
        <a href="{{ url('/') }}" class="project-title text-white">
            WORKSTATION
        </a>

        <div class="hidden items-center gap-10 text-sm font-medium md:flex">
            <a class="ws-nav-link relative pb-1 text-white transition-colors after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:rounded-full after:bg-white after:transition-all after:duration-300 after:content-[''] hover:text-blue-200 hover:after:w-full" href="{{ url('/#about') }}">
                Trang chủ
            </a>
            <a class="ws-nav-link relative pb-1 text-white transition-colors after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:rounded-full after:bg-white after:transition-all after:duration-300 after:content-[''] hover:text-blue-200 hover:after:w-full" href="{{ url('/#about') }}">
                Giới thiệu
            </a>
            <div class="group relative">
                <a class="ws-nav-link relative flex items-center gap-1 py-2 text-white transition-colors after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:rounded-full after:bg-white after:transition-all after:duration-300 after:content-[''] hover:text-blue-200 hover:after:w-full" href="{{ url('/#services') }}">
                    Dịch vụ
                    <span class="material-symbols-outlined text-[18px]">expand_more</span>
                </a>

                <div class="dropdown-menu invisible absolute left-0 top-full w-56 rounded-2xl bg-white/95 py-2 opacity-0 shadow-ambient backdrop-blur-lg transition-all duration-300 group-hover:visible group-hover:opacity-100">
                    <a class="block px-5 py-2.5 text-sm text-slate-700 transition-colors hover:bg-surface-container-low hover:text-primary" href="{{ route('khongGian') }}">
                        Không gian làm việc
                    </a>
                    <a class="block px-5 py-2.5 text-sm text-slate-700 transition-colors hover:bg-surface-container-low hover:text-primary" href="{{ route('dichVu') }}">
                        Danh mục dịch vụ
                    </a>
                </div>
            </div>
            <a class="ws-nav-link relative pb-1 text-white transition-colors after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 after:rounded-full after:bg-white after:transition-all after:duration-300 after:content-[''] hover:text-blue-200 hover:after:w-full" href="{{ url('/#reviews') }}">
                Đánh giá
            </a>
        </div>

        <div class="flex items-center gap-3 md:gap-6">
            @if (Session::has('user_id'))
                <div class="hidden items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm text-white backdrop-blur-sm md:flex">
                    <span class="material-symbols-outlined text-base">verified_user</span>
                    <span>{{ Session::get('user_name') }}</span>
                    <span class="h-1 w-1 rounded-full bg-white/70"></span>
                    <span>{{ Session::get('user_role') ?: 'member' }}</span>
                </div>
                <a href="{{ route('logOut') }}" class="hidden rounded-lg border border-red-200/60 bg-white/90 px-4 py-2 text-sm font-semibold text-red-500 transition-colors hover:bg-red-50 md:inline-flex">
                    Đăng xuất
                </a>
            @else
                <a href="{{ route('logIn') }}" class="ws-nav-auth hidden text-sm font-medium text-white/80 transition-all duration-200 hover:text-white md:inline-block">
                    Đăng nhập
                </a>
                <a href="{{ route('register') }}" class="ws-nav-auth hidden text-sm font-medium text-white/80 transition-all duration-200 hover:text-white md:inline-block">
                    Đăng ký
                </a>
            @endif

            <a href="{{ route('khongGian') }}" data-nav-cta class="hidden rounded-lg bg-white px-6 py-2.5 text-sm font-semibold text-primary shadow-lg transition-all duration-200 hover:bg-slate-100 md:inline-flex">
                Đặt chỗ ngay
            </a>

            <button type="button" data-menu-toggle aria-controls="mobile-menu" aria-expanded="false" class="rounded-lg p-2 text-white transition-colors hover:bg-white/10 md:hidden">
                <span class="material-symbols-outlined text-2xl">menu</span>
            </button>
        </div>
    </div>

    <aside id="mobile-menu" data-mobile-menu class="hidden border-t border-slate-100 bg-white/95 shadow-lg backdrop-blur-xl md:hidden">
        <nav class="flex flex-col gap-1 p-4">
            <a href="{{ url('/') }}" class="rounded-lg px-4 py-3 font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-primary">
                Trang chủ
            </a>
            <a href="{{ url('/#about') }}" class="rounded-lg px-4 py-3 font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-primary">
                Giới thiệu
            </a>
            <a href="{{ route('khongGian') }}" class="rounded-lg px-4 py-3 font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-primary">
                Không gian làm việc
            </a>
            <a href="{{ route('dichVu') }}" class="rounded-lg px-4 py-3 font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-primary">
                Danh mục dịch vụ
            </a>
            <a href="{{ url('/#reviews') }}" class="rounded-lg px-4 py-3 font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-primary">
                Đánh giá
            </a>

            <hr class="my-2 border-slate-200">

            @if (Session::has('user_id'))
                <div class="rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    User #{{ Session::get('user_name') }} - {{ Session::get('user_role') ?: 'member' }}
                </div>
                <a href="{{ route('logOut') }}" class="rounded-lg px-4 py-3 font-medium text-red-500 transition-colors hover:bg-red-50">
                    Đăng xuất
                </a>
            @else
                <a href="{{ route('logIn') }}" class="rounded-lg px-4 py-3 font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                    Đăng nhập
                </a>
                <a href="{{ route('register') }}" class="rounded-lg px-4 py-3 font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                    Đăng ký
                </a>
            @endif

            <a href="{{ route('khongGian') }}" class="mt-2 rounded-lg bg-primary px-4 py-3 text-center font-semibold text-white transition-opacity hover:opacity-90">
                Đặt chỗ ngay
            </a>
        </nav>
    </aside>
</nav>
