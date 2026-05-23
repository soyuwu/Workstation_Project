@extends('layouts.app')

@section('title', 'Đặt ' . $serviceInfo->name)
@section('nav-mode', 'solid')

@push('styles')
    @vite('resources/css/booking-hourly.css')
@endpush

@push('scripts')
    @vite('resources/js/booking-hourly.js')
@endpush

@section('content')
    <script type="application/json" id="booking-hourly-confirmed-bookings">
        @json($confirmedBookings ?? [])
    </script>

    <x-common.sub-page-hero
        icon="{{ $serviceInfo->icon }}"
        subtitle="Thuê theo giờ / ngày"
        :title="$serviceInfo->name"
        :description="$serviceInfo->booking_desc"
    />

	    <section class="bg-slate-50 py-12" data-checkout-url="{{ route('booking.checkout') }}">
	        <div class="mx-auto max-w-[1400px] px-6">
            
            <!-- Controls -->
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                <button
                    type="button"
                    id="hourly-confirm-btn"
                    disabled
                    class="w-full sm:w-auto sm:min-w-[340px] inline-flex items-center justify-center sm:justify-start gap-3 rounded-xl bg-primary px-5 py-4 text-white shadow-sm transition hover:bg-primary-dark disabled:opacity-40 disabled:cursor-not-allowed"
                >
                    <span class="material-symbols-outlined text-xl">task_alt</span>
                    <span class="text-left leading-tight">
                        <span class="block text-sm font-bold">Xác nhận khung giờ</span>
                        <span id="hourly-confirm-subtitle" class="block text-xs font-medium text-white/90">Chưa chọn khung giờ</span>
                    </span>
                </button>
            </div>

            <div class="mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                <div class="flex items-center gap-4 w-full sm:w-auto">
	                    <div class="flex flex-col">
	                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Chọn ngày đặt</label>
	                        <input type="date" id="booking_date" min="{{ date('Y-m-d') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
	                    </div>
	                </div>
                <div class="flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-white border border-slate-200"></div>Trống</div>
                    <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-slate-400"></div>Đã đặt</div>
                    <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-slate-300 timeline-legend-unavailable"></div>Không khả dụng</div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden">
                <div class="timeline-container pb-4">
                    
                    <!-- Header (Hours 0-23) -->
                    <div class="timeline-grid timeline-header border-b border-slate-200">
                        <div class="p-4 font-semibold text-slate-700 bg-white sticky left-0 z-20 border-r border-slate-200 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">Danh sách phòng</div>
                        @for($i = 0; $i < 24; $i++)
                            <div class="col-span-2 p-2 border-r border-slate-200 text-center">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00</div>
                        @endfor
                    </div>

                    <!-- Room Tracks -->
                    @foreach($rooms as $room)
                        <div class="timeline-grid group hover:bg-slate-50 transition-colors" data-room-id="{{ $room['id'] }}" data-room-name="{{ $room['name'] }}" data-room-price="{{ $room['price'] }}" data-room-image="{{ $room['image'] }}" data-room-capacity="{{ $room['capacity'] }}">
                            <!-- Room Info Sticky Column -->
                            <div class="p-3 bg-white group-hover:bg-slate-50 sticky left-0 z-10 border-r border-slate-200 flex items-center gap-3 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)] transition-colors">
                                <img src="{{ $room['image'] }}" class="w-10 h-10 rounded-lg object-cover" alt="room">
                                <div>
                                    <h4 class="font-bold text-sm text-on-surface leading-tight">{{ $room['name'] }}</h4>
                                    <p class="text-xs text-slate-500">{{ $room['capacity'] }} • <span class="text-primary font-medium">{{ number_format($room['price']) }}đ/h</span></p>
                                </div>
                            </div>

                            <!-- 48 Time Slots (30 mins each) -->
                            @for($i = 0; $i < 48; $i++)
                                <div class="time-slot {{ $i % 2 !== 0 ? 'hour-mark' : '' }}" data-slot="{{ $i }}" title="{{ floor($i/2) }}:{{ $i%2===0 ? '00' : '30' }} - {{ floor(($i+1)/2) }}:{{ ($i+1)%2===0 ? '00' : '30' }}"></div>
                            @endfor
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </section>
@endsection
