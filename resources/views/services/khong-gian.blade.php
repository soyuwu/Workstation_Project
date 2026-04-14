@extends('layouts.topBar')

@section('title', 'Không gian làm việc')

@section('content')
{{-- Hero Banner --}}
<section class="pt-28 pb-16 bg-gradient-to-b from-primary/5 to-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-primary-light text-primary font-headline font-semibold text-sm px-4 py-2 rounded-full mb-6">
                <span class="material-symbols-outlined text-lg">apartment</span>
                Không gian làm việc
            </div>
            <h1 class="font-headline text-4xl lg:text-5xl font-bold text-on-surface mb-6 leading-tight">
                Khám phá các <span class="text-primary">không gian</span> của chúng tôi
            </h1>
            <p class="text-slate-500 text-lg leading-relaxed">
                Từ ghế đơn linh hoạt cho freelancer đến văn phòng riêng cho doanh nghiệp — WorkStation có không gian phù hợp cho mọi nhu cầu.
            </p>
        </div>
    </div>
</section>

{{-- Workspace Grid --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">

        {{-- Filter tabs --}}
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button class="filter-btn active px-6 py-2.5 rounded-full font-headline font-semibold text-sm bg-primary text-white transition-all duration-300 hover:shadow-lg" data-filter="all">Tất cả</button>
            <button class="filter-btn px-6 py-2.5 rounded-full font-headline font-semibold text-sm bg-slate-100 text-slate-600 hover:bg-primary hover:text-white transition-all duration-300" data-filter="flexible">Linh hoạt</button>
            <button class="filter-btn px-6 py-2.5 rounded-full font-headline font-semibold text-sm bg-slate-100 text-slate-600 hover:bg-primary hover:text-white transition-all duration-300" data-filter="group">Nhóm</button>
            <button class="filter-btn px-6 py-2.5 rounded-full font-headline font-semibold text-sm bg-slate-100 text-slate-600 hover:bg-primary hover:text-white transition-all duration-300" data-filter="meeting">Phòng họp</button>
            <button class="filter-btn px-6 py-2.5 rounded-full font-headline font-semibold text-sm bg-slate-100 text-slate-600 hover:bg-primary hover:text-white transition-all duration-300" data-filter="office">Văn phòng</button>
        </div>

        {{-- Workspace Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="workspace-grid">

            {{-- Card 1 --}}
            <div class="workspace-card group bg-white rounded-2xl overflow-hidden shadow-[var(--shadow-card)] hover:shadow-[var(--shadow-card-hover)] transition-all duration-400 hover:-translate-y-2" data-category="flexible">
                <div class="relative h-64 overflow-hidden">
                    <img src="{{ asset('Images/ghedon.jpg') }}" alt="Ghế đơn linh hoạt" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-accent text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                        Phổ biến
                    </div>
                    <div class="absolute bottom-0 inset-x-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-white/80 text-sm">Từ</span>
                        <span class="text-white font-headline font-bold text-2xl ml-1">50.000đ</span>
                        <span class="text-white/80 text-sm">/ giờ</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-primary">event_seat</span>
                        <h3 class="font-headline font-bold text-xl text-on-surface">Ghế đơn linh hoạt</h3>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        Chỗ ngồi hot desk không cố định, không cần đặt trước. Đến bất kỳ lúc nào, chọn chỗ bạn thích và bắt đầu làm việc với đầy đủ tiện nghi.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-5">
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">wifi</span> Wifi
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">power</span> Ổ cắm
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">local_cafe</span> Đồ uống
                        </span>
                    </div>
                    <a href="#" class="inline-flex items-center justify-center w-full gap-2 bg-primary text-white px-6 py-3 rounded-xl font-headline font-semibold text-sm hover:bg-primary-dark transition-all duration-300 active:scale-95">
                        <span class="material-symbols-outlined text-lg">calendar_today</span>
                        Đặt chỗ ngay
                    </a>
                </div>
            </div>

            {{-- Card 2 --}}
            <div class="workspace-card group bg-white rounded-2xl overflow-hidden shadow-[var(--shadow-card)] hover:shadow-[var(--shadow-card-hover)] transition-all duration-400 hover:-translate-y-2" data-category="flexible">
                <div class="relative h-64 overflow-hidden">
                    <img src="{{ asset('Images/ghelinhhoat.jpg') }}" alt="Ghế linh hoạt cao cấp" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute bottom-0 inset-x-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-white/80 text-sm">Từ</span>
                        <span class="text-white font-headline font-bold text-2xl ml-1">80.000đ</span>
                        <span class="text-white/80 text-sm">/ giờ</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-primary">chair</span>
                        <h3 class="font-headline font-bold text-xl text-on-surface">Ghế linh hoạt cao cấp</h3>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        Ghế ergonomic cao cấp với bàn rộng rãi, vách ngăn riêng tư. Phù hợp cho những ai cần sự yên tĩnh và thoải mái tối đa khi làm việc.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-5">
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">wifi</span> Wifi
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">monitor</span> Màn hình phụ
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">lock</span> Tủ khóa
                        </span>
                    </div>
                    <a href="#" class="inline-flex items-center justify-center w-full gap-2 bg-primary text-white px-6 py-3 rounded-xl font-headline font-semibold text-sm hover:bg-primary-dark transition-all duration-300 active:scale-95">
                        <span class="material-symbols-outlined text-lg">calendar_today</span>
                        Đặt chỗ ngay
                    </a>
                </div>
            </div>

            {{-- Card 3 --}}
            <div class="workspace-card group bg-white rounded-2xl overflow-hidden shadow-[var(--shadow-card)] hover:shadow-[var(--shadow-card-hover)] transition-all duration-400 hover:-translate-y-2" data-category="group">
                <div class="relative h-64 overflow-hidden">
                    <img src="{{ asset('Images/Banhocnhom.jpg') }}" alt="Bàn nhóm" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-primary text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                        Team
                    </div>
                    <div class="absolute bottom-0 inset-x-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-white/80 text-sm">Từ</span>
                        <span class="text-white font-headline font-bold text-2xl ml-1">200.000đ</span>
                        <span class="text-white/80 text-sm">/ giờ</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-primary">groups</span>
                        <h3 class="font-headline font-bold text-xl text-on-surface">Bàn nhóm 4-8 người</h3>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        Khu vực bàn lớn dành cho nhóm làm dự án, brainstorm hoặc học nhóm. Trang bị bảng trắng, ổ cắm tập trung và hệ thống đèn riêng.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-5">
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">groups</span> 4-8 người
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">edit_note</span> Bảng trắng
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">power</span> Ổ cắm
                        </span>
                    </div>
                    <a href="#" class="inline-flex items-center justify-center w-full gap-2 bg-primary text-white px-6 py-3 rounded-xl font-headline font-semibold text-sm hover:bg-primary-dark transition-all duration-300 active:scale-95">
                        <span class="material-symbols-outlined text-lg">calendar_today</span>
                        Đặt chỗ ngay
                    </a>
                </div>
            </div>

            {{-- Card 4 --}}
            <div class="workspace-card group bg-white rounded-2xl overflow-hidden shadow-[var(--shadow-card)] hover:shadow-[var(--shadow-card-hover)] transition-all duration-400 hover:-translate-y-2" data-category="meeting">
                <div class="relative h-64 overflow-hidden">
                    <img src="{{ asset('Images/Phonghoithao.jpg') }}" alt="Phòng hội thảo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                        Premium
                    </div>
                    <div class="absolute bottom-0 inset-x-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-white/80 text-sm">Từ</span>
                        <span class="text-white font-headline font-bold text-2xl ml-1">500.000đ</span>
                        <span class="text-white/80 text-sm">/ giờ</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-primary">meeting_room</span>
                        <h3 class="font-headline font-bold text-xl text-on-surface">Phòng hội thảo</h3>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        Phòng họp lớn sức chứa 20-30 người, trang bị projector 4K, hệ thống âm thanh surround, bảng trắng tương tác và hệ thống cách âm chuyên nghiệp.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-5">
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">tv</span> Projector 4K
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">volume_up</span> Âm thanh
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">groups</span> 20-30 người
                        </span>
                    </div>
                    <a href="#" class="inline-flex items-center justify-center w-full gap-2 bg-primary text-white px-6 py-3 rounded-xl font-headline font-semibold text-sm hover:bg-primary-dark transition-all duration-300 active:scale-95">
                        <span class="material-symbols-outlined text-lg">calendar_today</span>
                        Đặt chỗ ngay
                    </a>
                </div>
            </div>

            {{-- Card 5 --}}
            <div class="workspace-card group bg-white rounded-2xl overflow-hidden shadow-[var(--shadow-card)] hover:shadow-[var(--shadow-card-hover)] transition-all duration-400 hover:-translate-y-2" data-category="office">
                <div class="relative h-64 overflow-hidden">
                    <img src="{{ asset('Images/Vanphong.webp') }}" alt="Văn phòng riêng" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute bottom-0 inset-x-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-white/80 text-sm">Từ</span>
                        <span class="text-white font-headline font-bold text-2xl ml-1">5.000.000đ</span>
                        <span class="text-white/80 text-sm">/ tháng</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-primary">corporate_fare</span>
                        <h3 class="font-headline font-bold text-xl text-on-surface">Văn phòng riêng</h3>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        Văn phòng khép kín cho team 2-10 người. Có khóa riêng, bàn ghế cao cấp, điều hòa riêng. Phù hợp doanh nghiệp cần sự riêng tư và chuyên nghiệp.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-5">
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">lock</span> Khóa riêng
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">ac_unit</span> Điều hòa
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">groups</span> 2-10 người
                        </span>
                    </div>
                    <a href="#" class="inline-flex items-center justify-center w-full gap-2 bg-primary text-white px-6 py-3 rounded-xl font-headline font-semibold text-sm hover:bg-primary-dark transition-all duration-300 active:scale-95">
                        <span class="material-symbols-outlined text-lg">call</span>
                        Liên hệ tư vấn
                    </a>
                </div>
            </div>

            {{-- Card 6 --}}
            <div class="workspace-card group bg-white rounded-2xl overflow-hidden shadow-[var(--shadow-card)] hover:shadow-[var(--shadow-card-hover)] transition-all duration-400 hover:-translate-y-2" data-category="group">
                <div class="relative h-64 overflow-hidden">
                    <img src="{{ asset('Images/khonggian.jpg') }}" alt="Không gian sáng tạo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-emerald-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                        Mới
                    </div>
                    <div class="absolute bottom-0 inset-x-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-white/80 text-sm">Từ</span>
                        <span class="text-white font-headline font-bold text-2xl ml-1">150.000đ</span>
                        <span class="text-white/80 text-sm">/ giờ</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-primary">lightbulb</span>
                        <h3 class="font-headline font-bold text-xl text-on-surface">Không gian sáng tạo</h3>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        Khu vực mở với bàn ghế linh hoạt, bảng tường sáng tạo và cây xanh. Thiết kế đặc biệt để khơi nguồn ý tưởng và thúc đẩy sự hợp tác nhóm.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-5">
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">nature</span> Cây xanh
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">edit_note</span> Bảng tường
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">
                            <span class="material-symbols-outlined text-sm">local_cafe</span> Đồ uống
                        </span>
                    </div>
                    <a href="#" class="inline-flex items-center justify-center w-full gap-2 bg-primary text-white px-6 py-3 rounded-xl font-headline font-semibold text-sm hover:bg-primary-dark transition-all duration-300 active:scale-95">
                        <span class="material-symbols-outlined text-lg">calendar_today</span>
                        Đặt chỗ ngay
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Filter Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.workspace-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button
            filterBtns.forEach(b => {
                b.classList.remove('bg-primary', 'text-white');
                b.classList.add('bg-slate-100', 'text-slate-600');
            });
            btn.classList.remove('bg-slate-100', 'text-slate-600');
            btn.classList.add('bg-primary', 'text-white');

            const filter = btn.dataset.filter;

            cards.forEach(card => {
                if (filter === 'all' || card.dataset.category === filter) {
                    card.style.display = '';
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                        card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    }, 50);
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Navbar scroll — dark style on sub-pages
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
