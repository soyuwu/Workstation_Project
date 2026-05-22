@extends('layouts.app')

@section('title', 'Cổng thanh toán giả lập')

@section('content')
<div class="pt-28 pb-16 bg-slate-50/50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4">
        
        {{-- Back Button --}}
        <a href="{{ route('profile') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 text-sm font-semibold mb-6 transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Quay lại trang cá nhân
        </a>

        {{-- Main Container --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_15px_50px_rgba(0,0,0,0.03)] overflow-hidden">
            
            {{-- Header info --}}
            <div class="bg-gradient-to-r from-primary to-blue-600 p-6 text-white relative">
                <div class="absolute right-6 top-6 opacity-10">
                    <span class="material-symbols-outlined text-8xl">payments</span>
                </div>
                <div class="space-y-1">
                    <span class="inline-block px-2.5 py-0.5 bg-white/20 backdrop-blur-sm text-[10px] font-bold rounded-lg tracking-wider uppercase">Thanh toán đặt chỗ</span>
                    <h2 class="font-headline font-bold text-2xl">Cổng Thanh Toán Giả Lập</h2>
                    <p class="text-xs text-blue-100 mt-1">Vui lòng hoàn tất thanh toán để kích hoạt lịch đặt chỗ của bạn.</p>
                </div>
            </div>

            {{-- Summary of Booking details --}}
            <div class="p-6 bg-slate-50/50 border-b border-slate-100 grid grid-cols-2 gap-4 text-sm font-medium text-slate-600">
                <div class="space-y-1">
                    <div class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Đặt chỗ</div>
                    <div class="text-slate-800 font-bold">{{ $payment->booking->booking_code }}</div>
                    <div class="text-xs text-slate-500">{{ $payment->booking->workspace->name ?? 'Workspace' }}</div>
                </div>
                <div class="space-y-1 text-right">
                    <div class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Tổng số tiền thanh toán</div>
                    <div class="text-primary text-xl font-black">{{ number_format($payment->final_amount, 0, ',', '.') }}đ</div>
                    <div class="text-xs text-slate-400">Đã bao gồm VAT & giảm giá</div>
                </div>
            </div>

            {{-- Tab selector for Payment Methods --}}
            <div class="p-6">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Chọn phương thức thanh toán giả lập</label>
                <div class="grid grid-cols-2 gap-4 mb-8">
                    {{-- VietQR Tab Button --}}
                    <button type="button" onclick="setPaymentMethod('bank_transfer')" id="btn-method-bank" 
                        class="p-4 rounded-2xl border-2 transition-all flex flex-col items-center justify-center gap-2 bg-primary/5 border-primary text-primary">
                        <span class="material-symbols-outlined text-3xl font-bold">account_balance</span>
                        <span class="text-xs font-bold">Chuyển khoản VietQR</span>
                    </button>
                    {{-- MoMo Tab Button --}}
                    <button type="button" onclick="setPaymentMethod('momo')" id="btn-method-momo" 
                        class="p-4 rounded-2xl border-2 transition-all flex flex-col items-center justify-center gap-2 border-slate-100 hover:border-slate-200 text-slate-500 bg-white">
                        <span class="material-symbols-outlined text-3xl font-bold">phone_iphone</span>
                        <span class="text-xs font-bold">Ví điện tử MoMo</span>
                    </button>
                </div>

                {{-- FORM FOR CONFIRMATION --}}
                <form id="payment-form" action="{{ route('profile.payment.confirm', $payment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" id="selected-method" value="bank_transfer">

                    {{-- VIEW METHOD 1: VIETQR --}}
                    <div id="method-bank-content" class="block space-y-6 animate-fade-in">
                        <div class="flex flex-col md:flex-row items-center gap-8 bg-slate-50 rounded-2xl p-5 border border-slate-100">
                            {{-- Mock QR --}}
                            <div class="bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm relative shrink-0">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&color=2563eb&data={{ urlencode('STB:0011223344-Amount:' . $payment->final_amount . '-Ref:' . $payment->booking->booking_code) }}" 
                                    alt="VietQR Mock Code" class="w-36 h-36">
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-8 h-8 rounded-lg bg-white shadow-md border border-slate-100 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-primary text-sm font-black">account_balance</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Bank Details --}}
                            <div class="flex-1 space-y-3.5 w-full text-sm">
                                <div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Ngân hàng thụ hưởng</div>
                                    <div class="font-bold text-slate-800">Sacombank (Ngân hàng TMCP Sài Gòn Thương Tín)</div>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Số tài khoản</div>
                                        <div class="font-black text-slate-800 tracking-wider text-base" id="bank-acc">0011223344</div>
                                    </div>
                                    <button type="button" onclick="copyToClipboard('0011223344', this, 'primary')" class="inline-flex items-center gap-1 text-primary text-xs font-bold hover:underline">
                                        <span class="material-symbols-outlined text-sm font-bold">content_copy</span> Sao chép
                                    </button>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tên chủ tài khoản</div>
                                        <div class="font-bold text-slate-800 uppercase">CÔNG TY CỔ PHẦN WORKSTATION</div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Nội dung chuyển khoản</div>
                                        <div class="font-black text-primary tracking-widest text-base" id="bank-memo">{{ $payment->booking->booking_code }}</div>
                                    </div>
                                    <button type="button" onclick="copyToClipboard('{{ $payment->booking->booking_code }}', this, 'primary')" class="inline-flex items-center gap-1 text-primary text-xs font-bold hover:underline">
                                        <span class="material-symbols-outlined text-sm font-bold">content_copy</span> Sao chép
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Instructions --}}
                        <div class="bg-blue-50/50 rounded-2xl p-4 border border-blue-100 flex items-start gap-3 text-xs text-blue-800">
                            <span class="material-symbols-outlined text-[20px] text-primary shrink-0 mt-0.5">info</span>
                            <div class="space-y-1">
                                <p class="font-bold">Hướng dẫn thanh toán:</p>
                                <ol class="list-decimal pl-4 space-y-0.5 text-slate-600 font-medium">
                                    <li>Mở ứng dụng ngân hàng của bạn trên điện thoại di động.</li>
                                    <li>Chọn chức năng quét mã QR và quét mã phía trên.</li>
                                    <li>Hoặc chuyển khoản trực tiếp theo thông tin tài khoản thụ hưởng.</li>
                                    <li>Đảm bảo ghi chính xác <span class="font-bold text-primary">{{ $payment->booking->booking_code }}</span> vào phần nội dung giao dịch.</li>
                                    <li>Bấm nút <span class="font-bold text-slate-850">"Xác nhận đã thanh toán"</span> bên dưới sau khi đã chuyển khoản thành công.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    {{-- VIEW METHOD 2: MOMO --}}
                    <div id="method-momo-content" class="hidden space-y-6 animate-fade-in">
                        <div class="flex flex-col md:flex-row items-center gap-8 bg-pink-50/30 rounded-2xl p-5 border border-pink-100">
                            {{-- Mock QR --}}
                            <div class="bg-white border border-pink-200/50 rounded-2xl p-4 shadow-sm relative shrink-0">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&color=d81b60&data={{ urlencode('MomoPhone:0987654321-Amount:' . $payment->final_amount . '-Ref:' . $payment->booking->booking_code) }}" 
                                    alt="MoMo Mock Code" class="w-36 h-36">
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-8 h-8 rounded-lg bg-pink-600 flex items-center justify-center text-white font-extrabold text-[10px]">
                                        MoMo
                                    </div>
                                </div>
                            </div>

                            {{-- MoMo Details --}}
                            <div class="flex-1 space-y-3.5 w-full text-sm">
                                <div>
                                    <div class="text-[10px] text-pink-600 font-bold uppercase tracking-wider">Ví điện tử liên kết</div>
                                    <div class="font-bold text-slate-800">Ví MoMo chính thức</div>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-[10px] text-pink-600 font-bold uppercase tracking-wider">Số điện thoại MoMo</div>
                                        <div class="font-black text-slate-800 tracking-wider text-base">0987 654 321</div>
                                    </div>
                                    <button type="button" onclick="copyToClipboard('0987654321', this, 'momo')" class="inline-flex items-center gap-1 text-pink-600 text-xs font-bold hover:underline">
                                        <span class="material-symbols-outlined text-sm font-bold">content_copy</span> Sao chép
                                    </button>
                                </div>

                                <div>
                                    <div class="text-[10px] text-pink-600 font-bold uppercase tracking-wider">Tên chủ tài khoản</div>
                                    <div class="font-bold text-slate-800 uppercase">WORKSTATION CO. LTD</div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-[10px] text-pink-600 font-bold uppercase tracking-wider">Nội dung chuyển ví</div>
                                        <div class="font-black text-pink-700 tracking-widest text-base">{{ $payment->booking->booking_code }}</div>
                                    </div>
                                    <button type="button" onclick="copyToClipboard('{{ $payment->booking->booking_code }}', this, 'momo')" class="inline-flex items-center gap-1 text-pink-600 text-xs font-bold hover:underline">
                                        <span class="material-symbols-outlined text-sm font-bold">content_copy</span> Sao chép
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Instructions --}}
                        <div class="bg-pink-50 rounded-2xl p-4 border border-pink-100 flex items-start gap-3 text-xs text-pink-850">
                            <span class="material-symbols-outlined text-[20px] text-pink-600 shrink-0 mt-0.5">info</span>
                            <div class="space-y-1">
                                <p class="font-bold">Hướng dẫn thanh toán bằng MoMo:</p>
                                <ol class="list-decimal pl-4 space-y-0.5 text-slate-650 font-medium">
                                    <li>Mở ứng dụng ví MoMo trên điện thoại di động của bạn.</li>
                                    <li>Bấm chọn mục <span class="font-bold">"Quét mã"</span> góc trên màn hình và quét mã phía trên.</li>
                                    <li>Hoặc thực hiện chức năng <span class="font-bold">"Chuyển tiền đến SĐT ví"</span>.</li>
                                    <li>Điền chính xác số tiền <span class="font-bold text-pink-700">{{ number_format($payment->final_amount, 0, ',', '.') }}đ</span> và lời nhắn: <span class="font-bold text-pink-700">{{ $payment->booking->booking_code }}</span>.</li>
                                    <li>Bấm nút <span class="font-bold">"Xác nhận đã thanh toán"</span> bên dưới để hoàn tất giao dịch trong tích tắc.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Action --}}
                    <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('profile') }}" class="flex-1 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-2xl transition-all text-center active:scale-95">
                            Thanh toán sau
                        </a>
                        <button type="submit" class="flex-1 py-3.5 bg-primary text-white text-sm font-bold rounded-2xl hover:opacity-95 shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                            <span class="material-symbols-outlined text-sm font-bold">verified</span>
                            Xác nhận đã thanh toán
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

@vite(['resources/css/profile.css', 'resources/js/profile.js'])
@endsection
