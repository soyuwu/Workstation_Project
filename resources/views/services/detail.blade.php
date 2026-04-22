@extends('layouts.app')

@section('title', $service['name'] . ' - WorkStation')

@section('content')

    {{-- Hero Banner --}}
    <section class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ $service['hero_image'] }}" alt="{{ $service['name'] }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70"></div>
        </div>
        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <div
                class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md text-white font-headline font-semibold text-sm px-5 py-2.5 rounded-full mb-6 border border-white/20">
                <span class="material-symbols-outlined text-lg">{{ $service['icon'] }}</span>
                {{ $service['badge'] }}
            </div>
            <h1 class="font-headline text-4xl lg:text-6xl font-bold text-white mb-4 leading-tight">{{ $service['name'] }}
            </h1>
            <p class="text-blue-100 text-lg lg:text-xl max-w-2xl mx-auto leading-relaxed">{{ $service['tagline'] }}</p>
        </div>
        {{-- Scroll indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <span class="material-symbols-outlined text-white/60 text-3xl">keyboard_arrow_down</span>
        </div>
    </section>

    {{-- Pricing + Overview --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                {{-- Left: Description --}}
                <div>
                    <div class="section-divider mb-6"></div>
                    <h2 class="font-headline text-3xl lg:text-4xl font-bold text-on-surface mb-6 leading-tight">
                        {{ $service['headline'] }}
                    </h2>
                    <p class="text-slate-600 text-lg leading-relaxed mb-6">
                        {{ $service['description'] }}
                    </p>
                    <p class="text-slate-500 leading-relaxed mb-8">
                        {{ $service['description_2'] }}
                    </p>

                    {{-- Price card --}}
                    <div
                        class="inline-flex items-end gap-2 bg-gradient-to-r from-primary to-blue-600 text-white px-8 py-4 rounded-2xl shadow-lg shadow-primary/25">
                        <span class="text-sm opacity-80">Từ</span>
                        <span class="font-headline text-3xl font-bold">{{ $service['price'] }}</span>
                        <span class="text-sm opacity-80">/ {{ $service['price_unit'] }}</span>
                    </div>
                </div>

                {{-- Right: Image --}}
                <div class="relative">
                    <div class="rounded-2xl overflow-hidden shadow-[var(--shadow-card)]">
                        <img src="{{ $service['detail_image'] }}" alt="{{ $service['name'] }}"
                            class="w-full h-[400px] object-cover">
                    </div>
                    {{-- Floating badge --}}
                    <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl p-4 shadow-xl border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-primary-light rounded-xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-2xl">{{ $service['icon'] }}</span>
                            </div>
                            <div>
                                <div class="font-headline font-bold text-on-surface">{{ $service['capacity'] }}</div>
                                <div class="text-slate-400 text-sm">Sức chứa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Grid --}}
    <section class="py-16 lg:py-24 bg-background">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <h2 class="font-headline text-3xl lg:text-4xl font-bold text-on-surface mb-4">
                    Tiện ích <span class="text-primary">đi kèm</span>
                </h2>
                <div class="section-divider mx-auto mb-4"></div>
                <p class="text-slate-500 text-lg">Mọi thứ bạn cần để tập trung làm việc hiệu quả</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($service['features'] as $feature)
                    <div
                        class="bg-white rounded-2xl p-6 shadow-[var(--shadow-ambient)] hover:shadow-[var(--shadow-card-hover)] hover:-translate-y-1 transition-all duration-300">
                        <div class="w-12 h-12 bg-primary-light rounded-xl flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-primary text-xl">{{ $feature['icon'] }}</span>
                        </div>
                        <h3 class="font-headline font-bold text-on-surface text-base mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Who is this for? --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="font-headline text-3xl lg:text-4xl font-bold text-on-surface mb-6">
                        Phù hợp với <span class="text-primary">ai?</span>
                    </h2>
                    <div class="section-divider mb-8"></div>
                    <div class="space-y-4">
                        @foreach ($service['target_audience'] as $audience)
                            <div class="flex items-start gap-4 bg-background rounded-xl p-4">
                                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-white text-lg">check</span>
                                </div>
                                <div>
                                    <h4 class="font-headline font-semibold text-on-surface mb-1">{{ $audience['title'] }}
                                    </h4>
                                    <p class="text-slate-500 text-sm">{{ $audience['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative">
                    <div class="rounded-2xl overflow-hidden shadow-[var(--shadow-card)]">
                        <img src="{{ $service['audience_image'] }}" alt="Đối tượng phù hợp"
                            class="w-full h-[450px] object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="relative overflow-hidden">
        <div class="cta-gradient py-20 lg:py-24">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>

            <div class="relative z-10 max-w-3xl mx-auto px-6 lg:px-12 text-center">
                <h2 class="font-headline text-3xl lg:text-4xl font-bold text-white mb-6">
                    Sẵn sàng trải nghiệm {{ $service['name'] }}?
                </h2>
                <p class="text-blue-100 text-lg mb-10 max-w-xl mx-auto leading-relaxed">
                    Đăng ký trải nghiệm miễn phí hoặc liên hệ tư vấn để chọn gói phù hợp nhất với bạn.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#"
                        class="inline-flex items-center justify-center gap-2 bg-white text-primary px-8 py-4 rounded-xl font-headline font-bold text-base hover:bg-slate-50 transition-all duration-300 active:scale-95 shadow-xl">
                        <span class="material-symbols-outlined">calendar_today</span>
                        Đặt chỗ ngay
                    </a>
                    <a href="/"
                        class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-sm text-white border border-white/25 px-8 py-4 rounded-xl font-headline font-semibold text-base hover:bg-white/20 transition-all duration-300 active:scale-95">
                        <span class="material-symbols-outlined">arrow_back</span>
                        Quay về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer (same as homepage) --}}
    @include('layouts.footer')

@endsection

