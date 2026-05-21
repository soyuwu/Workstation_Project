@extends('layouts.app')

@section('title', 'Đặt chỗ ngay')
@section('nav-mode', 'solid')

@section('content')
    <x-common.sub-page-hero
        icon="touch_app"
        subtitle="Hệ thống đặt chỗ"
        :title="'Chọn dịch vụ bạn muốn <span class=&quot;text-primary&quot;>trải nghiệm</span>'"
        description="Chọn không gian làm việc hoặc phòng họp phù hợp nhất với nhu cầu của bạn để tiến hành đặt chỗ."
    />

    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-12">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <article class="group relative overflow-hidden rounded-3xl bg-white shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-[var(--shadow-card-hover)]">
                        <!-- Thẻ trang trí góc -->
                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-primary/5 transition-transform duration-500 group-hover:scale-150"></div>
                        
                        <div class="p-8">
                            <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-light text-primary transition-all duration-300 group-hover:bg-primary group-hover:text-white">
                                <span class="material-symbols-outlined text-3xl">{{ $service->icon }}</span>
                            </div>

                            <h3 class="mb-3 font-headline text-xl font-bold text-on-surface">{{ $service->name }}</h3>
                            <p class="mb-6 text-sm leading-relaxed text-slate-500 line-clamp-2">{{ $service->booking_desc }}</p>

                            <div class="mb-6 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                    <span class="material-symbols-outlined text-[14px]">
                                        {{ $service->booking_type === 'monthly' ? 'calendar_month' : 'schedule' }}
                                    </span>
                                    {{ $service->booking_type === 'monthly' ? 'Thuê theo tháng' : 'Thuê theo giờ/ngày' }}
                                </span>
                            </div>

                            <a href="{{ route($service->booking_type === 'monthly' ? 'booking.monthly' : 'booking.hourly', $service->slug) }}" 
                               class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-700 transition-all duration-300 group-hover:bg-primary group-hover:text-white">
                                Đặt ngay
                                <span class="material-symbols-outlined text-base transition-transform duration-300 group-hover:translate-x-1">arrow_forward</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
