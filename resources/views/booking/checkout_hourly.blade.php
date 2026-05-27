@extends('layouts.app')

@section('title', 'Xác nhận đặt phòng')
@section('nav-mode', 'solid')

@section('content')
<div class="bg-slate-50 min-h-screen py-12">
    <div class="mx-auto max-w-[1200px] px-6">
        
        <!-- Nút Back -->
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-slate-500 hover:text-primary mb-8 transition-colors">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span class="font-medium text-sm">Quay lại chọn giờ</span>
        </a>

        @if(session('error'))
            <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Cột Trái: Chi tiết phòng (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Hình ảnh phòng -->
                <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                    <div class="aspect-[16/9] w-full overflow-hidden rounded-xl bg-slate-100 mb-6 relative">
                        <img src="{{ $room['image'] ?? 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200&auto=format&fit=crop' }}" alt="Hình ảnh phòng" class="h-full w-full object-cover">
                        <div class="absolute top-4 left-4 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm">
                            Có sẵn
                        </div>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $room['name'] ?? 'Tên phòng' }}</h1>
                    <div class="flex items-center gap-4 text-sm text-slate-500 mb-6">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">group</span> {{ $room['capacity'] ?? 'N/A' }}</span>
                        <span class="flex items-center gap-1 text-primary font-medium"><span class="material-symbols-outlined text-base">payments</span> {{ number_format($room['price'] ?? 0) }} VNĐ/h</span>
                    </div>

                    <div class="border-t border-slate-100 pt-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Mô tả chi tiết</h3>
                        <ul class="space-y-3 text-slate-600 text-sm">
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                                Trang bị màn hình TV, máy chiếu phục vụ trình chiếu.
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                                Cung cấp thêm văn phòng phẩm nếu cần.
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                                Tường cách âm và điều hòa riêng biệt có thể điều chỉnh.
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                                Kết nối Internet Wifi tốc độ cao.
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                                Trà, cà phê, nước lọc phục vụ tự do ở khu vực pantry.
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Chính sách -->
                <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Chính sách & Hủy đặt phòng</h3>
                    <div class="rounded-xl bg-orange-50 p-4 border border-orange-100 text-sm text-orange-800 leading-relaxed">
                        <p class="mb-2"><strong>Quy định thanh toán:</strong> Bạn cần thanh toán 100% giá trị đặt phòng để giữ chỗ.</p>
                        <p><strong>Chính sách hủy:</strong> Hoàn 100% nếu hủy trước 24 giờ. Không hoàn tiền nếu hủy trong vòng 24 giờ trước giờ bắt đầu.</p>
                    </div>
                </div>
            </div>

            <!-- Cột Phải: Form đặt phòng & Thanh toán (1/3) -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 rounded-2xl bg-white p-6 shadow-lg border border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800 mb-6">Thông tin thanh toán</h2>
                    
                    <!-- Thời gian đã chọn -->
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 mb-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 font-medium">Phòng</span>
                            <span class="text-sm font-bold text-slate-800">{{ $room['name'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 font-medium">Ngày đặt</span>
                            <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 font-medium">Khung giờ</span>
                            <div class="text-right">
                                <span class="text-sm font-bold text-slate-800">{{ $startTime }} - {{ $endTime }}</span>
                                <p class="text-xs text-slate-500 mt-1">({{ $duration }} giờ)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Mã giảm giá -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Mã giảm giá (Tùy chọn)</label>
                        <div class="flex gap-2">
                            <input type="text" id="discount-code-input" placeholder="Nhập mã giảm giá..." class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" value="{{ old('discount_code') }}">
                            <button type="button" id="apply-discount-btn" class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition">Áp dụng</button>
                        </div>
                        <div id="discount-message" class="text-xs mt-2 font-medium hidden"></div>
                    </div>

                    <!-- Tính tiền -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Tạm tính ({{ $duration }} giờ)</span>
                            <span class="font-medium text-slate-800" id="summary-subtotal" data-value="{{ $subtotal }}">{{ number_format($subtotal) }} VNĐ</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Thuế VAT (8%)</span>
                            <span class="font-medium text-slate-800" id="summary-tax" data-value="{{ $tax }}">{{ number_format($tax) }} VNĐ</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Giảm giá</span>
                            <span class="font-medium text-green-600" id="summary-discount" data-value="0">- 0 VNĐ</span>
                        </div>
                        <div class="border-t border-slate-200 pt-4 flex justify-between items-center">
                            <span class="text-base font-bold text-slate-800">Tổng thanh toán</span>
                            <span class="text-2xl font-bold text-primary" id="summary-total" data-value="{{ $total }}">{{ number_format($total) }} VNĐ</span>
                        </div>
                    </div>

                    <!-- Phương thức thanh toán -->
                    <form id="checkout-form" action="{{ route('booking.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $roomId }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        <input type="hidden" name="start_time" value="{{ $startTime }}">
                        <input type="hidden" name="end_time" value="{{ $endTime }}">
                        <input type="hidden" name="payment_method" value="bank_transfer">
                        <input type="hidden" name="discount_code" id="hidden-discount-code" value="{{ old('discount_code') }}">

                        <!-- Thông báo phương thức thanh toán -->
                        <div class="mb-6 flex items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 p-4">
                            <span class="material-symbols-outlined text-blue-500 text-2xl">account_balance</span>
                            <div>
                                <p class="font-semibold text-sm text-slate-800">Chuyển khoản VietQR</p>
                                <p class="text-xs text-slate-500 mt-0.5">Quét mã QR bằng App ngân hàng bất kỳ – tự động điền số tiền và nội dung.</p>
                            </div>
                        </div>

                        <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl bg-primary py-4 text-sm font-bold text-white transition-all hover:bg-primary-dark hover:shadow-lg">
                            <span class="material-symbols-outlined text-lg">qr_code_2</span>
                            Tiến hành thanh toán VietQR
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/checkout-discount.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        initCheckoutDiscount({
            applyDiscountUrl: "{{ route('booking.apply-discount') }}",
            csrfToken: "{{ csrf_token() }}",
            workspaceId: "{{ $roomId }}",
            voucherDiscountSelector: '#summary-discount'
        });

        // Chống nhấp đúp khi tiến hành thanh toán
        const checkoutForm = document.getElementById('checkout-form');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function () {
                const submitBtn = checkoutForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">hourglass_empty</span> Đang xử lý...';
                }
            });
        }
    });
</script>
@endpush
