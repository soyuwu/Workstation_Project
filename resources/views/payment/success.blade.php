@extends('layouts.app')

@section('title', 'Hoàn tất Đặt phòng')
@section('nav-mode', 'solid')

@push('scripts')
    @vite('resources/js/payment-success.js')
@endpush

@section('content')
<div class="bg-slate-50 min-h-screen py-12 flex items-center justify-center"
     data-home-url="{{ url('/') }}"
     data-countdown-seconds="10">
	    <div class="max-w-[500px] w-full px-6">
	        
	        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-10 text-center">
            
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-4xl text-green-500">check_circle</span>
            </div>

            @if($booking->status === 'confirmed')
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Thanh toán thành công!</h1>
            @else
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Đã ghi nhận yêu cầu!</h1>
            @endif
            
            <p class="text-slate-500 mb-8 leading-relaxed">{{ $message ?? 'Yêu cầu của bạn đã được ghi nhận. Chúng tôi sẽ xử lý trong thời gian sớm nhất.' }}</p>

            <div class="bg-slate-50 rounded-xl p-4 mb-8 text-left">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-slate-500">Mã đơn hàng</span>
                    <span class="font-bold text-slate-800">{{ $booking->booking_code }}</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-slate-500">Ngày đặt</span>
                    <span class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</span>
                </div>
	            <div class="flex justify-between items-center">
	                <span class="text-sm text-slate-500">Trạng thái thanh toán</span>
	                @if($booking->payment && $booking->payment->payment_status === 'completed')
	                    <span class="font-medium text-green-600 flex items-center gap-1">
	                        <span class="material-symbols-outlined text-sm">check_circle</span> Đã thanh toán
	                    </span>
	                @else
                        <span class="font-medium text-orange-500 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">pending</span> Chờ xác nhận
                        </span>
                    @endif
                </div>
            </div>

            <a href="{{ url('/') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary py-4 text-sm font-bold text-white transition-all hover:bg-primary-dark">
                Trở về trang chủ
            </a>
	
	            <p class="text-xs text-slate-400 mt-4">Hệ thống sẽ tự động chuyển về trang chủ sau <span id="countdown-num" class="font-bold text-primary">10</span> giây...</p>
	            
	        </div>

    </div>
	</div>
@endsection
