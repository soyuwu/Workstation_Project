@extends('layouts.app')

@section('title', 'Không gian làm việc')
@section('nav-mode', 'solid')

@php
    $filters = [
        'all' => 'Tất cả',
        'flexible' => 'Linh hoạt',
        'group' => 'Nhóm',
        'meeting' => 'Phòng họp',
        'office' => 'Văn phòng',
    ];

    $workspaces = [
        [
            'category' => 'flexible',
            'image' => asset('Images/ghedon.jpg'),
            'badge' => 'Phổ biến',
            'badge_classes' => 'bg-accent text-white',
            'price' => '50.000đ',
            'unit' => '/ giờ',
            'icon' => 'event_seat',
            'title' => 'Ghế đơn linh hoạt',
            'description' => 'Chỗ ngồi hot desk không cố định, không cần đặt trước. Đến bất kỳ lúc nào, chọn chỗ bạn thích và bắt đầu làm việc với đầy đủ tiện nghi.',
            'features' => [
                ['icon' => 'wifi', 'label' => 'Wifi'],
                ['icon' => 'power', 'label' => 'Ổ cắm'],
                ['icon' => 'local_cafe', 'label' => 'Đồ uống'],
            ],
            'action_icon' => 'calendar_today',
            'action_label' => 'Đặt chỗ ngay',
        ],
        [
            'category' => 'flexible',
            'image' => asset('Images/ghelinhhoat.jpg'),
            'badge' => null,
            'badge_classes' => '',
            'price' => '80.000đ',
            'unit' => '/ giờ',
            'icon' => 'chair',
            'title' => 'Ghế linh hoạt cao cấp',
            'description' => 'Ghế ergonomic cao cấp với bàn rộng rãi, vách ngăn riêng tư. Phù hợp cho những ai cần sự yên tĩnh và thoải mái tối đa khi làm việc.',
            'features' => [
                ['icon' => 'wifi', 'label' => 'Wifi'],
                ['icon' => 'monitor', 'label' => 'Màn hình phụ'],
                ['icon' => 'lock', 'label' => 'Tủ khóa'],
            ],
            'action_icon' => 'calendar_today',
            'action_label' => 'Đặt chỗ ngay',
        ],
        [
            'category' => 'group',
            'image' => asset('Images/Banhocnhom.jpg'),
            'badge' => 'Team',
            'badge_classes' => 'bg-primary text-white',
            'price' => '200.000đ',
            'unit' => '/ giờ',
            'icon' => 'groups',
            'title' => 'Bàn nhóm 4-8 người',
            'description' => 'Khu vực bàn lớn dành cho nhóm làm dự án, brainstorm hoặc học nhóm. Trang bị bảng trắng, ổ cắm tập trung và hệ thống đèn riêng.',
            'features' => [
                ['icon' => 'groups', 'label' => '4-8 người'],
                ['icon' => 'edit_note', 'label' => 'Bảng trắng'],
                ['icon' => 'power', 'label' => 'Ổ cắm'],
            ],
            'action_icon' => 'calendar_today',
            'action_label' => 'Đặt chỗ ngay',
        ],
        [
            'category' => 'meeting',
            'image' => asset('Images/Phonghoithao.jpg'),
            'badge' => 'Premium',
            'badge_classes' => 'bg-amber-500 text-white',
            'price' => '500.000đ',
            'unit' => '/ giờ',
            'icon' => 'meeting_room',
            'title' => 'Phòng hội thảo',
            'description' => 'Phòng họp lớn sức chứa 20-30 người, trang bị projector 4K, hệ thống âm thanh surround, bảng trắng tương tác và hệ thống cách âm chuyên nghiệp.',
            'features' => [
                ['icon' => 'tv', 'label' => 'Projector 4K'],
                ['icon' => 'volume_up', 'label' => 'Âm thanh'],
                ['icon' => 'groups', 'label' => '20-30 người'],
            ],
            'action_icon' => 'calendar_today',
            'action_label' => 'Đặt chỗ ngay',
        ],
        [
            'category' => 'office',
            'image' => asset('Images/Vanphong.webp'),
            'badge' => null,
            'badge_classes' => '',
            'price' => '5.000.000đ',
            'unit' => '/ tháng',
            'icon' => 'corporate_fare',
            'title' => 'Văn phòng riêng',
            'description' => 'Văn phòng khép kín cho team 2-10 người. Có khóa riêng, bàn ghế cao cấp, điều hòa riêng. Phù hợp doanh nghiệp cần sự riêng tư và chuyên nghiệp.',
            'features' => [
                ['icon' => 'lock', 'label' => 'Khóa riêng'],
                ['icon' => 'ac_unit', 'label' => 'Điều hòa'],
                ['icon' => 'groups', 'label' => '2-10 người'],
            ],
            'action_icon' => 'call',
            'action_label' => 'Liên hệ tư vấn',
        ],
        [
            'category' => 'group',
            'image' => asset('Images/khonggian.jpg'),
            'badge' => 'Mới',
            'badge_classes' => 'bg-emerald-500 text-white',
            'price' => '150.000đ',
            'unit' => '/ giờ',
            'icon' => 'lightbulb',
            'title' => 'Không gian sáng tạo',
            'description' => 'Khu vực mở với bàn ghế linh hoạt, bảng tường sáng tạo và cây xanh. Thiết kế đặc biệt để khơi nguồn ý tưởng và thúc đẩy sự hợp tác nhóm.',
            'features' => [
                ['icon' => 'nature', 'label' => 'Cây xanh'],
                ['icon' => 'edit_note', 'label' => 'Bảng tường'],
                ['icon' => 'local_cafe', 'label' => 'Đồ uống'],
            ],
            'action_icon' => 'calendar_today',
            'action_label' => 'Đặt chỗ ngay',
        ],
    ];
