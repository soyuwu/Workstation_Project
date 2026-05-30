@php
    try {
        $homeServices = \App\Models\Service::active()->ordered()->get();
    } catch (\Exception $e) {
        $homeServices = collect();
    }

    $fallbackServices = collect([
        [
            'slug' => 'cho-ngoi-linh-hoat',
            'name' => 'Chỗ ngồi linh hoạt',
            'icon' => 'event_seat',
            'badge' => 'Hot Desk',
            'tagline' => 'Hot desk - đến bất kỳ lúc nào, chọn chỗ tùy thích. Bao gồm wifi, ổ cắm và đồ uống.',
            'price' => '50.000đ',
            'price_unit' => 'giờ',
            'booking_type' => 'hourly',
            'detail_image' => 'Images/ghedon.jpg',
        ],
        [
            'slug' => 'cho-ngoi-co-dinh',
            'name' => 'Chỗ ngồi cố định',
            'icon' => 'chair',
            'badge' => 'Dedicated Desk',
            'tagline' => 'Dedicated desk - bàn riêng cố định, tủ khóa cá nhân. Không gian quen thuộc mỗi ngày.',
            'price' => '2.500.000đ',
            'price_unit' => 'tháng',
            'booking_type' => 'monthly',
            'detail_image' => 'Images/Linhhoat.jpg',
        ],
        [
            'slug' => 'phong-lam-viec-rieng',
            'name' => 'Phòng làm việc riêng',
            'icon' => 'corporate_fare',
            'badge' => 'Private Office',
            'tagline' => 'Văn phòng khép kín cho team 2-10 người. Riêng tư, yên tĩnh, phù hợp doanh nghiệp.',
            'price' => '5.000.000đ',
            'price_unit' => 'tháng',
            'booking_type' => 'monthly',
            'detail_image' => 'Images/Vanphong.webp',
        ],
        [
            'slug' => 'phong-hop-tieu-chuan',
            'name' => 'Phòng họp tiêu chuẩn',
            'icon' => 'meeting_room',
            'badge' => 'Meeting Room',
            'tagline' => 'Phòng họp 4-12 người, trang bị màn hình, bảng trắng và hệ thống hội nghị truyền hình.',
            'price' => '200.000đ',
            'price_unit' => 'giờ',
            'booking_type' => 'hourly',
            'detail_image' => 'Images/Phonghoithao.jpg',
        ],
        [
            'slug' => 'khong-gian-su-kien',
            'name' => 'Không gian sự kiện',
            'icon' => 'celebration',
            'badge' => 'Event Space',
            'tagline' => 'Sức chứa 20-100 người, projector, âm thanh chuyên nghiệp. Lý tưởng cho workshop & hội thảo.',
            'price' => '500.000đ',
            'price_unit' => 'giờ',
            'booking_type' => 'hourly',
            'detail_image' => 'Images/khonggian.jpg',
        ],
    ])->map(fn ($service) => (object) $service);

    if ($homeServices->isEmpty()) {
        $homeServices = $fallbackServices;
    }

    $serviceImages = [
        'cho-ngoi-linh-hoat' => 'Images/ghedon.jpg',
        'cho-ngoi-co-dinh' => 'Images/Linhhoat.jpg',
        'phong-lam-viec-rieng' => 'Images/Vanphong.webp',
        'phong-hop-tieu-chuan' => 'Images/Phonghoithao.jpg',
        'khong-gian-su-kien' => 'Images/khonggian.jpg',
    ];

    $serviceBadges = [
        'cho-ngoi-linh-hoat' => 'Hot',
        'phong-lam-viec-rieng' => 'Premium',
    ];
@endphp

<section id="services" class="py-24 lg:py-32 bg-background overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">

        {{-- Section Header --}}
        <div class="reveal text-center max-w-2xl mx-auto mb-16">
            <div
                class="inline-flex items-center gap-2 bg-primary-light text-primary font-headline font-semibold text-sm px-4 py-2 rounded-full mb-6">
                <span class="material-symbols-outlined text-lg">workspace_premium</span>
                Dịch vụ của chúng tôi
            </div>
            <h2 class="font-headline text-4xl lg:text-5xl font-bold text-on-surface mb-6">
                Giải pháp không gian
                <span class="text-primary">đa dạng</span>
            </h2>
            <div class="section-divider mx-auto mb-6"></div>
            <p class="text-slate-500 text-lg">
                Khám phá đầy đủ 5 dịch vụ WorkStation: chỗ ngồi linh hoạt, chỗ ngồi cố định, phòng làm việc riêng,
                phòng họp tiêu chuẩn và không gian sự kiện.
            </p>
        </div>

        {{-- Service Cards Grid --}}
        <div class="reveal-stagger grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 lg:gap-8">
            @foreach ($homeServices as $service)
                @php
                    $image = $serviceImages[$service->slug] ?? $service->detail_image ?? 'Images/khonggian.jpg';
                    $imageUrl = str_starts_with($image, 'http') ? $image : asset($image);
                    $badge = $serviceBadges[$service->slug] ?? null;
                @endphp

                <div class="service-card flex h-full flex-col bg-white rounded-2xl overflow-hidden shadow-[var(--shadow-card)] group">
                    <div class="relative h-52 overflow-hidden">
                        <img src="{{ $imageUrl }}" alt="{{ $service->name }}" class="service-img w-full h-full object-cover">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                        @if ($badge)
                            <div
                                class="absolute top-4 right-4 bg-primary text-white text-xs font-bold px-3 py-1 rounded-full">
                                {{ $badge }}
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-primary">{{ $service->icon }}</span>
                            <h3 class="font-headline font-bold text-lg text-on-surface">{{ $service->name }}</h3>
                        </div>
                        <p class="text-slate-500 text-sm leading-relaxed mb-4">
                            {{ $service->tagline }}
                        </p>
                        <div class="mt-auto flex items-end justify-between gap-3 pt-4 border-t border-slate-100">
                            <div class="min-w-0">
                                <span class="text-2xl font-headline font-bold text-primary">{{ $service->price }}</span>
                                <span class="text-slate-400 text-sm"> / {{ $service->price_unit }}</span>
                            </div>
                            <a href="{{ route('dichvu.detail', $service->slug) }}"
                                class="inline-flex shrink-0 items-center gap-1 text-primary font-semibold text-sm hover:gap-2 transition-all duration-300">
                                Xem chi tiết
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
