@php
    try {
        $navServices = \App\Models\Service::active()->ordered()->get();
    } catch (\Exception $e) {
        $navServices = collect();
    }

    $gradients = [
        'cho-ngoi-linh-hoat' => 'from-blue-500 to-cyan-400',
        'cho-ngoi-co-dinh' => 'from-emerald-500 to-teal-400',
        'phong-lam-viec-rieng' => 'from-violet-500 to-purple-400',
        'phong-hop-tieu-chuan' => 'from-amber-500 to-orange-400',
        'khong-gian-su-kien' => 'from-rose-500 to-pink-400',
    ];

    $isDynamic = trim($__env->yieldContent('nav-mode', 'solid')) === 'dynamic';
@endphp
<nav
    class="ws-nav fixed top-0 w-full z-50 transition-all duration-300 {{ $isDynamic ? 'bg-transparent' : 'bg-white/95 backdrop-blur-xl shadow-sm border-b border-slate-100/80' }}">
    <div class="flex justify-between items-center px-8 lg:px-12 py-5 w-full max-w-1440px mx-auto">
        <a href="/"
            class="project-title font-headline font-bold text-lg tracking-wider transition-colors duration-300 {{ $isDynamic ? 'text-white' : 'text-on-surface' }}">WORKSTATION</a>
        <div class="hidden md:flex gap-10 items-center font-headline font-medium text-sm">
            <a class="ws-nav-link relative pb-1 transition-colors nav-link after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-1 after:rounded-full after:transition-all after:duration-300 after:w-0 hover:after:w-full {{ $isDynamic ? 'text-white hover:text-blue-200 after:bg-white' : 'text-slate-600 hover:text-primary after:bg-primary' }}"
                href="/">Trang chủ</a>
            <a class="ws-nav-link relative pb-1 transition-colors nav-link after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-1 after:rounded-full after:transition-all after:duration-300 after:w-0 hover:after:w-full {{ $isDynamic ? 'text-white hover:text-blue-200 after:bg-white' : 'text-slate-600 hover:text-primary after:bg-primary' }}"
                href="#about">Giới thiệu</a>

            {{-- Megamenu Dịch vụ --}}
            <div class="relative" id="megamenu-wrapper">
                <a href="#services"
                    class="ws-nav-link relative flex items-center gap-1 transition-colors pb-1 nav-link after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-1 after:rounded-full after:transition-all after:duration-300 after:w-0 hover:after:w-full {{ $isDynamic ? 'text-white hover:text-blue-200 after:bg-white' : 'text-slate-600 hover:text-primary after:bg-primary' }}"
                    id="megamenu-trigger">
                    Dịch vụ
                    <span
                        class="material-symbols-outlined text-[18px] transition-transform duration-300 {{ $isDynamic ? 'text-white' : 'text-slate-600' }}"
                        id="megamenu-arrow">expand_more</span>
                </a>

                {{-- Megamenu Panel --}}
                <div id="megamenu-panel"
                    class="megamenu-panel fixed left-0 w-full bg-white/98 backdrop-blur-2xl shadow-2xl border-t border-slate-100 opacity-0 invisible -translate-y-2 transition-all duration-300 ease-out"
                    style="top: 70px; z-index: 100;">
                    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-8">
                        <div class="grid grid-cols-5 gap-6">
                            @if ($navServices->isNotEmpty())
                                @foreach ($navServices as $srv)
                                    @php
                                        $gradientClass = $gradients[$srv->slug] ?? 'from-primary to-blue-400';
                                    @endphp
                                    <a href="{{ route('dichvu.detail', $srv->slug) }}"
                                        class="megamenu-col group rounded-2xl p-5 transition-all duration-300 hover:bg-primary-light hover:shadow-lg">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br {{ $gradientClass }} rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                            <span
                                                class="material-symbols-outlined text-white text-2xl">{{ $srv->icon }}</span>
                                        </div>
                                        <h3
                                            class="font-headline font-bold text-on-surface text-base mb-2 group-hover:text-primary transition-colors">
                                            {{ $srv->name }}</h3>
                                        <p class="text-slate-500 text-sm leading-relaxed mb-3">{{ $srv->tagline }}</p>
                                        <span
                                            class="inline-flex items-center gap-1 text-primary text-xs font-semibold opacity-0 group-hover:opacity-100 transition-all duration-300">
                                            Xem chi tiết
                                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                        </span>
                                    </a>
                                @endforeach
                            @else
                                {{-- Col 1: Chỗ ngồi linh hoạt --}}
                                <a href="{{ route('dichvu.detail', 'cho-ngoi-linh-hoat') }}"
                                    class="megamenu-col group rounded-2xl p-5 transition-all duration-300 hover:bg-primary-light hover:shadow-lg">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <span class="material-symbols-outlined text-white text-2xl">event_seat</span>
                                    </div>
                                    <h3
                                        class="font-headline font-bold text-on-surface text-base mb-2 group-hover:text-primary transition-colors">
                                        Chỗ ngồi linh hoạt</h3>
                                    <p class="text-slate-500 text-sm leading-relaxed mb-3">Hot desk – đến bất kỳ lúc
                                        nào,
                                        chọn chỗ tùy thích. Bao gồm wifi, ổ cắm và đồ uống.</p>
                                    <span
                                        class="inline-flex items-center gap-1 text-primary text-xs font-semibold opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        Xem chi tiết
                                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                    </span>
                                </a>

                                {{-- Col 2: Chỗ ngồi cố định --}}
                                <a href="{{ route('dichvu.detail', 'cho-ngoi-co-dinh') }}"
                                    class="megamenu-col group rounded-2xl p-5 transition-all duration-300 hover:bg-primary-light hover:shadow-lg">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <span class="material-symbols-outlined text-white text-2xl">chair</span>
                                    </div>
                                    <h3
                                        class="font-headline font-bold text-on-surface text-base mb-2 group-hover:text-primary transition-colors">
                                        Chỗ ngồi cố định</h3>
                                    <p class="text-slate-500 text-sm leading-relaxed mb-3">Dedicated desk – bàn riêng cố
                                        định, tủ khóa cá nhân. Không gian quen thuộc mỗi ngày.</p>
                                    <span
                                        class="inline-flex items-center gap-1 text-primary text-xs font-semibold opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        Xem chi tiết
                                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                    </span>
                                </a>

                                {{-- Col 3: Phòng làm việc riêng --}}
                                <a href="{{ route('dichvu.detail', 'phong-lam-viec-rieng') }}"
                                    class="megamenu-col group rounded-2xl p-5 transition-all duration-300 hover:bg-primary-light hover:shadow-lg">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <span
                                            class="material-symbols-outlined text-white text-2xl">corporate_fare</span>
                                    </div>
                                    <h3
                                        class="font-headline font-bold text-on-surface text-base mb-2 group-hover:text-primary transition-colors">
                                        Phòng làm việc riêng</h3>
                                    <p class="text-slate-500 text-sm leading-relaxed mb-3">Văn phòng khép kín cho team
                                        2-10 người. Riêng tư, yên tĩnh, phù hợp doanh nghiệp.</p>
                                    <span
                                        class="inline-flex items-center gap-1 text-primary text-xs font-semibold opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        Xem chi tiết
                                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                    </span>
                                </a>

                                {{-- Col 4: Phòng họp tiêu chuẩn --}}
                                <a href="{{ route('dichvu.detail', 'phong-hop-tieu-chuan') }}"
                                    class="megamenu-col group rounded-2xl p-5 transition-all duration-300 hover:bg-primary-light hover:shadow-lg">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <span class="material-symbols-outlined text-white text-2xl">meeting_room</span>
                                    </div>
                                    <h3
                                        class="font-headline font-bold text-on-surface text-base mb-2 group-hover:text-primary transition-colors">
                                        Phòng họp tiêu chuẩn</h3>
                                    <p class="text-slate-500 text-sm leading-relaxed mb-3">Phòng họp 4-12 người, trang
                                        bị
                                        màn hình, bảng trắng và hệ thống hội nghị truyền hình.</p>
                                    <span
                                        class="inline-flex items-center gap-1 text-primary text-xs font-semibold opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        Xem chi tiết
                                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                    </span>
                                </a>

                                {{-- Col 5: Không gian sự kiện --}}
                                <a href="{{ route('dichvu.detail', 'khong-gian-su-kien') }}"
                                    class="megamenu-col group rounded-2xl p-5 transition-all duration-300 hover:bg-primary-light hover:shadow-lg">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-rose-500 to-pink-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <span class="material-symbols-outlined text-white text-2xl">celebration</span>
                                    </div>
                                    <h3
                                        class="font-headline font-bold text-on-surface text-base mb-2 group-hover:text-primary transition-colors">
                                        Không gian sự kiện</h3>
                                    <p class="text-slate-500 text-sm leading-relaxed mb-3">Sức chứa 20-100 người,
                                        projector, âm thanh chuyên nghiệp. Lý tưởng cho workshop & hội thảo.</p>
                                    <span
                                        class="inline-flex items-center gap-1 text-primary text-xs font-semibold opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        Xem chi tiết
                                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                    </span>
                                </a>
                            @endif
                        </div>

                        {{-- Bottom bar in megamenu --}}
                        <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
                            <p class="text-slate-400 text-sm">Chưa biết chọn loại nào? <a href="#"
                                    class="text-primary font-semibold hover:underline">Để chúng tôi tư vấn →</a></p>
                            <a href="#services"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-primary-dark transition-colors">
                                <span class="material-symbols-outlined text-lg">grid_view</span>
                                Xem tất cả dịch vụ
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <a class="ws-nav-link relative pb-1 transition-colors nav-link after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-1 after:rounded-full after:transition-all after:duration-300 after:w-0 hover:after:w-full {{ $isDynamic ? 'text-white hover:text-blue-200 after:bg-white' : 'text-slate-600 hover:text-primary after:bg-primary' }}"
                href="#reviews">Đánh giá</a>
        </div>
        <div class="flex items-center gap-6">
            @if (Session::has('user_id'))
                <div class="relative group" id="profile-dropdown-wrapper">
                    <button class="flex items-center gap-2 focus:outline-none ws-nav-link transition-colors {{ $isDynamic ? 'text-white hover:text-blue-200' : 'text-slate-600 hover:text-primary' }}">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary to-blue-500 flex items-center justify-center text-white font-bold text-sm shadow-md border-2 border-white/20">
                            {{ mb_strtoupper(mb_substr(Session::get('user_name', 'U'), 0, 1)) }}
                        </div>
                        <span class="font-headline font-medium text-sm hidden lg:block">
                            {{ Session::get('user_name') }}
                        </span>
                        <span class="material-symbols-outlined text-[20px] transition-transform duration-300 group-hover:rotate-180">expand_more</span>
                    </button>
                    
                    {{-- Dropdown Panel --}}
                    <div class="absolute right-0 mt-3 w-64 bg-white/95 backdrop-blur-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 rounded-2xl opacity-0 invisible translate-y-2 transition-all duration-300 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0" style="top: 100%; z-index: 100;">
                        {{-- Dropdown Arrow Triangle --}}
                        <div class="absolute -top-2 right-6 w-4 h-4 bg-white border-l border-t border-slate-100 rotate-45"></div>
                        
                        <div class="relative p-4 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl z-10">
                            <p class="font-headline font-bold text-on-surface text-sm truncate">{{ Session::get('user_name') }}</p>
                            <p class="text-[11px] text-primary mt-1 uppercase tracking-wider font-bold">{{ Session::get('user_role', 'Member') }}</p>
                        </div>
                        <div class="relative p-2 z-10 bg-white">
                            <a href="{{ route('account.profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-600 hover:text-primary hover:bg-primary-light rounded-xl transition-all duration-200">
                                <span class="material-symbols-outlined text-[20px]">person</span>
                                Hồ sơ cá nhân
                            </a>
                            <a href="{{ route('account.bookings') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-600 hover:text-primary hover:bg-primary-light rounded-xl transition-all duration-200">
                                <span class="material-symbols-outlined text-[20px]">history</span>
                                Lịch sử đặt chỗ
                            </a>
                            @if(Session::get('user_role') === 'admin' || Session::get('user_role') === 'staff')
                                <a href="/admin/booking" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-600 hover:text-primary hover:bg-primary-light rounded-xl transition-all duration-200">
                                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                                    Quản trị hệ thống
                                </a>
                            @endif
                        </div>
                        <div class="relative p-2 border-t border-slate-100 bg-white rounded-b-2xl z-10">
                            <form action="{{ route('logOut') }}" method="POST" class="w-full m-0">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 rounded-xl transition-all duration-200 text-left">
                                    <span class="material-symbols-outlined text-[20px]">logout</span>
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a class="hidden md:inline-block font-headline font-medium tracking-tight text-sm transition-all active:scale-95 duration-200 {{ $isDynamic ? 'text-white/80 hover:text-white' : 'text-slate-600 hover:text-slate-900' }}"
                    href="{{ route('logIn') }}">Đăng nhập</a>
                <a class="hidden md:inline-block font-headline font-medium tracking-tight text-sm transition-all active:scale-95 duration-200 {{ $isDynamic ? 'text-white/80 hover:text-white' : 'text-slate-600 hover:text-slate-900' }}"
                    href="{{ route('register') }}">Đăng ký</a>
            @endif
            <a class="hidden md:inline-block px-6 py-2.5 font-headline text-sm font-semibold transition-all active:scale-95 rounded-lg shadow-lg {{ $isDynamic ? 'bg-white text-primary hover:bg-slate-100' : 'bg-primary text-white hover:opacity-90' }}"
                href="{{ route('booking.index') }}">Đặt chỗ ngay</a>

            <button id="menu-toggle"
                class="md:hidden hover:cursor-pointer {{ $isDynamic ? 'text-white' : 'text-slate-700' }}"
                onclick="document.getElementById('menu').classList.toggle('hidden')">
                <span class="material-symbols-outlined text-2xl">menu</span>
            </button>

            <aside id="menu"
                class="hidden md:hidden absolute top-full left-0 w-full bg-white/95 backdrop-blur-xl shadow-lg border-t border-slate-100">
                <nav class="flex flex-col p-4 space-y-1">
                    <a href="/"
                        class="block px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">Trang
                        chủ</a>
                    <a href="#about"
                        class="block px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">Giới
                        thiệu</a>

                    {{-- Mobile: Dịch vụ accordion --}}
                    <div>
                        <button onclick="document.getElementById('mobile-services').classList.toggle('hidden')"
                            class="w-full flex items-center justify-between px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">
                            Dịch vụ
                            <span class="material-symbols-outlined text-lg">expand_more</span>
                        </button>
                        <div id="mobile-services" class="hidden pl-4 space-y-1">
                            @if ($navServices->isNotEmpty())
                                @foreach ($navServices as $srv)
                                    <a href="{{ route('dichvu.detail', $srv->slug) }}"
                                        class="block px-4 py-2.5 text-slate-500 text-sm hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">{{ $srv->name }}</a>
                                @endforeach
                            @else
                                <a href="{{ route('dichvu.detail', 'cho-ngoi-linh-hoat') }}"
                                    class="block px-4 py-2.5 text-slate-500 text-sm hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">Chỗ
                                    ngồi linh hoạt</a>
                                <a href="{{ route('dichvu.detail', 'cho-ngoi-co-dinh') }}"
                                    class="block px-4 py-2.5 text-slate-500 text-sm hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">Chỗ
                                    ngồi cố định</a>
                                <a href="{{ route('dichvu.detail', 'phong-lam-viec-rieng') }}"
                                    class="block px-4 py-2.5 text-slate-500 text-sm hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">Phòng
                                    làm việc riêng</a>
                                <a href="{{ route('dichvu.detail', 'phong-hop-tieu-chuan') }}"
                                    class="block px-4 py-2.5 text-slate-500 text-sm hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">Phòng
                                    họp tiêu chuẩn</a>
                                <a href="{{ route('dichvu.detail', 'khong-gian-su-kien') }}"
                                    class="block px-4 py-2.5 text-slate-500 text-sm hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">Không
                                    gian sự kiện</a>
                            @endif
                        </div>
                    </div>

                    <a href="#reviews"
                        class="block px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">Đánh
                        giá</a>

                    <hr class="my-2 border-slate-200">

                    @if(Session::has('user_id'))
                        <div class="px-4 py-3 bg-slate-50/80 rounded-xl mb-2 mx-2 border border-slate-100">
                            <p class="font-headline font-bold text-on-surface text-sm">{{ Session::get('user_name') }}</p>
                            <p class="text-[11px] text-primary mt-1 uppercase font-bold">{{ Session::get('user_role', 'Member') }}</p>
                        </div>
                        <a href="{{ route('account.profile') }}" class="flex items-center gap-3 px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-[20px]">person</span>
                            Hồ sơ cá nhân
                        </a>
                        <a href="{{ route('account.bookings') }}" class="flex items-center gap-3 px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-[20px]">history</span>
                            Lịch sử đặt chỗ
                        </a>
                        @if(Session::get('user_role') === 'admin' || Session::get('user_role') === 'staff')
                            <a href="/admin/booking" class="flex items-center gap-3 px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-primary rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                                Quản trị hệ thống
                            </a>
                        @endif
                        <form action="{{ route('logOut') }}" method="POST" class="w-full m-0">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-600 font-medium hover:bg-red-50 rounded-lg transition-colors text-left">
                                <span class="material-symbols-outlined text-[20px]">logout</span>
                                Đăng xuất
                            </button>
                        </form>
                    @else
                        <a href="{{ route('logIn') }}"
                            class="block px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900 rounded-lg transition-colors">Đăng
                            nhập</a>
                        <a href="{{ route('register') }}"
                            class="block px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900 rounded-lg transition-colors">Đăng
                            ký</a>
                    @endif
                    <a href="{{ route('booking.index') }}"
                        class="block mt-2 px-4 py-3 bg-primary text-white text-center font-semibold rounded-lg hover:opacity-90 transition-opacity">Đặt
                        chỗ ngay</a>
                </nav>
            </aside>
        </div>
    </div>
</nav>
