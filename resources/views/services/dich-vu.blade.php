@extends('layouts.topBar')

@section('title', 'Danh mục dịch vụ')

@section('content')
{{-- Hero Banner --}}
<section class="pt-28 pb-16 bg-gradient-to-b from-primary/5 to-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-primary-light text-primary font-headline font-semibold text-sm px-4 py-2 rounded-full mb-6">
                <span class="material-symbols-outlined text-lg">workspace_premium</span>
                Danh mục dịch vụ
            </div>
            <h1 class="font-headline text-4xl lg:text-5xl font-bold text-on-surface mb-6 leading-tight">
                Dịch vụ <span class="text-primary">trọn gói</span> cho doanh nghiệp
            </h1>
            <p class="text-slate-500 text-lg leading-relaxed">
                Ngoài không gian làm việc, WorkStation còn cung cấp đa dạng dịch vụ hỗ trợ giúp bạn tập trung vào công việc kinh doanh.
            </p>
        </div>
    </div>
</section>

{{-- Service Categories --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            {{-- Service 1: Cho thuê bàn ghế --}}
            <div class="group bg-white rounded-2xl p-8 border border-slate-100 hover:border-primary/20 hover:shadow-[var(--shadow-card-hover)] transition-all duration-400 hover:-translate-y-2">
                <div class="w-16 h-16 bg-primary-light rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                    <span class="material-symbols-outlined text-primary group-hover:text-white text-3xl transition-colors duration-300">desk</span>
                </div>
                <h3 class="font-headline font-bold text-xl text-on-surface mb-3">Cho thuê chỗ ngồi</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-5">
                    Đa dạng loại hình: ghế đơn, bàn nhóm, bàn cố định theo tháng. Linh hoạt theo giờ, ngày hoặc tháng với giá ưu đãi cho gói dài hạn.
                </p>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        Ghế đơn linh hoạt từ 50.000đ/giờ
                    </li>
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        Bàn nhóm 4-8 người từ 200.000đ/giờ
                    </li>
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        Gói tháng giảm đến 30%
                    </li>
                </ul>
                <a href="{{ route('khongGian') }}" class="inline-flex items-center gap-1 text-primary font-semibold text-sm hover:gap-2 transition-all duration-300">
                    Xem chi tiết
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>

            {{-- Service 2: Phòng họp --}}
            <div class="group bg-white rounded-2xl p-8 border border-slate-100 hover:border-primary/20 hover:shadow-[var(--shadow-card-hover)] transition-all duration-400 hover:-translate-y-2">
                <div class="w-16 h-16 bg-primary-light rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                    <span class="material-symbols-outlined text-primary group-hover:text-white text-3xl transition-colors duration-300">meeting_room</span>
                </div>
                <h3 class="font-headline font-bold text-xl text-on-surface mb-3">Phòng họp & Hội thảo</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-5">
                    Phòng họp trang bị đầy đủ từ nhỏ (4 người) đến lớn (30 người). Projector 4K, hệ thống âm thanh, video conferencing sẵn sàng.
                </p>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        Phòng họp nhỏ 4-8 người
                    </li>
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        Phòng hội thảo 20-30 người
                    </li>
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        Thiết bị trình chiếu & âm thanh
                    </li>
                </ul>
                <a href="{{ route('khongGian') }}" class="inline-flex items-center gap-1 text-primary font-semibold text-sm hover:gap-2 transition-all duration-300">
                    Xem chi tiết
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>

            {{-- Service 3: Văn phòng ảo --}}
            <div class="group bg-white rounded-2xl p-8 border border-slate-100 hover:border-primary/20 hover:shadow-[var(--shadow-card-hover)] transition-all duration-400 hover:-translate-y-2">
                <div class="w-16 h-16 bg-primary-light rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                    <span class="material-symbols-outlined text-primary group-hover:text-white text-3xl transition-colors duration-300">cloud</span>
                </div>
                <h3 class="font-headline font-bold text-xl text-on-surface mb-3">Văn phòng ảo</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-5">
                    Địa chỉ đăng ký kinh doanh uy tín, nhận thư & bưu phẩm, dịch vụ tiếp khách chuyên nghiệp — không cần thuê văn phòng vật lý.
                </p>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        Địa chỉ đăng ký kinh doanh
                    </li>
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        Nhận thư & bưu phẩm
                    </li>
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        Tiếp khách chuyên nghiệp
                    </li>
                </ul>
                <a href="#" class="inline-flex items-center gap-1 text-primary font-semibold text-sm hover:gap-2 transition-all duration-300">
                    Liên hệ tư vấn
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>

            {{-- Service 4: Sự kiện --}}
            <div class="group bg-white rounded-2xl p-8 border border-slate-100 hover:border-primary/20 hover:shadow-[var(--shadow-card-hover)] transition-all duration-400 hover:-translate-y-2">
                <div class="w-16 h-16 bg-accent-light rounded-2xl flex items-center justify-center mb-6 group-hover:bg-accent group-hover:text-white transition-colors duration-300">
                    <span class="material-symbols-outlined text-accent group-hover:text-white text-3xl transition-colors duration-300">celebration</span>
                </div>
                <h3 class="font-headline font-bold text-xl text-on-surface mb-3">Tổ chức sự kiện</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-5">
                    Hỗ trợ tổ chức workshop, seminar, networking event tại không gian WorkStation. Đội ngũ chuyên nghiệp lo liệu từ A đến Z.
                </p>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-accent text-lg">check_circle</span>
                        Workshop & Seminar
                    </li>
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-accent text-lg">check_circle</span>
                        Networking Event
                    </li>
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-accent text-lg">check_circle</span>
                        Hỗ trợ setup & catering
                    </li>
                </ul>
                <a href="#" class="inline-flex items-center gap-1 text-accent font-semibold text-sm hover:gap-2 transition-all duration-300">
                    Liên hệ tư vấn
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>

            {{-- Service 5: In ấn --}}
            <div class="group bg-white rounded-2xl p-8 border border-slate-100 hover:border-primary/20 hover:shadow-[var(--shadow-card-hover)] transition-all duration-400 hover:-translate-y-2">
                <div class="w-16 h-16 bg-primary-light rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                    <span class="material-symbols-outlined text-primary group-hover:text-white text-3xl transition-colors duration-300">print</span>
                </div>
                <h3 class="font-headline font-bold text-xl text-on-surface mb-3">In ấn & Photocopy</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-5">
                    Dịch vụ in ấn, photocopy, scan tài liệu tại chỗ. Máy in màu laser chất lượng cao, nhanh chóng và giá hợp lý.
                </p>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        In đen trắng: 500đ/trang
                    </li>
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        In màu: 2.000đ/trang
                    </li>
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                        Scan & photocopy miễn phí (gói tháng)
                    </li>
                </ul>
                <a href="#" class="inline-flex items-center gap-1 text-primary font-semibold text-sm hover:gap-2 transition-all duration-300">
                    Xem bảng giá
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>

            {{-- Service 6: Đồ uống --}}
            <div class="group bg-white rounded-2xl p-8 border border-slate-100 hover:border-primary/20 hover:shadow-[var(--shadow-card-hover)] transition-all duration-400 hover:-translate-y-2">
                <div class="w-16 h-16 bg-accent-light rounded-2xl flex items-center justify-center mb-6 group-hover:bg-accent group-hover:text-white transition-colors duration-300">
                    <span class="material-symbols-outlined text-accent group-hover:text-white text-3xl transition-colors duration-300">local_cafe</span>
                </div>
                <h3 class="font-headline font-bold text-xl text-on-surface mb-3">Café & Đồ uống</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-5">
                    Quầy café tại chỗ với đa dạng đồ uống: Espresso, Americano, Latte, trà sen, nước ép. Miễn phí cho thành viên gói tháng.
                </p>
                <ul class="space-y-2 mb-6">
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-accent text-lg">check_circle</span>
                        Cà phê specialty
                    </li>
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-accent text-lg">check_circle</span>
                        Trà & nước ép tươi
                    </li>
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <span class="material-symbols-outlined text-accent text-lg">check_circle</span>
                        Miễn phí cho gói tháng
                    </li>
                </ul>
                <a href="#" class="inline-flex items-center gap-1 text-accent font-semibold text-sm hover:gap-2 transition-all duration-300">
                    Xem menu
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-gradient-to-r from-primary to-primary-dark">
    <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center">
        <h2 class="font-headline text-3xl lg:text-4xl font-bold text-white mb-4">
            Cần tư vấn dịch vụ phù hợp?
        </h2>
        <p class="text-blue-100 text-lg mb-8">
            Liên hệ để được tư vấn miễn phí. Đội ngũ WorkStation sẽ giúp bạn tìm giải pháp tối ưu nhất.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="tel:0901234567" class="inline-flex items-center justify-center gap-2 bg-white text-primary px-8 py-4 rounded-xl font-headline font-bold text-base hover:bg-slate-50 transition-all duration-300 active:scale-95 shadow-xl">
                <span class="material-symbols-outlined">call</span>
                090 123 4567
            </a>
            <a href="/" class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-sm text-white border border-white/25 px-8 py-4 rounded-xl font-headline font-semibold text-base hover:bg-white/20 transition-all duration-300 active:scale-95">
                <span class="material-symbols-outlined">home</span>
                Về trang chủ
            </a>
        </div>
    </div>
</section>

{{-- Navbar script for sub-pages --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nav = document.querySelector('.ws-nav');
    if (nav) {
        nav.classList.add('bg-white/95', 'backdrop-blur-xl', 'shadow-md');
        nav.classList.remove('bg-transparent');
        const navLinks = nav.querySelectorAll('.ws-nav-link');
        const projectTitle = nav.querySelector('.project-title');
        navLinks.forEach(link => {
            link.classList.remove('text-white', 'hover:text-blue-200', 'after:bg-white');
            link.classList.add('text-slate-600', 'hover:text-primary', 'after:bg-primary');
        });
        if (projectTitle) {
            projectTitle.classList.remove('text-white');
            projectTitle.classList.add('text-on-surface');
        }
        const authLinks = nav.querySelectorAll('a[href*="logIn"], a[href*="register"]');
        authLinks.forEach(link => {
            link.classList.remove('text-white/80', 'hover:text-white');
            link.classList.add('text-slate-600', 'hover:text-slate-900');
        });
        const ctaBtn = nav.querySelector('a[href="#"]');
        if (ctaBtn) {
            ctaBtn.classList.remove('bg-white', 'text-primary', 'hover:bg-slate-100');
            ctaBtn.classList.add('bg-primary', 'text-white', 'hover:opacity-90');
        }
        const menuToggle = nav.querySelector('#menu-toggle');
        if (menuToggle) {
            menuToggle.classList.remove('text-white');
            menuToggle.classList.add('text-slate-700');
        }
    }
});
</script>
@endsection
