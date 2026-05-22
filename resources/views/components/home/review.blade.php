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
                    @php
                        $avatarColors = [
                            'from-primary to-blue-400',
                            'from-emerald-500 to-teal-400',
                            'from-purple-500 to-pink-400',
                            'from-amber-500 to-orange-400',
                            'from-cyan-500 to-blue-400',
                        ];
                    @endphp

                    @forelse($reviews ?? [] as $index => $review)
                        @php
                            $colorClass = $avatarColors[$index % count($avatarColors)];
                            // Lấy chữ cái đầu tiên của tên, giả sử tên là "Trần Minh Tuấn" -> "T"
                            $nameParts = explode(' ', $review->author_name);
                            $lastName = end($nameParts);
                            $firstLetter = mb_substr($lastName, 0, 1);
                        @endphp
                        <div class="swiper-slide">
                            <div class="testimonial-card bg-background rounded-2xl p-8 mx-2 shadow-[var(--shadow-ambient)]">
                                <div class="flex items-center gap-1 mb-4 star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fa-solid fa-star"></i>
                                        @elseif($i - 0.5 == $review->rating)
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        @else
                                            <i class="fa-regular fa-star text-slate-300"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-slate-600 leading-relaxed mb-6 italic">
                                    "{{ $review->content }}"
                                </p>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br {{ $colorClass }} flex items-center justify-center text-white font-bold text-lg">
                                        {{ mb_strtoupper($firstLetter) }}
                                    </div>
                                    <div>
                                        <h4 class="font-headline font-semibold text-on-surface">{{ $review->author_name }}</h4>
                                        <p class="text-slate-400 text-sm">{{ $review->author_role }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-full text-center text-slate-500 py-8">
                            Chưa có đánh giá nào.
                        </div>
                    @endforelse
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
