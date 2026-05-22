@extends('layouts.app')

@section('title', 'Xác nhận đặt chỗ theo tháng')
@section('nav-mode', 'solid')

@section('content')
<div class="bg-slate-50 min-h-screen py-12">
    <div class="mx-auto max-w-[1200px] px-6">
        
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-slate-500 hover:text-primary mb-8 transition-colors">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span class="font-medium text-sm">Quay lại chọn không gian</span>
        </a>

        @if(session('error'))
            <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Cột Trái: Chi tiết không gian (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                    <div class="aspect-[16/9] w-full overflow-hidden rounded-xl bg-slate-100 mb-6 relative">
                        <img src="{{ $room['image'] ?? 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200&auto=format&fit=crop' }}"
                             alt="Hình ảnh không gian" class="h-full w-full object-cover">
                        <div class="absolute top-4 left-4 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm">
                            Có sẵn
                        </div>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $room['name'] }}</h1>
                    <div class="flex items-center gap-4 text-sm text-slate-500 mb-6">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">group</span> {{ $room['capacity'] }}</span>
                        <span class="flex items-center gap-1 text-primary font-medium">
                            <span class="material-symbols-outlined text-base">payments</span>
                            {{ number_format($room['price_raw']) }} VNĐ/tháng
                        </span>
                    </div>

                    <div class="border-t border-slate-100 pt-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Tiện ích bao gồm</h3>
                        <ul class="space-y-3 text-slate-600 text-sm">
                            <li class="flex items-start gap-3"><span class="material-symbols-outlined text-primary text-xl">check_circle</span> Wifi tốc độ cao Fiber 1Gbps, backup 4G.</li>
                            <li class="flex items-start gap-3"><span class="material-symbols-outlined text-primary text-xl">check_circle</span> Tủ khóa cá nhân, gửi đồ qua đêm an toàn.</li>
                            <li class="flex items-start gap-3"><span class="material-symbols-outlined text-primary text-xl">check_circle</span> Trà, cà phê, nước lọc miễn phí không giới hạn.</li>
                            <li class="flex items-start gap-3"><span class="material-symbols-outlined text-primary text-xl">check_circle</span> Điều hòa riêng, môi trường thoải mái 24/7.</li>
                            <li class="flex items-start gap-3"><span class="material-symbols-outlined text-primary text-xl">check_circle</span> Ưu tiên đặt phòng họp với giá thành viên.</li>
                        </ul>
                    </div>
                </div>

                <!-- Chính sách -->
                <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Chính sách thuê tháng</h3>
                    <div class="rounded-xl bg-orange-50 p-4 border border-orange-100 text-sm text-orange-800 leading-relaxed space-y-2">
                        <p><strong>Thanh toán:</strong> Thanh toán 100% giá trị để kích hoạt hợp đồng thuê.</p>
                        <p><strong>Gia hạn:</strong> Hệ thống sẽ nhắc nhở trước 7 ngày khi hợp đồng sắp hết hạn.</p>
                        <p><strong>Hủy hợp đồng:</strong> Hoàn 70% nếu hủy trước 15 ngày kể từ ngày bắt đầu. Không hoàn tiền sau đó.</p>
                    </div>
                </div>
            </div>

            <!-- Cột Phải: Form thanh toán (1/3) -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 rounded-2xl bg-white p-6 shadow-lg border border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800 mb-6">Thông tin thanh toán</h2>
                    
                    <!-- Chi tiết đơn hàng -->
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 mb-6 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 font-medium">Không gian</span>
                            <span class="text-sm font-bold text-slate-800">{{ $room['name'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 font-medium">Ngày bắt đầu</span>
                            <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 font-medium">Ngày kết thúc</span>
                            <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($startDate)->addMonths($durationMonths)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 font-medium">Thời hạn</span>
                            <span class="text-sm font-bold text-slate-800">{{ $durationMonths }} tháng</span>
                        </div>
                    </div>

                    <!-- Tính tiền -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Giá thuê ({{ $durationMonths }} tháng)</span>
                            <span class="font-medium text-slate-800">{{ number_format($subtotal) }} VNĐ</span>
                        </div>
                        @if($discount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Giảm giá ({{ $discountPercent }}%)</span>
                            <span class="font-medium text-green-600">- {{ number_format($discount) }} VNĐ</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Thuế VAT (8%)</span>
                            <span class="font-medium text-slate-800">{{ number_format($tax) }} VNĐ</span>
                        </div>
                        <div class="border-t border-slate-200 pt-4 flex justify-between items-center">
                            <span class="text-base font-bold text-slate-800">Tổng thanh toán</span>
                            <span class="text-2xl font-bold text-primary">{{ number_format($total) }} VNĐ</span>
                        </div>
                    </div>

                    <!-- Form chọn phương thức thanh toán -->
                    <form action="{{ route('booking.monthly.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room['id'] }}">
                        <input type="hidden" name="room_price" value="{{ $room['price_raw'] }}">
                        <input type="hidden" name="room_name" value="{{ $room['name'] }}">
                        <input type="hidden" name="room_image" value="{{ $room['image'] }}">
                        <input type="hidden" name="room_capacity" value="{{ $room['capacity'] }}">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="duration_months" value="{{ $durationMonths }}">
                        <input type="hidden" name="payment_method" value="bank_transfer">

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
