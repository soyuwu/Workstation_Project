@extends('layouts.app')

@section('title', 'Lịch sử đặt chỗ')
@section('nav-mode', 'solid')

@section('content')
    <x-common.sub-page-hero
        icon="history"
        subtitle="Tài khoản"
        :title="'Lịch sử <span class=&quot;text-primary&quot;>đặt chỗ</span>'"
        description="Danh sách các lượt đặt chỗ của bạn."
    />

    @php
        $bookingStatusMap = [
            'pending' => ['label' => 'Chờ xử lý', 'class' => 'bg-amber-50 text-amber-700 ring-amber-100'],
            'confirmed' => ['label' => 'Đã xác nhận', 'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-100'],
            'completed' => ['label' => 'Hoàn thành', 'class' => 'bg-sky-50 text-sky-700 ring-sky-100'],
            'cancelled' => ['label' => 'Đã hủy', 'class' => 'bg-rose-50 text-rose-700 ring-rose-100'],
        ];

        $paymentStatusMap = [
            'pending' => ['label' => 'Chưa thanh toán', 'class' => 'bg-slate-50 text-slate-700 ring-slate-100'],
            'reported' => ['label' => 'Chờ duyệt CK', 'class' => 'bg-amber-50 text-amber-700 ring-amber-100'],
            'completed' => ['label' => 'Đã thanh toán', 'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-100'],
            'failed' => ['label' => 'Thất bại', 'class' => 'bg-rose-50 text-rose-700 ring-rose-100'],
            'cancelled' => ['label' => 'Đã hủy', 'class' => 'bg-rose-50 text-rose-700 ring-rose-100'],
        ];
    @endphp

    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-12">
            <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-headline text-xl font-bold text-on-surface">Đơn đặt chỗ</h2>
                        <p class="mt-1 text-sm text-slate-500">Bạn có thể xem trạng thái thanh toán và xác nhận.</p>
                    </div>
                    <a href="{{ route('account.profile') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                        Hồ sơ cá nhân
                    </a>
                </div>

                <div class="mt-8 overflow-x-auto rounded-2xl ring-1 ring-slate-100">
                    <table class="min-w-[900px] w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4">Mã đơn</th>
                                <th class="px-5 py-4">Không gian</th>
                                <th class="px-5 py-4">Thời gian</th>
                                <th class="px-5 py-4">Tổng tiền</th>
                                <th class="px-5 py-4">Trạng thái</th>
                                <th class="px-5 py-4">Thanh toán</th>
                                <th class="px-5 py-4 text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bookings as $booking)
                                @php
                                    $bookingStatus = $bookingStatusMap[$booking->status] ?? ['label' => $booking->status, 'class' => 'bg-slate-50 text-slate-700 ring-slate-100'];
                                    $payment = $booking->payment;
                                    $paymentStatusRaw = $payment?->payment_status ?? 'pending';
                                    if ($paymentStatusRaw === 'pending' && $payment?->payment_method === 'bank_transfer' && $payment?->reported_at) {
                                        $paymentStatusRaw = 'reported';
                                    }
                                    $paymentStatus = $paymentStatusMap[$paymentStatusRaw] ?? ['label' => $paymentStatusRaw, 'class' => 'bg-slate-50 text-slate-700 ring-slate-100'];
                                @endphp
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-5 py-4 font-semibold text-slate-800">{{ $booking->booking_code }}</td>
                                    <td class="px-5 py-4">
                                        <div class="font-medium text-slate-800">{{ $booking->workspace?->name ?? '--' }}</div>
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $booking->workspace?->capacity ? $booking->workspace->capacity . ' người' : '' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-medium text-slate-800">
                                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                                        </div>
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $booking->start_time }} - {{ $booking->end_time }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-slate-800">
                                        {{ number_format((float) $booking->total_amount, 0, ',', '.') }} ₫
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $bookingStatus['class'] }}">
                                            {{ $bookingStatus['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $paymentStatus['class'] }}">
                                            {{ $paymentStatus['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        @if($booking->status === 'pending' && ($payment?->payment_status ?? 'pending') === 'pending' && !$payment?->reported_at)
                                            <a href="{{ route('payment.vietqr', $booking->booking_code) }}"
                                                class="inline-flex items-center justify-center rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-white transition hover:opacity-90">
                                                Thanh toán
                                            </a>
                                        @else
                                            <span class="text-xs font-medium text-slate-400">--</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center text-slate-500">
                                        Bạn chưa có đơn đặt chỗ nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($bookings, 'links'))
                    <div class="mt-8">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
