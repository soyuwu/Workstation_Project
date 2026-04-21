@extends('layouts.app')

@section('title', 'Danh mục dịch vụ')
@section('nav-mode', 'solid')

@php
    $services = [
        [
            'icon_container_classes' => 'bg-primary-light group-hover:bg-primary',
            'icon_classes' => 'text-primary group-hover:text-white',
            'icon' => 'desk',
            'title' => 'Cho thuê chỗ ngồi',
            'description' => 'Đa dạng loại hình: ghế đơn, bàn nhóm, bàn cố định theo tháng. Linh hoạt theo giờ, ngày hoặc tháng với giá ưu đãi cho gói dài hạn.',
            'features' => [
                'Ghế đơn linh hoạt từ 50.000đ/giờ',
                'Bàn nhóm 4-8 người từ 200.000đ/giờ',
                'Gói tháng giảm đến 30%',
            ],
            'link' => route('khongGian'),
            'link_label' => 'Xem chi tiết',
            'link_classes' => 'text-primary',
            'check_classes' => 'text-primary',
        ],
        [
            'icon_container_classes' => 'bg-primary-light group-hover:bg-primary',
            'icon_classes' => 'text-primary group-hover:text-white',
            'icon' => 'meeting_room',
            'title' => 'Phòng họp & Hội thảo',
            'description' => 'Phòng họp trang bị đầy đủ từ nhỏ (4 người) đến lớn (30 người). Projector 4K, hệ thống âm thanh, video conferencing sẵn sàng.',
            'features' => [
                'Phòng họp nhỏ 4-8 người',
                'Phòng hội thảo 20-30 người',
                'Thiết bị trình chiếu & âm thanh',
            ],
            'link' => route('khongGian'),
            'link_label' => 'Xem chi tiết',
            'link_classes' => 'text-primary',
            'check_classes' => 'text-primary',
        ],
        [
            'icon_container_classes' => 'bg-primary-light group-hover:bg-primary',
            'icon_classes' => 'text-primary group-hover:text-white',
            'icon' => 'cloud',
            'title' => 'Văn phòng ảo',
            'description' => 'Địa chỉ đăng ký kinh doanh uy tín, nhận thư & bưu phẩm, dịch vụ tiếp khách chuyên nghiệp mà không cần thuê văn phòng vật lý.',
            'features' => [
                'Địa chỉ đăng ký kinh doanh',
                'Nhận thư & bưu phẩm',
                'Tiếp khách chuyên nghiệp',
            ],
            'link' => '#',
            'link_label' => 'Liên hệ tư vấn',
            'link_classes' => 'text-primary',
            'check_classes' => 'text-primary',
        ],
        [
            'icon_container_classes' => 'bg-accent-light group-hover:bg-accent',
            'icon_classes' => 'text-accent group-hover:text-white',
            'icon' => 'celebration',
            'title' => 'Tổ chức sự kiện',
            'description' => 'Hỗ trợ tổ chức workshop, seminar, networking event tại không gian WorkStation với đội ngũ chuyên nghiệp lo liệu từ A đến Z.',
            'features' => [
                'Workshop & Seminar',
                'Networking Event',
                'Hỗ trợ setup & catering',
            ],
            'link' => '#',
            'link_label' => 'Liên hệ tư vấn',
            'link_classes' => 'text-accent',
            'check_classes' => 'text-accent',
        ],
        [
            'icon_container_classes' => 'bg-primary-light group-hover:bg-primary',
            'icon_classes' => 'text-primary group-hover:text-white',
            'icon' => 'print',
            'title' => 'In ấn & Photocopy',
            'description' => 'Dịch vụ in ấn, photocopy, scan tài liệu tại chỗ. Máy in màu laser chất lượng cao, nhanh chóng và giá hợp lý.',
            'features' => [
                'In đen trắng: 500đ/trang',
                'In màu: 2.000đ/trang',
                'Scan & photocopy miễn phí cho gói tháng',
            ],
            'link' => '#',
            'link_label' => 'Xem bảng giá',
            'link_classes' => 'text-primary',
            'check_classes' => 'text-primary',
        ],
        [
            'icon_container_classes' => 'bg-accent-light group-hover:bg-accent',
            'icon_classes' => 'text-accent group-hover:text-white',
            'icon' => 'local_cafe',
            'title' => 'Café & Đồ uống',
            'description' => 'Quầy café tại chỗ với đa dạng đồ uống: Espresso, Americano, Latte, trà sen và nước ép. Miễn phí cho thành viên gói tháng.',
            'features' => [
                'Cà phê specialty',
                'Trà & nước ép tươi',
                'Miễn phí cho gói tháng',
            ],
            'link' => '#',
            'link_label' => 'Xem menu',
            'link_classes' => 'text-accent',
            'check_classes' => 'text-accent',
        ],
    ];
@endphp

@section('content')
    <x-common.sub-page-hero
        icon="workspace_premium"
        subtitle="Danh mục dịch vụ"
        :title="'Dịch vụ <span class=&quot;text-primary&quot;>trọn gói</span> cho doanh nghiệp'"
        description="Ngoài không gian làm việc, WorkStation còn cung cấp đa dạng dịch vụ hỗ trợ giúp bạn tập trung vào công việc kinh doanh."
    />

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-12">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <article class="group rounded-2xl border border-slate-100 bg-white p-8 transition-all duration-300 hover:-translate-y-2 hover:border-primary/20 hover:shadow-[var(--shadow-card-hover)]">
                        <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl transition-colors duration-300 {{ $service['icon_container_classes'] }}">
                            <span class="material-symbols-outlined text-3xl transition-colors duration-300 {{ $service['icon_classes'] }}">{{ $service['icon'] }}</span>
                        </div>

                        <h3 class="mb-3 font-headline text-xl font-bold text-on-surface">{{ $service['title'] }}</h3>
                        <p class="mb-5 text-sm leading-relaxed text-slate-500">{{ $service['description'] }}</p>

                        <ul class="mb-6 space-y-2">
                            @foreach ($service['features'] as $feature)
                                <li class="flex items-center gap-2 text-sm text-slate-600">
                                    <span class="material-symbols-outlined text-lg {{ $service['check_classes'] }}">check_circle</span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ $service['link'] }}" class="inline-flex items-center gap-1 text-sm font-semibold transition-all duration-300 hover:gap-2 {{ $service['link_classes'] }}">
                            {{ $service['link_label'] }}
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <x-common.cta-contact />
@endsection
