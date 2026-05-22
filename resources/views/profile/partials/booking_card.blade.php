{{-- Booking Ticket Card --}}
<div class="group relative bg-white border border-slate-100 rounded-3xl p-5 md:p-6 shadow-[0_8px_30px_rgb(0,0,0,0.015)] hover:shadow-[0_12px_40px_rgba(0,0,0,0.04)] transition-all duration-300 flex flex-col md:flex-row md:items-center justify-between gap-6 overflow-hidden">
    
    {{-- Left Border Accent based on status --}}
    @php
        $accentClass = 'bg-slate-300';
        $badgeClass = 'bg-slate-50 text-slate-600 border-slate-200';
        $statusText = 'Không xác định';

        if ($booking->status === 'confirmed') {
            $accentClass = 'bg-emerald-500';
            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
            $statusText = 'Đã xác nhận';
        } elseif ($booking->status === 'pending') {
            $accentClass = 'bg-amber-500';
            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-100';
            $statusText = 'Chờ thanh toán';
        } elseif ($booking->status === 'completed') {
            $accentClass = 'bg-blue-500';
            $badgeClass = 'bg-blue-50 text-blue-700 border-blue-100';
            $statusText = 'Đã hoàn thành';
        } elseif ($booking->status === 'cancelled') {
            $accentClass = 'bg-rose-500';
            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-100';
            $statusText = 'Đã hủy';
        }
    @endphp
    
    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $accentClass }}"></div>

    {{-- Main Workspace & Time Info --}}
    <div class="flex-1 space-y-4">
        <div class="flex flex-wrap items-center gap-2.5">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full border {{ $badgeClass }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $accentClass }}"></span>
                {{ $statusText }}
            </span>
            <span class="text-xs font-bold text-slate-400">Mã đặt chỗ:</span>
            <span class="text-xs font-extrabold text-primary tracking-wider uppercase bg-primary-light px-2.5 py-0.5 rounded-lg border border-primary/10">
                {{ $booking->booking_code }}
            </span>
        </div>

        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-primary border border-slate-100 group-hover:bg-primary-light group-hover:text-primary transition-colors">
                @if($booking->workspace && $booking->workspace->room_type_id == 2)
                    <span class="material-symbols-outlined text-2xl">groups</span>
                @elseif($booking->workspace && $booking->workspace->room_type_id == 3)
                    <span class="material-symbols-outlined text-2xl">corporate_fare</span>
                @else
                    <span class="material-symbols-outlined text-2xl">laptop_mac</span>
                @endif
            </div>
            
            <div class="space-y-1">
                <h4 class="font-headline font-bold text-base text-slate-800 tracking-tight group-hover:text-primary transition-colors">
                    {{ $booking->workspace->name ?? 'Không gian làm việc' }}
                </h4>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 font-medium">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-slate-400">qr_code_2</span>
                        <span>Mã vị trí: {{ $booking->workspace->code ?? 'N/A' }}</span>
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-slate-400">person</span>
                        <span>Sức chứa: {{ $booking->workspace->capacity ?? 1 }} người</span>
                    </span>
                </div>
            </div>
        </div>

        <hr class="border-dashed border-slate-100 my-2">

        {{-- DateTime details --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-4 text-xs font-semibold text-slate-600">
            <div class="space-y-0.5">
                <div class="text-[10px] text-slate-400 uppercase tracking-wider">Ngày sử dụng</div>
                <div class="flex items-center gap-1.5 text-slate-800">
                    <span class="material-symbols-outlined text-[18px] text-slate-400">calendar_today</span>
                    <span>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</span>
                </div>
            </div>

            <div class="space-y-0.5">
                <div class="text-[10px] text-slate-400 uppercase tracking-wider">Thời gian</div>
                <div class="flex items-center gap-1.5 text-slate-800">
                    <span class="material-symbols-outlined text-[18px] text-slate-400">schedule</span>
                    <span>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                </div>
            </div>

            <div class="col-span-2 md:col-span-1 space-y-0.5">
                <div class="text-[10px] text-slate-400 uppercase tracking-wider">Tổng thời gian / Số tiền</div>
                <div class="flex items-center gap-1.5 text-slate-800">
                    <span class="material-symbols-outlined text-[18px] text-slate-400">hourglass_empty</span>
                    <span>{{ $booking->duration_hours }} giờ / <span class="text-slate-900 font-extrabold">{{ number_format($booking->total_amount, 0, ',', '.') }}đ</span></span>
                </div>
            </div>
        </div>

        @if($booking->notes)
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 text-xs text-slate-500 flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-slate-400 mt-0.5">sticky_note_2</span>
                <span class="italic">Ghi chú: "{{ $booking->notes }}"</span>
            </div>
        @endif

        {{-- Review details display inside booking card if exists (for completed past bookings) --}}
        @if($booking->review)
            <div class="bg-amber-50/50 rounded-2xl p-4 border border-amber-100 space-y-2 mt-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs font-bold text-amber-800">
                        <span class="material-symbols-outlined text-[18px]">verified_user</span>
                        <span>Đánh giá của bạn:</span>
                    </div>
                    <div class="flex text-amber-400">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-sm font-bold">{{ $i <= $booking->review->rating ? 'star' : 'star_border' }}</span>
                        @endfor
                    </div>
                </div>
                <p class="text-xs text-slate-600 font-medium italic">"{{ $booking->review->content }}"</p>
            </div>
        @endif
    </div>

    {{-- Actions Side --}}
    <div class="flex flex-row md:flex-col md:justify-center md:items-end gap-3 border-t md:border-t-0 pt-4 md:pt-0 border-slate-100 w-full md:w-auto z-10 shrink-0">
        @if($type === 'upcoming' || $type === 'active')
            @if($booking->status === 'confirmed')
                {{-- QR Code check-in --}}
                <button onclick="openModalQR('{{ $booking->booking_code }}', '{{ $booking->workspace->name ?? 'Workspace' }}', '{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}', '{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}', '{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}')" 
                    class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 px-5.5 py-3 bg-primary hover:bg-primary/95 active:scale-95 text-white text-xs font-bold rounded-2xl transition-all shadow-md shadow-primary/10">
                    <span class="material-symbols-outlined text-[18px] font-bold">qr_code_scanner</span>
                    Xem mã QR
                </button>
            @endif

            @if($booking->status === 'pending' || $booking->status === 'confirmed')
                {{-- Cancel Booking form --}}
                <form action="{{ route('profile.booking.cancel', $booking->id) }}" method="POST" class="m-0 flex-1 md:flex-initial w-full md:w-auto" onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch đặt chỗ này không?')">
                    @csrf
                    <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-white hover:bg-rose-50 border border-rose-200 hover:border-rose-300 text-rose-600 text-xs font-bold rounded-2xl transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[18px] font-bold">cancel</span>
                        Hủy đặt chỗ
                    </button>
                </form>
            @endif
        @endif

        @if($type === 'past' && $booking->status === 'completed' && !$booking->review)
            {{-- Write Review button --}}
            <button onclick="openModalReview({{ $booking->id }}, '{{ $booking->booking_code }}', {{ $booking->workspace->id }}, '{{ $booking->workspace->name ?? 'Workspace' }}')" 
                class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 px-5 py-3 bg-white hover:bg-amber-50 border border-amber-300 text-amber-700 hover:text-amber-800 text-xs font-bold rounded-2xl transition-all active:scale-95 shadow-sm">
                <span class="material-symbols-outlined text-[18px] font-bold">rate_review</span>
                Viết đánh giá
            </button>
        @endif
        
        @if($booking->status === 'cancelled')
            <span class="text-xs text-rose-500 font-semibold italic">Đã hủy đặt chỗ</span>
        @endif
    </div>
</div>
