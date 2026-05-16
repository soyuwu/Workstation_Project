@extends('layouts.app')

@section('title', 'Thanh toán chuyển khoản VietQR')
@section('nav-mode', 'solid')

@section('content')
<div class="bg-slate-50 min-h-screen py-12">
    <div class="mx-auto max-w-[800px] px-6">
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-primary/5 p-6 text-center border-b border-slate-100">
                <h1 class="text-2xl font-bold text-slate-800">Thanh toán Đặt phòng</h1>
                <p class="text-slate-500 mt-2">Mã đơn hàng: <span class="font-bold text-primary">{{ $booking->booking_code }}</span></p>
            </div>

            <div class="p-8 grid md:grid-cols-2 gap-10">
                <!-- Cột trái: QR Code -->
                <div class="flex flex-col items-center justify-center space-y-4">
                    <div class="p-4 bg-white border-2 border-slate-100 rounded-2xl shadow-sm w-full max-w-[300px]">
                        <img src="{{ $qrUrl }}" alt="VietQR" class="w-full h-auto rounded-xl">
                    </div>
                    <p class="text-sm text-slate-500 text-center italic">Sử dụng App ngân hàng bất kỳ để quét mã QR</p>
                </div>

                <!-- Cột phải: Thông tin -->
                <div class="space-y-6">
                    <div>
                        <p class="text-sm text-slate-500 mb-1">Số tiền cần thanh toán</p>
                        <p class="text-3xl font-bold text-primary">{{ number_format($booking->total_amount) }} VNĐ</p>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-slate-100">
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Ngân hàng thụ hưởng</p>
                            <p class="font-bold text-slate-800">ACB (Ngân hàng Á Châu)</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Tên tài khoản</p>
                            <p class="font-bold text-slate-800">{{ $accountName }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Số tài khoản</p>
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-slate-800 text-lg">{{ $accountNo }}</p>
                                <button class="text-slate-400 hover:text-primary transition-colors" title="Copy">
                                    <span class="material-symbols-outlined text-sm">content_copy</span>
                                </button>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Nội dung chuyển khoản</p>
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-slate-800 bg-slate-100 px-3 py-1.5 rounded-lg">{{ $booking->booking_code }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 p-6 border-t border-slate-100">
                <form action="{{ route('payment.vietqr.confirm', $booking->booking_code) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl bg-primary py-4 text-base font-bold text-white transition-all hover:bg-primary-dark hover:shadow-lg">
                        <span class="material-symbols-outlined">check_circle</span>
                        Tôi đã chuyển khoản thành công
                    </button>
                </form>
                <p class="text-xs text-center text-slate-400 mt-4">Vui lòng chỉ bấm nút sau khi bạn đã thực hiện chuyển tiền thành công.</p>
            </div>
        </div>

    </div>
</div>
@endsection
