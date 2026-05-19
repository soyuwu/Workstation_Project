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
                <!-- Cột trái: Danh sách không gian mẫu -->
                <div class="lg:col-span-2 space-y-6">
                    <h2 class="font-headline text-2xl font-bold text-on-surface mb-6">Chọn không gian phù hợp</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($rooms as $room)
                            <article class="group relative overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-[var(--shadow-card-hover)] hover:border-primary/30 cursor-pointer" onclick="selectRoom(event, '{{ $room['name'] }}', '{{ $room['price'] }}')">
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
                        <h3 class="font-headline text-xl font-bold text-on-surface mb-2">Thông tin đặt chỗ</h3>
                        <p class="text-sm text-slate-500 mb-6">Vui lòng điền thông tin để WorkStation liên hệ tư vấn và xác nhận.</p>

                        <form action="#" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Không gian đã chọn</label>
                                <input type="text" id="selected_room" name="selected_room" readonly placeholder="Vui lòng chọn không gian bên trái" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Họ và tên</label>
                                <input type="text" name="name" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Số điện thoại</label>
                                    <input type="tel" name="phone" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                                    <input type="email" name="email" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                                </div>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Ngày bắt đầu dự kiến</label>
                                <input type="date" name="start_date" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Thời gian thuê (Tháng)</label>
                                <select name="duration" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                                    <option value="1">1 Tháng</option>
                                    <option value="3">3 Tháng (Giảm 5%)</option>
                                    <option value="6">6 Tháng (Giảm 10%)</option>
                                    <option value="12">12 Tháng (Giảm 15%)</option>
                                </select>
                            </div>

                            <button type="submit" class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3.5 text-sm font-semibold text-white transition-all hover:bg-primary-dark hover:shadow-lg">
                                Gửi yêu cầu đặt chỗ
                                <span class="material-symbols-outlined text-base">send</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>

    @vite('resources/js/booking-monthly.js')
@endsection
