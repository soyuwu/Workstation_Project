<section id="about" class="py-24 lg:py-32 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">

            {{-- Left: Image --}}
            <div class="reveal-left relative">
                <div class="relative rounded-2xl overflow-hidden shadow-[var(--shadow-card)]">
                    <img src="{{ asset('Images/Linhhoat.jpg') }}" alt="WorkStation - Không gian làm việc linh hoạt"
                        class="w-full h-[500px] object-cover">
                    {{-- Floating accent card --}}
                    <div
                        class="absolute -bottom-6 -right-6 bg-primary text-white rounded-2xl p-6 shadow-lg animate-float hidden lg:block">
                        <div class="text-3xl font-headline font-bold">5+</div>
                        <div class="text-sm opacity-90">Năm kinh nghiệm</div>
                    </div>
                </div>
                {{-- Decorative element --}}
                <div class="absolute -top-4 -left-4 w-24 h-24 bg-primary/10 rounded-2xl -z-10"></div>
                <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-accent/10 rounded-full -z-10 hidden lg:block">
                </div>
            </div>

            {{-- Right: Content --}}
            <div class="reveal-right">
                <div
                    class="inline-flex items-center gap-2 bg-primary-light text-primary font-headline font-semibold text-sm px-4 py-2 rounded-full mb-6">
                    <span class="material-symbols-outlined text-lg">apartment</span>
                    Về chúng tôi
                </div>

                <h2 class="font-headline text-4xl lg:text-5xl font-bold text-on-surface leading-tight mb-6">
                    Không gian làm việc
                    <span class="text-primary">thế hệ mới</span>
                </h2>

                <div class="section-divider mb-8"></div>

                <p class="text-slate-600 text-lg leading-relaxed mb-6">
                    <strong class="text-on-surface">WorkStation</strong> là hệ thống không gian làm việc chung
                    (coworking space) hàng đầu, được thiết kế dành riêng cho các startup, freelancer, và doanh nghiệp
                    vừa & nhỏ tại Việt Nam.
                </p>

                <p class="text-slate-500 leading-relaxed mb-10">
                    Chúng tôi tin rằng môi trường làm việc ảnh hưởng trực tiếp đến chất lượng công việc. Vì vậy, mỗi
                    không gian WorkStation đều được thiết kế tỉ mỉ - từ ánh sáng tự nhiên, cây xanh, đến hệ thống cách
                    âm - tạo nên trải nghiệm làm việc tối ưu và truyền cảm hứng.
                </p>

                {{-- Feature list --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-xl">wifi</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-semibold text-on-surface">Internet tốc độ cao</h4>
                            <p class="text-slate-500 text-sm">Fiber 1Gbps, backup 4G</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-xl">local_cafe</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-semibold text-on-surface">Đồ uống miễn phí</h4>
                            <p class="text-slate-500 text-sm">Trà, cà phê, nước lọc</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-xl">print</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-semibold text-on-surface">Máy in & scan</h4>
                            <p class="text-slate-500 text-sm">Hỗ trợ in ấn tại chỗ</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-xl">security</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-semibold text-on-surface">An ninh 24/7</h4>
                            <p class="text-slate-500 text-sm">Camera & bảo vệ trực</p>
                        </div>
                    </div>
                </div>

                <a href="#services"
                    class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3.5 rounded-lg font-headline font-semibold text-sm hover:bg-primary-dark transition-all duration-300 active:scale-95 shadow-lg shadow-primary/25">
                    Khám phá dịch vụ
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </a>
            </div>
        </div>

        {{-- Stats Counter --}}
        <div class="reveal grid grid-cols-2 md:grid-cols-4 gap-8 mt-20 pt-16 border-t border-slate-100">
            <div class="text-center">
                <div class="stat-number font-headline text-4xl lg:text-5xl font-bold text-primary mb-2"
                    data-target="500">0</div>
                <div class="text-slate-500 font-medium">Thành viên</div>
            </div>
            <div class="text-center">
                <div class="stat-number font-headline text-4xl lg:text-5xl font-bold text-primary mb-2" data-target="3">
                    0</div>
                <div class="text-slate-500 font-medium">Chi nhánh</div>
            </div>
            <div class="text-center">
                <div class="stat-number font-headline text-4xl lg:text-5xl font-bold text-primary mb-2"
                    data-target="50">0</div>
                <div class="text-slate-500 font-medium">Sự kiện / tháng</div>
            </div>
            <div class="text-center">
                <div class="stat-number font-headline text-4xl lg:text-5xl font-bold text-primary mb-2"
                    data-target="98">0</div>
                <div class="text-slate-500 font-medium">% Hài lòng</div>
            </div>
        </div>
    </div>
</section>

