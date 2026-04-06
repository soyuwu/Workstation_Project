{{-- ========================================
    SECTION 1: HERO CAROUSEL
    ======================================== --}}
<div class="carousel">
    {{-- list item --}}
    <div class="list">
        <div class="item">
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop" alt="Coworking Space">
            <div class="content">
                <div class="author">WORKSTATION</div>
                <div class="title">KHÔNG GIAN</div>
                <div class="topic">LÀM VIỆC CHUNG</div>
                <div class="des">
                    Khám phá không gian làm việc chung hiện đại, nơi kết nối cộng đồng sáng tạo. Thiết kế mở, thoáng đãng giúp bạn tập trung tối đa và nâng cao hiệu suất làm việc mỗi ngày.
                </div>
                <div class="buttons">
                    <button>KHÁM PHÁ</button>
                    <button>ĐẶT CHỖ</button>
                </div>
            </div>
        </div>
        <div class="item">
            <img src="https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?q=80&w=2070&auto=format&fit=crop" alt="Hot Desk">
            <div class="content">
                <div class="author">WORKSTATION</div>
                <div class="title">LINH HOẠT</div>
                <div class="topic">HOT DESK</div>
                <div class="des">
                    Chỗ ngồi linh hoạt, đến bất kỳ lúc nào bạn muốn. Không ràng buộc hợp đồng dài hạn, chỉ cần đặt chỗ và bắt đầu làm việc ngay với đầy đủ tiện nghi chuyên nghiệp.
                </div>
                <div class="buttons">
                    <button>KHÁM PHÁ</button>
                    <button>ĐẶT CHỖ</button>
                </div>
            </div>
        </div>
        <div class="item">
            <img src="https://images.unsplash.com/photo-1497215842964-222b430dc094?q=80&w=2070&auto=format&fit=crop" alt="Meeting Room">
            <div class="content">
                <div class="author">WORKSTATION</div>
                <div class="title">PHÒNG HỌP</div>
                <div class="topic">CHUYÊN NGHIỆP</div>
                <div class="des">
                    Phòng họp trang bị hiện đại, cách âm chuyên nghiệp. Lý tưởng cho các buổi brainstorm, họp khách hàng hay workshop với sức chứa linh hoạt từ 4 đến 30 người.
                </div>
                <div class="buttons">
                    <button>KHÁM PHÁ</button>
                    <button>ĐẶT CHỖ</button>
                </div>
            </div>
        </div>
    </div>

    {{-- thumbnail --}}
    <div class="thumbnail">
        <div class="item">
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=500&auto=format&fit=crop" alt="Thumb 1">
            <div class="content">
                <div class="title">Coworking</div>
                <div class="description">Không gian mở</div>
            </div>
        </div>
        <div class="item">
            <img src="https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?q=80&w=500&auto=format&fit=crop" alt="Thumb 2">
            <div class="content">
                <div class="title">Hot Desk</div>
                <div class="description">Linh hoạt</div>
            </div>
        </div>
        <div class="item">
            <img src="https://images.unsplash.com/photo-1497215842964-222b430dc094?q=80&w=500&auto=format&fit=crop" alt="Thumb 3">
            <div class="content">
                <div class="title">Phòng họp</div>
                <div class="description">Chuyên nghiệp</div>
            </div>
        </div>
    </div>

    {{-- arrows --}}
    <div class="arrows">
        <button id="prev"><</button>
        <button id="next">></button>
    </div>
</div>


{{-- ========================================
    SECTION 2: GIỚI THIỆU (ABOUT)
    ======================================== --}}
