{{-- Static Hero Banner - Coworking Space Premium Deep Slate Background --}}
<section class="hero-banner relative min-h-[100vh] flex items-center justify-center overflow-hidden bg-slate-950">
    {{-- Background Image with Premium Deep Navy/Slate Overlay --}}
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop" 
             alt="Coworking Space" 
             class="w-full h-full object-cover object-center select-none pointer-events-none opacity-50">
        {{-- Elegant deep slate/navy tint to match the website brand color scheme --}}
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-blue-950/60 to-slate-950/95"></div>
    </div>

    {{-- Subtle decorative orbs --}}
    <div
        class="absolute top-20 -left-24 w-[450px] h-[450px] bg-gradient-to-br from-blue-500/10 to-cyan-400/5 rounded-full blur-[120px]">
    </div>
    <div
        class="absolute bottom-20 -right-24 w-[400px] h-[400px] bg-gradient-to-bl from-amber-500/10 to-orange-400/5 rounded-full blur-[120px]">
    </div>

    {{-- Dot pattern overlay --}}
    <div class="absolute inset-0 opacity-[0.03] z-0"
        style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px;">
    </div>

    {{-- Content --}}
    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto mt-20">
        {{-- Badge --}}
        <div
            class="inline-flex items-center gap-2 bg-blue-500/10 backdrop-blur-md text-blue-300 font-headline text-xs tracking-[0.2em] uppercase px-5 py-2.5 rounded-full mb-8 border border-blue-500/20">
            <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
            Coworking Space tại TP.HCM
        </div>

        {{-- Headline --}}
        <h1
            class="font-headline text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-[1.1] tracking-tight">
            Không gian làm việc
            <span
                class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-500">thế hệ mới</span>
        </h1>

        {{-- Subtext --}}
        <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed mb-12 font-light">
            Nơi ý tưởng được hiện thực hóa. Thiết kế tối giản, tiện ích đầy đủ, cộng đồng năng động - tất cả trong một
            không gian duy nhất.
        </p>

        {{-- CTA --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('booking.index') }}"
                class="group inline-flex items-center gap-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-10 py-4 rounded-xl font-headline font-bold text-base hover:from-blue-600 hover:to-cyan-600 transition-all duration-300 active:scale-95 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/35">
                <span>Đặt phòng ngay</span>
                <span
                    class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform duration-300">arrow_forward</span>
            </a>
            <a href="#services"
                class="inline-flex items-center gap-2 text-slate-300 hover:text-white font-headline font-medium text-sm transition-colors duration-300 px-6 py-4">
                <span class="material-symbols-outlined text-lg">explore</span>
                Khám phá dịch vụ
            </a>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2">
        <span class="text-slate-400 text-xs font-headline tracking-widest uppercase">Cuộn xuống</span>
        <div class="w-[1px] h-10 bg-gradient-to-b from-slate-300 to-transparent animate-bounce"></div>
    </div>
</section>

