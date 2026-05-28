@extends('layouts.app')

@section('title', 'Thanh toán VietQR')
@section('nav-mode', 'solid')

@section('content')
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="mx-auto max-w-[800px] px-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-primary/5 p-6 text-center border-b border-slate-100">
                    <h1 class="text-2xl font-bold text-slate-800">VietQR tạm thời chưa khả dụng</h1>
                    <p class="text-slate-500 mt-2">
                        Mã đơn hàng:
                        <span class="font-bold text-primary">{{ $booking->booking_code }}</span>
                    </p>
                </div>

                <div class="p-8 space-y-4 text-slate-700">
                    <p>
                        Hệ thống chưa được cấu hình thông tin VietQR (các biến môi trường <span class="font-semibold">VIETQR_*</span>).
                        Vui lòng liên hệ quản trị viên để được hỗ trợ.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <a
                            href="{{ route('booking.index') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition"
                        >
                            Quay lại trang đặt chỗ
                        </a>

                        @auth
                            <a
                                href="{{ route('account.bookings') }}"
                                class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white hover:bg-primary-dark transition"
                            >
                                Xem lịch sử đặt chỗ
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

