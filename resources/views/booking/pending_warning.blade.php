@extends('layouts.app')

@section('title', 'Yêu cầu thanh toán chưa hoàn tất')
@section('nav-mode', 'solid')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center bg-gradient-to-tr from-slate-50 via-blue-50/20 to-indigo-50/20 py-16 px-6">
    <div class="max-w-xl w-full">
        <!-- Main Card -->
        <div class="relative overflow-hidden rounded-3xl bg-white border border-slate-100 p-8 md:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.04)] transition-all duration-300 hover:shadow-[0_24px_60px_rgba(0,0,0,0.07)]">
            <!-- Decorative colored bar -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-amber-400 via-orange-500 to-primary"></div>
            
            <!-- Warning Header -->
            <div class="text-center mb-8">
                <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-3xl bg-amber-50 text-amber-500 shadow-inner relative animate-pulse">
                    <span class="material-symbols-outlined text-4xl">pending_actions</span>
                    <span class="absolute inline-flex h-full w-full rounded-3xl bg-amber-400 opacity-20 animate-ping"></span>
                </div>
                <h1 class="text-2xl md:text-3xl font-headline font-extrabold text-slate-800 tracking-tight leading-tight">
                    Bạn có giao dịch chưa hoàn tất!
                </h1>
                <p class="mt-3 text-slate-500 text-sm md:text-base leading-relaxed">
                    Hệ thống ghi nhận tài khoản của bạn đang có một yêu cầu đặt chỗ ở trạng thái <span class="font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full text-xs">Chờ thanh toán</span> cho thời gian này.
                </p>
            </div>

            <!-- Booking Summary Box -->
            <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-5 md:p-6 mb-8 backdrop-blur-sm">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm text-slate-400">info</span>
                    Thông tin đơn đặt chỗ trùng khớp
                </h3>
                
                <div class="space-y-3.5">
                    <div class="flex justify-between items-start gap-4">
                        <span class="text-sm text-slate-500 font-medium">Mã đơn đặt:</span>
                        <span class="text-sm font-bold text-slate-800 font-mono">{{ $booking->booking_code }}</span>
                    </div>
                    <div class="flex justify-between items-start gap-4">
                        <span class="text-sm text-slate-500 font-medium">Không gian:</span>
                        <span class="text-sm font-bold text-slate-800">{{ $booking->workspace->name }}</span>
                    </div>
                    <div class="flex justify-between items-start gap-4">
                        <span class="text-sm text-slate-500 font-medium">Ngày sử dụng:</span>
                        <span class="text-sm font-bold text-slate-800">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-start gap-4">
                        <span class="text-sm text-slate-500 font-medium">Thời gian đặt:</span>
                        @if(str_contains($booking->notes ?? '', 'Đặt tháng'))
                            <span class="text-sm font-bold text-slate-800">Thuê gói tháng</span>
                        @else
                            <span class="text-sm font-bold text-slate-800">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} 
                                ({{ (float)$booking->duration_hours }} giờ)
                            </span>
                        @endif
                    </div>
                    <div class="border-t border-slate-200/60 my-3 pt-3.5 flex justify-between items-center">
                        <span class="text-sm font-semibold text-slate-600">Tổng thanh toán:</span>
                        <span class="text-lg font-bold text-primary">{{ number_format($booking->total_amount) }}đ</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('payment.vietqr', ['booking_code' => $booking->booking_code]) }}" 
                   class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-5 py-4 text-sm font-bold text-white transition-all hover:bg-primary-dark hover:shadow-lg active:scale-95 duration-200">
                    <span class="material-symbols-outlined text-lg">credit_card</span>
                    Thanh toán ngay VietQR
                </a>
                
                @php
                    $historyRoute = (auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                        ? route('admin.booking')
                        : route('account.bookings');
                    $historyLabel = (auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                        ? 'Quản lý đặt chỗ'
                        : 'Lịch sử đặt chỗ';
                @endphp
                <a href="{{ $historyRoute }}" 
                   class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-4 text-sm font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 duration-200">
                    <span class="material-symbols-outlined text-lg">history</span>
                    {{ $historyLabel }}
                </a>
            </div>
            
            <!-- Secondary Actions -->
            <div class="mt-6 text-center">
                <a href="{{ $fallbackUrl ?? route('booking.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-400 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    Quay lại tìm phòng khác
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
