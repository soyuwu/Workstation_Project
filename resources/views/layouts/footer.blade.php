@php
    try {
        $footerServices = \App\Models\Service::active()->ordered()->take(5)->get();
    } catch (\Exception $e) {
        $footerServices = collect();
    }
@endphp
<footer class="bg-inverse-surface pb-8 pt-16 text-slate-300">
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
        <div class="mb-12 grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <h3 class="mb-4 font-headline text-2xl font-bold text-white">WORKSTATION</h3>
                <p class="mb-6 leading-relaxed text-slate-400">
                    Hệ thống không gian làm việc chung hiện đại dành cho startup, freelancer và doanh nghiệp cần một nơi làm việc linh hoạt, chuyên nghiệp tại TP.HCM.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-colors duration-300 hover:bg-primary">
                        <i class="fa-brands fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-colors duration-300 hover:bg-primary">
                        <i class="fa-brands fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-colors duration-300 hover:bg-primary">
                        <i class="fa-brands fa-linkedin-in text-sm"></i>
                    </a>
                    <a href="#" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition-colors duration-300 hover:bg-primary">
                        <i class="fa-brands fa-tiktok text-sm"></i>
                    </a>
                </div>
            </div>

            <div>
                <h4 class="mb-4 font-headline font-semibold text-white">Liên kết nhanh</h4>
                <ul class="space-y-3">
                    <li><a href="{{ url('/') }}" class="text-slate-400 transition-colors duration-300 hover:text-white">Trang chủ</a></li>
                    <li><a href="{{ url('/#about') }}" class="text-slate-400 transition-colors duration-300 hover:text-white">Giới thiệu</a></li>
                    <li><a href="{{ route('khongGian') }}" class="text-slate-400 transition-colors duration-300 hover:text-white">Không gian làm việc</a></li>
                    <li><a href="{{ route('dichVu') }}" class="text-slate-400 transition-colors duration-300 hover:text-white">Danh mục dịch vụ</a></li>
                    <li><a href="{{ url('/#reviews') }}" class="text-slate-400 transition-colors duration-300 hover:text-white">Đánh giá</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 font-headline font-semibold text-white">Dịch vụ nổi bật</h4>
                <ul class="space-y-3">
                    @if($footerServices->isNotEmpty())
                        @foreach($footerServices as $srv)
                            <li><a href="{{ route('dichvu.detail', $srv->slug) }}" class="text-slate-400 transition-colors duration-300 hover:text-white">{{ $srv->name }}</a></li>
                        @endforeach
                    @else
                        <li><a href="{{ route('khongGian') }}" class="text-slate-400 transition-colors duration-300 hover:text-white">Ghế đơn linh hoạt</a></li>
                        <li><a href="{{ route('khongGian') }}" class="text-slate-400 transition-colors duration-300 hover:text-white">Bàn nhóm</a></li>
                        <li><a href="{{ route('khongGian') }}" class="text-slate-400 transition-colors duration-300 hover:text-white">Phòng họp</a></li>
                        <li><a href="{{ route('khongGian') }}" class="text-slate-400 transition-colors duration-300 hover:text-white">Văn phòng riêng</a></li>
                        <li><a href="{{ route('dichVu') }}" class="text-slate-400 transition-colors duration-300 hover:text-white">Sự kiện & Workshop</a></li>
                    @endif
                </ul>
            </div>

            <div>
                <h4 class="mb-4 font-headline font-semibold text-white">Liên hệ</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined mt-0.5 text-xl text-primary">location_on</span>
                        <span class="text-slate-400">Khu phố 6, phường Linh Trung, TP. Thủ Đức, TP.HCM</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-xl text-primary">phone</span>
                        <a href="tel:0901234567" class="text-slate-400 transition-colors duration-300 hover:text-white">090 123 4567</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-xl text-primary">mail</span>
                        <a href="mailto:hello@workstation.vn" class="text-slate-400 transition-colors duration-300 hover:text-white">hello@workstation.vn</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-xl text-primary">schedule</span>
                        <span class="text-slate-400">T2 - CN: 7:00 - 22:00</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 md:flex-row">
            <p class="text-sm text-slate-500">
                © 2026 WorkStation. All rights reserved.
            </p>
            <div class="flex gap-6 text-sm">
                <a href="#" class="text-slate-500 transition-colors hover:text-white">Chính sách bảo mật</a>
                <a href="#" class="text-slate-500 transition-colors hover:text-white">Điều khoản sử dụng</a>
            </div>
        </div>
    </div>
</footer>
