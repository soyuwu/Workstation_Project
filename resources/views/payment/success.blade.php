@extends('layouts.app')

@section('title', 'Hoàn tất Đặt phòng')
@section('nav-mode', 'solid')

@section('content')
<div class="bg-slate-50 min-h-screen py-12 flex items-center justify-center">
    <div class="max-w-[500px] w-full px-6">
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-10 text-center">
            
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-4xl text-green-500">check_circle</span>
            </div>

            <h1 class="text-2xl font-bold text-slate-800 mb-2">Đã ghi nhận yêu cầu!</h1>
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
                    <span class="font-medium text-orange-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">pending</span> Chờ xác nhận
                    </span>
                </div>
            </div>

            <a href="{{ url('/') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary py-4 text-sm font-bold text-white transition-all hover:bg-primary-dark">
                Trở về trang chủ
            </a>
            
        </div>

    </div>
</div>
@endsection