<section id="about" class="py-24 lg:py-32 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">

            {{-- Left: Image --}}
            <div class="reveal-left relative">
                <div class="relative rounded-2xl overflow-hidden shadow-[var(--shadow-card)]">
                    <img src="{{ asset('Images/Linhhoat.jpg') }}"
                         alt="WorkStation - Không gian làm việc linh hoạt"
                         class="w-full h-[500px] object-cover">
                    {{-- Floating accent card --}}
                    <div class="absolute -bottom-6 -right-6 bg-primary text-white rounded-2xl p-6 shadow-lg animate-float hidden lg:block">
                        <div class="text-3xl font-headline font-bold">5+</div>
                        <div class="text-sm opacity-90">Năm kinh nghiệm</div>
                    </div>
                </div>
                {{-- Decorative element --}}
                <div class="absolute -top-4 -left-4 w-24 h-24 bg-primary/10 rounded-2xl -z-10"></div>
                <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-accent/10 rounded-full -z-10 hidden lg:block"></div>
            </div>

            {{-- Right: Content --}}
            <div class="reveal-right">
                <div class="inline-flex items-center gap-2 bg-primary-light text-primary font-headline font-semibold text-sm px-4 py-2 rounded-full mb-6">
                    <span class="material-symbols-outlined text-lg">apartment</span>
                    Về chúng tôi
                </div>

                <h2 class="font-headline text-4xl lg:text-5xl font-bold text-on-surface leading-tight mb-6">
                    Không gian làm việc
                    <span class="text-primary">thế hệ mới</span>
                </h2>

                <div class="section-divider mb-8"></div>

                <p class="text-slate-600 text-lg leading-relaxed mb-6">
                    <strong class="text-on-surface">WorkStation</strong> là hệ thống không gian làm việc chung (coworking space) hàng đầu, được thiết kế dành riêng cho các startup, freelancer, và doanh nghiệp vừa & nhỏ tại Việt Nam.
                </p>

                <p class="text-slate-500 leading-relaxed mb-10">
                    Chúng tôi tin rằng môi trường làm việc ảnh hưởng trực tiếp đến chất lượng công việc. Vì vậy, mỗi không gian WorkStation đều được thiết kế tỉ mỉ — từ ánh sáng tự nhiên, cây xanh, đến hệ thống cách âm — tạo nên trải nghiệm làm việc tối ưu và truyền cảm hứng.
                </p>

                {{-- Feature list --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-xl">wifi</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-semibold text-on-surface">Internet tốc độ cao</h4>
                            <p class="text-slate-500 text-sm">Fiber 1Gbps, backup 4G</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-xl">local_cafe</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-semibold text-on-surface">Đồ uống miễn phí</h4>
                            <p class="text-slate-500 text-sm">Trà, cà phê, nước lọc</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-xl">print</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-semibold text-on-surface">Máy in & scan</h4>
                            <p class="text-slate-500 text-sm">Hỗ trợ in ấn tại chỗ</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-xl">security</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-semibold text-on-surface">An ninh 24/7</h4>
                            <p class="text-slate-500 text-sm">Camera & bảo vệ trực</p>
                        </div>
                    </div>
                </div>

                <a href="#services" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3.5 rounded-lg font-headline font-semibold text-sm hover:bg-primary-dark transition-all duration-300 active:scale-95 shadow-lg shadow-primary/25">
                    Khám phá dịch vụ
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </a>
            </div>
        </div>

        {{-- Stats Counter --}}
        <div class="reveal grid grid-cols-2 md:grid-cols-4 gap-8 mt-20 pt-16 border-t border-slate-100">
            <div class="text-center">
                <div class="stat-number font-headline text-4xl lg:text-5xl font-bold text-primary mb-2" data-target="500">0</div>
                <div class="text-slate-500 font-medium">Thành viên</div>
            </div>
            <div class="text-center">
                <div class="stat-number font-headline text-4xl lg:text-5xl font-bold text-primary mb-2" data-target="3">0</div>
                <div class="text-slate-500 font-medium">Chi nhánh</div>
            </div>
            <div class="text-center">
                <div class="stat-number font-headline text-4xl lg:text-5xl font-bold text-primary mb-2" data-target="50">0</div>
                <div class="text-slate-500 font-medium">Sự kiện / tháng</div>
            </div>
            <div class="text-center">
                <div class="stat-number font-headline text-4xl lg:text-5xl font-bold text-primary mb-2" data-target="98">0</div>
                <div class="text-slate-500 font-medium">% Hài lòng</div>
            </div>
        </div>
    </div>
</section>


{{-- ========================================
    SECTION 3: DỊCH VỤ (SERVICES)
    ======================================== --}}
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


{{-- ========================================
    SECTION 4: ĐÁNH GIÁ (TESTIMONIALS)
    ======================================== --}}
<section id="reviews" class="py-24 lg:py-32 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">

        {{-- Section Header --}}
        <div class="reveal text-center max-w-2xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 bg-accent-light text-accent font-headline font-semibold text-sm px-4 py-2 rounded-full mb-6">
                <span class="material-symbols-outlined text-lg">reviews</span>
                Khách hàng nói gì
            </div>
            <h2 class="font-headline text-4xl lg:text-5xl font-bold text-on-surface mb-6">
                Đánh giá từ
                <span class="text-primary">cộng đồng</span>
            </h2>
            <div class="section-divider mx-auto mb-6"></div>
            <p class="text-slate-500 text-lg">
                Hơn 500 thành viên đã tin tưởng lựa chọn WorkStation. Cùng lắng nghe trải nghiệm thực tế của họ.
            </p>
        </div>

        {{-- Swiper Testimonials --}}
        <div class="reveal">
            <div class="testimonial-swiper swiper pb-14">
                <div class="swiper-wrapper">

                    {{-- Testimonial 1 --}}
                    <div class="swiper-slide">
                        <div class="testimonial-card bg-background rounded-2xl p-8 mx-2 shadow-[var(--shadow-ambient)]">
                            <div class="flex items-center gap-1 mb-4 star-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <p class="text-slate-600 leading-relaxed mb-6 italic">
                                "Mình là freelancer, trước giờ hay làm việc ở quán cà phê nhưng luôn bị phân tán. Từ khi đến WorkStation, mình tập trung hơn hẳn. Wifi nhanh, không gian yên tĩnh và cộng đồng rất thân thiện!"
                            </p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white font-bold text-lg">
                                    T
                                </div>
                                <div>
                                    <h4 class="font-headline font-semibold text-on-surface">Trần Minh Tuấn</h4>
                                    <p class="text-slate-400 text-sm">Freelance Designer</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Testimonial 2 --}}
                    <div class="swiper-slide">
                        <div class="testimonial-card bg-background rounded-2xl p-8 mx-2 shadow-[var(--shadow-ambient)]">
                            <div class="flex items-center gap-1 mb-4 star-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <p class="text-slate-600 leading-relaxed mb-6 italic">
                                "Team mình thuê văn phòng riêng ở WorkStation được 6 tháng rồi. Giá hợp lý hơn nhiều so với thuê văn phòng truyền thống, mà mọi thứ đều đã bao gồm. Rất tiện lợi cho startup!"
                            </p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500 to-teal-400 flex items-center justify-center text-white font-bold text-lg">
                                    L
                                </div>
                                <div>
                                    <h4 class="font-headline font-semibold text-on-surface">Lê Hoàng Nam</h4>
                                    <p class="text-slate-400 text-sm">CEO, TechVi Startup</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Testimonial 3 --}}
                    <div class="swiper-slide">
                        <div class="testimonial-card bg-background rounded-2xl p-8 mx-2 shadow-[var(--shadow-ambient)]">
                            <div class="flex items-center gap-1 mb-4 star-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <p class="text-slate-600 leading-relaxed mb-6 italic">
                                "Phòng hội thảo của WorkStation rất chuyên nghiệp. Mình đã tổ chức 3 workshop ở đây, khách tham dự đều ấn tượng với không gian và trang thiết bị. Sẽ quay lại nhiều lần nữa!"
                            </p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-400 flex items-center justify-center text-white font-bold text-lg">
                                    P
                                </div>
                                <div>
                                    <h4 class="font-headline font-semibold text-on-surface">Phạm Ngọc Hà</h4>
                                    <p class="text-slate-400 text-sm">Marketing Manager</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Testimonial 4 --}}
                    <div class="swiper-slide">
                        <div class="testimonial-card bg-background rounded-2xl p-8 mx-2 shadow-[var(--shadow-ambient)]">
                            <div class="flex items-center gap-1 mb-4 star-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <p class="text-slate-600 leading-relaxed mb-6 italic">
                                "Là sinh viên IT, mình cần chỗ yên tĩnh để code. WorkStation có chỗ ngồi giá sinh viên rất hợp lý, wifi siêu nhanh và ổ cắm ở khắp nơi. Mình giới thiệu cho cả nhóm bạn rồi!"
                            </p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-500 to-orange-400 flex items-center justify-center text-white font-bold text-lg">
                                    N
                                </div>
                                <div>
                                    <h4 class="font-headline font-semibold text-on-surface">Nguyễn Thị Mai</h4>
                                    <p class="text-slate-400 text-sm">Sinh viên UIT</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Testimonial 5 --}}
                    <div class="swiper-slide">
                        <div class="testimonial-card bg-background rounded-2xl p-8 mx-2 shadow-[var(--shadow-ambient)]">
                            <div class="flex items-center gap-1 mb-4 star-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <p class="text-slate-600 leading-relaxed mb-6 italic">
                                "Đặt chỗ online trên website rất thuận tiện, chỉ vài click là xong. Đội ngũ nhân viên thân thiện, hỗ trợ nhanh. WorkStation đúng nghĩa là ngôi nhà thứ hai cho dân văn phòng!"
                            </p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-500 to-blue-400 flex items-center justify-center text-white font-bold text-lg">
                                    D
                                </div>
                                <div>
                                    <h4 class="font-headline font-semibold text-on-surface">Đỗ Văn Khoa</h4>
                                    <p class="text-slate-400 text-sm">Product Manager, FPT</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- Swiper Pagination --}}
                <div class="swiper-pagination !bottom-0"></div>
            </div>
        </div>
    </div>