@endphp

@section('content')
    <x-common.sub-page-hero
        icon="apartment"
        subtitle="Không gian làm việc"
        :title="'Khám phá các <span class=&quot;text-primary&quot;>không gian</span> của chúng tôi'"
        description="Từ ghế đơn linh hoạt cho freelancer đến văn phòng riêng cho doanh nghiệp, WorkStation có không gian phù hợp cho mọi nhu cầu."
    />

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-12">
            <div class="mb-12 flex flex-wrap justify-center gap-3">
                @foreach ($filters as $key => $label)
                    <button
                        class="filter-btn rounded-full px-6 py-2.5 text-sm font-headline font-semibold transition-all duration-300 {{ $key === 'all' ? 'bg-primary text-white shadow-lg' : 'bg-slate-100 text-slate-600 hover:bg-primary hover:text-white' }}"
                        data-filter="{{ $key }}"
                        type="button"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div id="workspace-grid" class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($workspaces as $workspace)
                    <article
                        class="workspace-card group overflow-hidden rounded-2xl bg-white shadow-[var(--shadow-card)] transition-all duration-300 hover:-translate-y-2 hover:shadow-[var(--shadow-card-hover)]"
                        data-category="{{ $workspace['category'] }}"
                    >
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $workspace['image'] }}" alt="{{ $workspace['title'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">

                            @if ($workspace['badge'])
                                <div class="absolute left-4 top-4 rounded-full px-3 py-1.5 text-xs font-bold shadow-lg {{ $workspace['badge_classes'] }}">
                                    {{ $workspace['badge'] }}
                                </div>
                            @endif

                            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <span class="text-sm text-white/80">Từ</span>
                                <span class="ml-1 font-headline text-2xl font-bold text-white">{{ $workspace['price'] }}</span>
                                <span class="text-sm text-white/80">{{ $workspace['unit'] }}</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">{{ $workspace['icon'] }}</span>
                                <h3 class="font-headline text-xl font-bold text-on-surface">{{ $workspace['title'] }}</h3>
                            </div>

                            <p class="mb-4 text-sm leading-relaxed text-slate-500">
                                {{ $workspace['description'] }}
                            </p>

                            <div class="mb-5 flex flex-wrap gap-2">
                                @foreach ($workspace['features'] as $feature)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1.5 text-xs text-slate-600">
                                        <span class="material-symbols-outlined text-sm">{{ $feature['icon'] }}</span>
                                        {{ $feature['label'] }}
                                    </span>
                                @endforeach
                            </div>

                            <a href="#" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-headline font-semibold text-white transition-all duration-300 hover:bg-primary-dark active:scale-95">
                                <span class="material-symbols-outlined text-lg">{{ $workspace['action_icon'] }}</span>
                                {{ $workspace['action_label'] }}
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <x-common.cta-contact />
@endsection
