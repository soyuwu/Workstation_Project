<section id="services" class="py-24 lg:py-32 bg-background overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">

        {{-- Section Header --}}
        <div class="reveal text-center max-w-2xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 bg-primary-light text-primary font-headline font-semibold text-sm px-4 py-2 rounded-full mb-6">
                <span class="material-symbols-outlined text-lg">workspace_premium</span>
                Dịch vụ của chúng tôi
            </div>
            <h2 class="font-headline text-4xl lg:text-5xl font-bold text-on-surface mb-6">
                Giải pháp không gian
                <span class="text-primary">đa dạng</span>
            </h2>
            <div class="section-divider mx-auto mb-6"></div>
            <p class="text-slate-500 text-lg">
                Lựa chọn không gian phù hợp với nhu cầu và ngân sách của bạn. Từ ghế ngồi linh hoạt đến văn phòng riêng, chúng tôi có tất cả.
            </p>
        </div>

        {{-- Service Cards Grid --}}
        <div class="reveal-stagger grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            {{-- Card 1: Ghế đơn linh hoạt --}}
            <div class="service-card bg-white rounded-2xl overflow-hidden shadow-[var(--shadow-card)] group">
                <div class="relative h-56 overflow-hidden">
                    <img src="{{ asset('Images/ghedon.jpg') }}"
                         alt="Ghế đơn linh hoạt"
                         class="service-img w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute top-4 right-4 bg-accent text-white text-xs font-bold px-3 py-1 rounded-full">
                        Hot
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-primary">event_seat</span>
                        <h3 class="font-headline font-bold text-lg text-on-surface">Ghế đơn linh hoạt</h3>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        Chỗ ngồi hot desk không cố định, đến bất kỳ lúc nào. Bao gồm wifi, ổ cắm và đồ uống miễn phí.
                    </p>
                    <div class="flex items-end justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-2xl font-headline font-bold text-primary">50K</span>
                            <span class="text-slate-400 text-sm"> / giờ</span>
                        </div>
                        <a href="#" class="inline-flex items-center gap-1 text-primary font-semibold text-sm hover:gap-2 transition-all duration-300">
                            Đặt ngay
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Card 2: Bàn học nhóm --}}
            <div class="service-card bg-white rounded-2xl overflow-hidden shadow-[var(--shadow-card)] group">
                <div class="relative h-56 overflow-hidden">
                    <img src="{{ asset('Images/Banhocnhom.jpg') }}"
                         alt="Bàn học nhóm"
                         class="service-img w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-primary">groups</span>
                        <h3 class="font-headline font-bold text-lg text-on-surface">Bàn nhóm</h3>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        Khu vực bàn lớn dành cho nhóm 4-8 người, ideal cho team làm dự án, brainstorm hoặc học nhóm.
                    </p>
                    <div class="flex items-end justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-2xl font-headline font-bold text-primary">200K</span>
                            <span class="text-slate-400 text-sm"> / giờ</span>
                        </div>
                        <a href="#" class="inline-flex items-center gap-1 text-primary font-semibold text-sm hover:gap-2 transition-all duration-300">
                            Đặt ngay
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Card 3: Phòng hội thảo --}}
            <div class="service-card bg-white rounded-2xl overflow-hidden shadow-[var(--shadow-card)] group">
                <div class="relative h-56 overflow-hidden">
                    <img src="{{ asset('Images/Phonghoithao.jpg') }}"
                         alt="Phòng hội thảo"
                         class="service-img w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute top-4 right-4 bg-primary text-white text-xs font-bold px-3 py-1 rounded-full">
                        Premium
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-primary">meeting_room</span>
                        <h3 class="font-headline font-bold text-lg text-on-surface">Phòng hội thảo</h3>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        Phòng họp lớn sức chứa 20-30 người, trang bị projector, bảng trắng và hệ thống âm thanh chuyên nghiệp.
                    </p>
                    <div class="flex items-end justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-2xl font-headline font-bold text-primary">500K</span>
                            <span class="text-slate-400 text-sm"> / giờ</span>
                        </div>
                        <a href="#" class="inline-flex items-center gap-1 text-primary font-semibold text-sm hover:gap-2 transition-all duration-300">
                            Đặt ngay
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Card 4: Văn phòng riêng --}}
            <div class="service-card bg-white rounded-2xl overflow-hidden shadow-[var(--shadow-card)] group">
                <div class="relative h-56 overflow-hidden">
                    <img src="{{ asset('Images/Vanphong.webp') }}"
                         alt="Văn phòng riêng"
                         class="service-img w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-primary">corporate_fare</span>
                        <h3 class="font-headline font-bold text-lg text-on-surface">Văn phòng riêng</h3>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        Văn phòng khép kín cho team 2-10 người. Riêng tư, yên tĩnh, có khóa. Phù hợp doanh nghiệp cần sự chuyên nghiệp.
                    </p>
                    <div class="flex items-end justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-2xl font-headline font-bold text-primary">5M</span>
                            <span class="text-slate-400 text-sm"> / tháng</span>
                        </div>
                        <a href="#" class="inline-flex items-center gap-1 text-primary font-semibold text-sm hover:gap-2 transition-all duration-300">
                            Liên hệ
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>