</section>


{{-- ========================================
    SECTION 5: CTA (CALL TO ACTION)
    ======================================== --}}
<section class="relative overflow-hidden">
    <div class="cta-gradient py-20 lg:py-28">
        {{-- Decorative circles --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        <div class="absolute top-1/2 left-1/4 w-40 h-40 bg-white/3 rounded-full"></div>

        <div class="reveal relative z-10 max-w-4xl mx-auto px-6 lg:px-12 text-center">
            <h2 class="font-headline text-3xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                Sẵn sàng nâng tầm<br>không gian làm việc?
            </h2>
            <p class="text-blue-100 text-lg lg:text-xl mb-10 max-w-2xl mx-auto leading-relaxed">
                Đăng ký trải nghiệm miễn phí 1 ngày tại WorkStation. Không cần cam kết, không phí ẩn.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#" class="inline-flex items-center justify-center gap-2 bg-white text-primary px-8 py-4 rounded-xl font-headline font-bold text-base hover:bg-slate-50 transition-all duration-300 active:scale-95 shadow-xl">
                    <span class="material-symbols-outlined">calendar_today</span>
                    Đặt chỗ ngay
                </a>
                <a href="#" class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-sm text-white border border-white/25 px-8 py-4 rounded-xl font-headline font-semibold text-base hover:bg-white/20 transition-all duration-300 active:scale-95">
                    <span class="material-symbols-outlined">call</span>
                    Liên hệ tư vấn
                </a>
            </div>
        </div>
    </div>
</section>


{{-- ========================================
    FOOTER
    ======================================== --}}
<footer class="bg-inverse-surface text-slate-300 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

            {{-- Col 1: Brand --}}
            <div>
                <h3 class="font-headline text-2xl font-bold text-white mb-4">WORKSTATION</h3>
                <p class="text-slate-400 leading-relaxed mb-6">
                    Hệ thống không gian làm việc chung hàng đầu dành cho startup, freelancer và doanh nghiệp tại Việt Nam.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary transition-colors duration-300 flex items-center justify-center">
                        <i class="fa-brands fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary transition-colors duration-300 flex items-center justify-center">
                        <i class="fa-brands fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary transition-colors duration-300 flex items-center justify-center">
                        <i class="fa-brands fa-linkedin-in text-sm"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 hover:bg-primary transition-colors duration-300 flex items-center justify-center">
                        <i class="fa-brands fa-tiktok text-sm"></i>
                    </a>
                </div>
            </div>

            {{-- Col 2: Quick Links --}}
            <div>
                <h4 class="font-headline font-semibold text-white mb-4">Liên kết nhanh</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-300">Trang chủ</a></li>
                    <li><a href="#about" class="text-slate-400 hover:text-white transition-colors duration-300">Giới thiệu</a></li>
                    <li><a href="#services" class="text-slate-400 hover:text-white transition-colors duration-300">Dịch vụ</a></li>
                    <li><a href="#reviews" class="text-slate-400 hover:text-white transition-colors duration-300">Đánh giá</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-300">Đặt chỗ</a></li>
                </ul>
            </div>

            {{-- Col 3: Services --}}
            <div>
                <h4 class="font-headline font-semibold text-white mb-4">Dịch vụ</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-300">Ghế đơn linh hoạt</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-300">Bàn nhóm</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-300">Phòng hội thảo</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-300">Văn phòng riêng</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-300">Sự kiện & Workshop</a></li>
                </ul>
            </div>

            {{-- Col 4: Contact --}}
            <div>
                <h4 class="font-headline font-semibold text-white mb-4">Liên hệ</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-xl mt-0.5">location_on</span>
                        <span class="text-slate-400">Khu phố 6, P.Linh Trung, TP.Thủ Đức, TP.HCM</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-xl">phone</span>
                        <a href="tel:0901234567" class="text-slate-400 hover:text-white transition-colors duration-300">090 123 4567</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-xl">mail</span>
                        <a href="mailto:hello@workstation.vn" class="text-slate-400 hover:text-white transition-colors duration-300">hello@workstation.vn</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-xl">schedule</span>
                        <span class="text-slate-400">T2 – CN: 7:00 – 22:00</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-slate-500 text-sm">
                © 2026 WorkStation. All rights reserved.
            </p>
            <div class="flex gap-6 text-sm">
                <a href="#" class="text-slate-500 hover:text-white transition-colors">Chính sách bảo mật</a>
                <a href="#" class="text-slate-500 hover:text-white transition-colors">Điều khoản sử dụng</a>
            </div>
        </div>
    </div>
</footer>


{{-- ========================================
    SCRIPTS
    ======================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ---- Carousel Logic ----
    let nextDom = document.getElementById('next');
    let prevDom = document.getElementById('prev');

    let carouselDom = document.querySelector('.carousel');
    if (carouselDom) {
        let SliderDom = carouselDom.querySelector('.carousel .list');
        let thumbnailBorderDom = document.querySelector('.carousel .thumbnail');
        let thumbnailItemsDom = thumbnailBorderDom.querySelectorAll('.item');

        thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);

        let timeRunning = 800;
        let timeAutoNext = 5000;
        let runTimeOut;
        let autoNextTimeout;

        nextDom.onclick = function(){
            showSlider('next');
        }

        prevDom.onclick = function(){
            showSlider('prev');
        }

        // Auto-slide
        autoNextTimeout = setTimeout(() => {
            nextDom.click();
        }, timeAutoNext);

        function showSlider(type){
            let SliderItemsDom = SliderDom.querySelectorAll('.carousel .list .item');
            let thumbnailItemsDom = document.querySelectorAll('.carousel .thumbnail .item');

            if(type === 'next'){
                SliderDom.appendChild(SliderItemsDom[0]);
                thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
                carouselDom.classList.add('next');
            } else {
                SliderDom.prepend(SliderItemsDom[SliderItemsDom.length - 1]);
                thumbnailBorderDom.prepend(thumbnailItemsDom[thumbnailItemsDom.length - 1]);
                carouselDom.classList.add('prev');
            }

            clearTimeout(runTimeOut);
            runTimeOut = setTimeout(() => {
                carouselDom.classList.remove('next');
                carouselDom.classList.remove('prev');
            }, timeRunning);

            clearTimeout(autoNextTimeout);
            autoNextTimeout = setTimeout(() => {
                nextDom.click();
            }, timeAutoNext);
        }
    }

    // ---- Scroll Reveal (IntersectionObserver) ----
    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-stagger');

    if (revealElements.length > 0) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -60px 0px'
        });

        revealElements.forEach(el => revealObserver.observe(el));
    }

    // ---- Stats Counter Animation ----
    const statNumbers = document.querySelectorAll('.stat-number[data-target]');

    if (statNumbers.length > 0) {
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseInt(el.getAttribute('data-target'));
                    const duration = 2000;
                    const startTime = performance.now();

                    function updateCounter(currentTime) {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        // Ease out cubic
                        const eased = 1 - Math.pow(1 - progress, 3);
                        const current = Math.round(target * eased);

                        el.textContent = current.toLocaleString() + (target === 98 ? '%' : '+');

                        if (progress < 1) {
                            requestAnimationFrame(updateCounter);
                        }
                    }

                    requestAnimationFrame(updateCounter);
                    counterObserver.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        statNumbers.forEach(el => counterObserver.observe(el));
    }

    // ---- Swiper Testimonials ----
    if (document.querySelector('.testimonial-swiper')) {
        new Swiper('.testimonial-swiper', {
            slidesPerView: 1,
            spaceBetween: 16,
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },
            },
        });
    }

    // ---- Navbar scroll background + text color ----
    const nav = document.querySelector('.ws-nav');
    if (nav) {
        const navLinks = nav.querySelectorAll('.ws-nav-link');
        const projectTitle = nav.querySelector('.project-title');
        const authLinks = nav.querySelectorAll('a[href*="logIn"], a[href*="register"]');
        const ctaBtn = nav.querySelector('a[href="#"]');
        const menuToggle = nav.querySelector('#menu-toggle');

        function updateNavStyle() {
            if (window.scrollY > 50) {
                // Scrolled: white bg, dark text
                nav.classList.add('bg-white/95', 'backdrop-blur-xl', 'shadow-md');
                nav.classList.remove('bg-transparent');
                navLinks.forEach(link => {
                    link.classList.remove('text-white', 'hover:text-blue-200');
                    link.classList.add('text-slate-600', 'hover:text-primary');
                    link.classList.remove('after:bg-white');
                    link.classList.add('after:bg-primary');
                });
                if (projectTitle) {
                    projectTitle.classList.remove('text-white');
                    projectTitle.classList.add('text-on-surface');
                }
                authLinks.forEach(link => {
                    link.classList.remove('text-white/80', 'hover:text-white');
                    link.classList.add('text-slate-600', 'hover:text-slate-900');
                });
                if (ctaBtn) {
                    ctaBtn.classList.remove('bg-white', 'text-primary', 'hover:bg-slate-100');
                    ctaBtn.classList.add('bg-primary', 'text-white', 'hover:opacity-90');
                }
                if (menuToggle) {
                    menuToggle.classList.remove('text-white');
                    menuToggle.classList.add('text-slate-700');
                }
            } else {
                // Top: transparent, white text
                nav.classList.remove('bg-white/95', 'backdrop-blur-xl', 'shadow-md');
                nav.classList.add('bg-transparent');
                navLinks.forEach(link => {
                    link.classList.add('text-white', 'hover:text-blue-200');
                    link.classList.remove('text-slate-600', 'hover:text-primary');
                    link.classList.add('after:bg-white');
                    link.classList.remove('after:bg-primary');
                });
                if (projectTitle) {
                    projectTitle.classList.add('text-white');
                    projectTitle.classList.remove('text-on-surface');
                }
                authLinks.forEach(link => {
                    link.classList.add('text-white/80', 'hover:text-white');
                    link.classList.remove('text-slate-600', 'hover:text-slate-900');
                });
                if (ctaBtn) {
                    ctaBtn.classList.add('bg-white', 'text-primary', 'hover:bg-slate-100');
                    ctaBtn.classList.remove('bg-primary', 'text-white', 'hover:opacity-90');
                }
                if (menuToggle) {
                    menuToggle.classList.add('text-white');
                    menuToggle.classList.remove('text-slate-700');
                }
            }
        }

        window.addEventListener('scroll', updateNavStyle);
        updateNavStyle(); // Run on page load
    }
});
</script>
