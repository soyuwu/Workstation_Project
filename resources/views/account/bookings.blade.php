@extends('layouts.app')

@section('title', 'Lịch sử đặt chỗ')
@section('nav-mode', 'solid')

@push('scripts')
    @vite('resources/js/account-bookings.js')
@endpush

@section('content')
    <script type="application/json" id="account-bookings-data">
        @json($bookingDetails ?? [], JSON_UNESCAPED_UNICODE)
    </script>

    <x-common.sub-page-hero
        icon="history"
        subtitle="Tài khoản"
        :title="'Lịch sử <span class=&quot;text-primary&quot;>đặt chỗ</span>'"
        description="Xem chi tiết, thanh toán, hủy đơn và gửi nhận xét sau khi sử dụng phòng."
    />

    @php
        $bookingStatusMap = [
            'pending' => ['label' => 'Chờ thanh toán', 'class' => 'bg-amber-50 text-amber-700 ring-amber-100'],
            'confirmed' => ['label' => 'Đã xác nhận', 'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-100'],
            'completed' => ['label' => 'Đã sử dụng', 'class' => 'bg-sky-50 text-sky-700 ring-sky-100'],
            'cancelled' => ['label' => 'Đã hủy', 'class' => 'bg-rose-50 text-rose-700 ring-rose-100'],
        ];

        $paymentStatusMap = [
            'pending' => ['label' => 'Chưa thanh toán', 'class' => 'bg-slate-50 text-slate-700 ring-slate-100'],
            'reported' => ['label' => 'Chờ duyệt CK', 'class' => 'bg-amber-50 text-amber-700 ring-amber-100'],
            'completed' => ['label' => 'Đã thanh toán', 'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-100'],
            'refunded' => ['label' => 'Đã hoàn tiền', 'class' => 'bg-sky-50 text-sky-700 ring-sky-100'],
            'failed' => ['label' => 'Đã đóng', 'class' => 'bg-rose-50 text-rose-700 ring-rose-100'],
            'cancelled' => ['label' => 'Đã hủy', 'class' => 'bg-rose-50 text-rose-700 ring-rose-100'],
        ];
    @endphp

    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-12">
            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-headline text-xl font-bold text-on-surface">Đơn đặt chỗ</h2>
                        <p class="mt-1 text-sm text-slate-500">Bấm vào một đơn bất kỳ để xem chi tiết, hủy đơn hoặc gửi nhận xét.</p>
                    </div>
                    <a href="{{ route('account.profile') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                        Hồ sơ cá nhân
                    </a>
                </div>

                <div class="mt-8 overflow-x-auto rounded-2xl ring-1 ring-slate-100">
                    <table class="min-w-[980px] w-full text-left text-sm">
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
                                    $detail = $bookingDetails[$booking->id] ?? [];
                                    $bookingStatus = $bookingStatusMap[$booking->status] ?? ['label' => $booking->status, 'class' => 'bg-slate-50 text-slate-700 ring-slate-100'];
                                    $payment = $booking->payment;
                                    $paymentStatusRaw = $payment?->payment_status ?? 'pending';
                                    if ($paymentStatusRaw === 'pending' && $payment?->payment_method === 'bank_transfer' && $payment?->reported_at) {
                                        $paymentStatusRaw = 'reported';
                                    }
                                    $paymentStatus = $paymentStatusMap[$paymentStatusRaw] ?? ['label' => $paymentStatusRaw, 'class' => 'bg-slate-50 text-slate-700 ring-slate-100'];
                                @endphp
                                <tr class="cursor-pointer hover:bg-slate-50/70" data-booking-detail-id="{{ $booking->id }}">
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-800">{{ $booking->booking_code }}</div>
                                        <div class="mt-1 text-xs text-slate-400">Tạo lúc {{ $booking->created_at?->format('d/m/Y H:i') }}</div>
                                    </td>
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
                                        {{ number_format((float) $booking->total_amount, 0, ',', '.') }} VND
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $bookingStatus['class'] }}">
                                            {{ $bookingStatus['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex w-fit items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $paymentStatus['class'] }}">
                                                {{ $paymentStatus['label'] }}
                                            </span>
                                            @if(!empty($detail['payment_deadline']))
                                                <span class="text-xs font-medium text-amber-600" data-payment-deadline="{{ $detail['payment_deadline'] }}">
                                                    Đang tính thời gian
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button type="button"
                                                data-open-booking-detail
                                                data-booking-detail-id="{{ $booking->id }}"
                                                class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-200">
                                                Chi tiết
                                            </button>
                                            @if(!empty($detail['can_pay']))
                                                <a href="{{ $detail['pay_url'] }}"
                                                    class="inline-flex items-center justify-center rounded-xl bg-primary px-4 py-2 text-xs font-semibold text-white transition hover:opacity-90">
                                                    Thanh toán
                                                </a>
                                            @endif
                                        </div>
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

    <div id="booking-detail-modal" class="fixed inset-0 z-[80] hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
        <div id="booking-detail-panel"
            tabindex="-1"
            role="dialog"
            aria-modal="true"
            class="max-h-[92vh] w-full max-w-4xl overflow-y-auto rounded-3xl bg-white shadow-2xl outline-none">
            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-100 bg-white px-6 py-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Chi tiết đơn hàng</p>
                    <h3 id="modal-booking-code" class="mt-1 font-headline text-2xl font-bold text-on-surface">--</h3>
                </div>
                <button type="button" data-close-booking-modal
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            <div class="grid gap-6 p-6 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="space-y-6">
                    <div class="rounded-2xl border border-slate-100 p-5">
                        <h4 class="font-headline text-lg font-bold text-slate-800">Thông tin sử dụng</h4>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Không gian</div>
                                <div id="modal-booking-workspace" class="mt-1 font-semibold text-slate-800">--</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Sức chứa</div>
                                <div id="modal-booking-capacity" class="mt-1 font-semibold text-slate-800">--</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Ngày đặt</div>
                                <div id="modal-booking-date" class="mt-1 font-semibold text-slate-800">--</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Khung giờ</div>
                                <div id="modal-booking-time" class="mt-1 font-semibold text-slate-800">--</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Trạng thái đơn</div>
                                <div id="modal-booking-status" class="mt-1 font-semibold text-slate-800">--</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tạo lúc</div>
                                <div id="modal-booking-created" class="mt-1 font-semibold text-slate-800">--</div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-100 p-5">
                        <h4 class="font-headline text-lg font-bold text-slate-800">Thanh toan</h4>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Trạng thái</div>
                                <div id="modal-booking-payment-status" class="mt-1 font-semibold text-slate-800">--</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Phương thức</div>
                                <div id="modal-booking-payment-method" class="mt-1 font-semibold text-slate-800">--</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Đã thanh toán lúc</div>
                                <div id="modal-booking-paid-at" class="mt-1 font-semibold text-slate-800">--</div>
                            </div>
                            <div id="modal-booking-deadline-wrap" class="hidden">
                                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Hạn thanh toán</div>
                                <div id="modal-booking-deadline" class="mt-1 font-semibold text-amber-600">--</div>
                            </div>
                        </div>

                        <div class="mt-5 rounded-2xl bg-slate-50 p-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Tạm tính</span>
                                <span id="modal-booking-base" class="font-semibold text-slate-800">--</span>
                            </div>
                            <div class="mt-2 flex justify-between text-sm">
                                <span class="text-slate-500">Thuế</span>
                                <span id="modal-booking-tax" class="font-semibold text-slate-800">--</span>
                            </div>
                            <div class="mt-3 flex justify-between border-t border-slate-200 pt-3">
                                <span class="font-bold text-slate-800">Tổng thanh toán</span>
                                <span id="modal-booking-total" class="font-bold text-primary">--</span>
                            </div>
                        </div>
                    </div>

                    <div id="booking-review-section" class="rounded-2xl border border-slate-100 p-5">
                        <h4 class="font-headline text-lg font-bold text-slate-800">Nhận xét sau khi sử dụng</h4>

                        <form id="booking-review-form" method="POST" class="mt-4 hidden space-y-4">
                            @csrf
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">Đánh giá</label>
                                <select name="rating" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                                    <option value="5">5 sao</option>
                                    <option value="4">4 sao</option>
                                    <option value="3">3 sao</option>
                                    <option value="2">2 sao</option>
                                    <option value="1">1 sao</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-slate-700">Nội dung nhận xét</label>
                                <textarea name="content" rows="4" minlength="10" maxlength="1000" required
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                                    placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
                            </div>
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:opacity-90">
                                Gửi nhận xét
                            </button>
                        </form>

                        <div id="booking-existing-review" class="mt-4 hidden rounded-2xl bg-slate-50 p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-700">Đánh giá của bạn</span>
                                <span id="booking-existing-review-rating" class="text-sm font-bold text-primary">--</span>
                            </div>
                            <p id="booking-existing-review-content" class="mt-3 text-sm leading-relaxed text-slate-600">--</p>
                            <p id="booking-existing-review-created" class="mt-3 text-xs text-slate-400">--</p>
                        </div>

                        <p id="booking-review-unavailable" class="mt-4 hidden rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">
                            Chỉ có thể nhận xét sau khi phòng đã sử dụng xong.
                        </p>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-2xl border border-slate-100 p-5">
                        <h4 class="font-headline text-lg font-bold text-slate-800">Chính sách hủy</h4>
                        <p id="modal-booking-cancel-policy" class="mt-3 text-sm leading-relaxed text-slate-600">--</p>
                        <div class="mt-4 space-y-2 rounded-2xl bg-slate-50 p-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Phí phạt</span>
                                <span id="modal-booking-cancel-fee" class="font-semibold text-rose-600">--</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Dự kiến hoàn</span>
                                <span id="modal-booking-refund" class="font-semibold text-emerald-600">--</span>
                            </div>
                        </div>

                        <a id="booking-modal-pay-link" href="#"
                            class="mt-4 hidden inline-flex w-full items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:opacity-90">
                            Thanh toán ngay
                        </a>

                        <div id="booking-cancel-action" class="mt-3 hidden">
                            <a id="booking-cancel-link" href="#"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
                                Hủy phòng
                            </a>
                        </div>

                        <p id="booking-no-cancel-reason" class="mt-3 hidden rounded-xl bg-slate-50 p-3 text-sm text-slate-500">--</p>
                    </div>

                    <div class="rounded-2xl border border-slate-100 p-5">
                        <h4 class="font-headline text-lg font-bold text-slate-800">Ghi chú</h4>
                        <p id="modal-booking-notes" class="mt-3 text-sm leading-relaxed text-slate-600">--</p>
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection
