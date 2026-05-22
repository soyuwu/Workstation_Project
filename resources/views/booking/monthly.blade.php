@extends('layouts.app')

@section('title', 'Đặt ' . $serviceInfo->name)
@section('nav-mode', 'solid')

@section('content')
    <x-common.sub-page-hero
        icon="{{ $serviceInfo->icon }}"
        subtitle="Thuê theo tháng"
        :title="$serviceInfo->name"
        :description="$serviceInfo->booking_desc"
    />

    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-12">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Cột trái: Danh sách không gian -->
                <div class="lg:col-span-2 space-y-6">
                    <h2 class="font-headline text-2xl font-bold text-on-surface mb-6">Chọn không gian phù hợp</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($rooms as $room)
                            <article
                                class="group relative overflow-hidden rounded-2xl bg-white shadow-sm border-2 border-slate-100 transition-all duration-300 hover:shadow-[var(--shadow-card-hover)] hover:border-primary/30 cursor-pointer"
                                onclick="selectRoom(event, '{{ $room['id'] }}', '{{ $room['name'] }}', {{ $room['price_raw'] }}, '{{ $room['image'] }}', '{{ $room['capacity'] }}')"
                            >
                                <div class="aspect-[4/3] w-full overflow-hidden">
                                    <img src="{{ $room['image'] }}" alt="{{ $room['name'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                </div>
                                <div class="p-6">
                                    <h3 class="font-headline text-lg font-bold text-on-surface mb-2">{{ $room['name'] }}</h3>
                                    <ul class="space-y-2 mb-4">
                                        <li class="flex items-center gap-2 text-sm text-slate-600">
                                            <span class="material-symbols-outlined text-[16px] text-slate-400">person</span>
                                            Sức chứa: {{ $room['capacity'] }}
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-slate-600">
                                            <span class="material-symbols-outlined text-[16px] text-slate-400">payments</span>
                                            Giá: <span class="font-semibold text-primary">{{ $room['price'] }}</span>
                                        </li>
                                    </ul>
                                    <button type="button" class="w-full rounded-xl bg-slate-100 py-2.5 text-sm font-semibold text-slate-700 transition-colors group-hover:bg-primary group-hover:text-white">
                                        Chọn không gian này
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>

                <!-- Cột phải: Form đặt chỗ -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 rounded-3xl bg-white p-8 shadow-[var(--shadow-card)] border border-slate-100">
                        <h3 class="font-headline text-xl font-bold text-on-surface mb-6">Thông tin đặt chỗ</h3>

                        <form id="monthly-form" action="{{ route('booking.monthly.checkout') }}" method="GET" class="space-y-5">

                            <!-- Hidden inputs được JS điền vào khi chọn phòng -->
                            <input type="hidden" name="room_id" id="form_room_id">
                            <input type="hidden" name="room_price" id="form_room_price">
                            <input type="hidden" name="room_name" id="form_room_name">
                            <input type="hidden" name="room_image" id="form_room_image">
                            <input type="hidden" name="room_capacity" id="form_room_capacity">

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Không gian đã chọn</label>
                                <input type="text" id="selected_room" readonly placeholder="Vui lòng chọn không gian bên trái"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 focus:border-primary focus:outline-none">
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Ngày bắt đầu</label>
                                <input type="date" name="start_date" id="form_start_date" required
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Thời gian thuê</label>
                                <select name="duration_months" id="form_duration"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                                    <option value="1">1 Tháng</option>
                                    <option value="3">3 Tháng (Giảm 5%)</option>
                                    <option value="6">6 Tháng (Giảm 10%)</option>
                                    <option value="12">12 Tháng (Giảm 15%)</option>
                                </select>
                            </div>

                            <!-- Preview giá realtime -->
                            <div id="price-preview" class="hidden rounded-xl bg-primary/5 border border-primary/20 p-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600">Giá thuê</span>
                                    <span id="preview-base" class="font-semibold text-slate-800"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600">Giảm giá</span>
                                    <span id="preview-discount" class="font-semibold text-green-600"></span>
                                </div>
                                <div class="flex justify-between text-sm font-bold border-t border-primary/20 pt-2">
                                    <span class="text-slate-800">Tổng tiền</span>
                                    <span id="preview-total" class="text-primary text-base"></span>
                                </div>
                            </div>

                            <button type="submit" id="submit-btn" disabled
                                class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3.5 text-sm font-semibold text-white transition-all hover:bg-primary-dark hover:shadow-lg disabled:opacity-40 disabled:cursor-not-allowed">
                                Tiến hành đặt chỗ
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        let selectedPrice = 0;
        const discountMap = { 1: 0, 3: 0.05, 6: 0.10, 12: 0.15 };

        function selectRoom(event, id, name, price, image, capacity) {
            selectedPrice = price;
            document.getElementById('form_room_id').value = id;
            document.getElementById('form_room_name').value = name;
            document.getElementById('form_room_price').value = price;
            document.getElementById('form_room_image').value = image;
            document.getElementById('form_room_capacity').value = capacity;
            document.getElementById('selected_room').value = name;

            document.querySelectorAll('article').forEach(el => el.classList.remove('ring-2', 'ring-primary', 'border-primary'));
            event.currentTarget.classList.add('ring-2', 'ring-primary', 'border-primary');

            updatePreview();
            document.getElementById('submit-btn').disabled = false;
        }

        function updatePreview() {
            if (!selectedPrice) return;
            const months = parseInt(document.getElementById('form_duration').value);
            const discount = discountMap[months] || 0;
            const base = selectedPrice * months;
            const discountAmt = base * discount;
            const total = base - discountAmt;

            document.getElementById('price-preview').classList.remove('hidden');
            document.getElementById('preview-base').textContent = base.toLocaleString('vi-VN') + ' VNĐ';
            document.getElementById('preview-discount').textContent = '- ' + discountAmt.toLocaleString('vi-VN') + ' VNĐ';
            document.getElementById('preview-total').textContent = total.toLocaleString('vi-VN') + ' VNĐ';
        }

        document.getElementById('form_duration').addEventListener('change', updatePreview);
    </script>
@endsection
