@extends('layouts.app')

@section('title', 'Hủy phòng')
@section('nav-mode', 'solid')

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reasonInputs = document.querySelectorAll('input[name="cancellation_reason_codes[]"]');
            const otherWrap = document.getElementById('other-reason-wrap');
            const otherTextarea = document.getElementById('cancellation_reason_detail');

            const syncOtherReason = () => {
                const showOther = Array.from(reasonInputs).some((input) => input.checked && input.value === 'other');

                otherWrap?.classList.toggle('hidden', !showOther);
                if (otherTextarea) {
                    otherTextarea.required = showOther;
                }
            };

            reasonInputs.forEach((input) => input.addEventListener('change', syncOtherReason));
            syncOtherReason();
        });
    </script>
@endpush

@section('content')
    <x-common.sub-page-hero
        icon="cancel"
        subtitle="Lịch sử đặt chỗ"
        :title="'Hủy <span class=&quot;text-primary&quot;>phòng</span>'"
        description="Bổ sung thông tin hoàn tiền và lý do hủy để WorkStation xử lý yêu cầu chính xác hơn."
    />

    <section class="bg-slate-50 py-16">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 lg:grid-cols-[1fr_360px] lg:px-12">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 sm:p-8">
                <div class="flex flex-col gap-3 border-b border-slate-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mã đơn {{ $booking->booking_code }}</p>
                        <h2 class="mt-2 font-headline text-2xl font-bold text-on-surface">Thông tin hủy phòng</h2>
                    </div>
                    <a href="{{ route('account.bookings') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                        Quay lại
                    </a>
                </div>

                <x-common.validation-errors class="mt-6" title="Vui lòng kiểm tra lại thông tin." />

                <form method="POST" action="{{ route('account.bookings.cancel', $booking) }}" class="mt-8 space-y-8">
                    @csrf

                    @if($requiresCancellationDetails)
                    <section>
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined mt-0.5 text-primary">account_balance</span>
                            <div>
                                <h3 class="font-headline text-xl font-bold text-slate-800">Thông tin nhận hoàn tiền</h3>
                                <p class="mt-1 text-sm text-slate-500">Admin sẽ dùng thông tin này để chuyển khoản hoàn tiền sau khi duyệt xử lý.</p>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="refund_receiver_name" class="mb-2 block text-sm font-semibold text-slate-700">Họ và tên người nhận</label>
                                <input id="refund_receiver_name" name="refund_receiver_name" type="text" required maxlength="255"
                                    value="{{ old('refund_receiver_name', $defaultReceiverName) }}"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            </div>

                            <div>
                                <label for="refund_bank_name" class="mb-2 block text-sm font-semibold text-slate-700">Ngân hàng thụ hưởng</label>
                                <select id="refund_bank_name" name="refund_bank_name" required
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                                    <option value="">Chọn ngân hàng</option>
                                    @foreach($bankOptions as $bankCode => $bankName)
                                        <option value="{{ $bankCode }}" @selected(old('refund_bank_name') === $bankCode)>{{ $bankName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="refund_bank_account_number" class="mb-2 block text-sm font-semibold text-slate-700">Số tài khoản ngân hàng</label>
                                <input id="refund_bank_account_number" name="refund_bank_account_number" type="text" required maxlength="32"
                                    inputmode="numeric" value="{{ old('refund_bank_account_number') }}"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                            </div>
                        </div>
                    </section>

                    <section class="border-t border-slate-100 pt-8">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined mt-0.5 text-primary">fact_check</span>
                            <div>
                                <h3 class="font-headline text-xl font-bold text-slate-800">Khảo sát lý do hủy</h3>
                                <p class="mt-1 text-sm text-slate-500">Thông tin này giúp WorkStation cải thiện quy trình đặt chỗ và chất lượng dịch vụ.</p>
                            </div>
                        </div>

                        <div class="mt-5 space-y-3">
                            @php
                                $selectedReasons = old('cancellation_reason_codes', []);
                                $selectedReasons = is_array($selectedReasons) ? $selectedReasons : [$selectedReasons];
                            @endphp
                            @foreach($cancellationReasons as $reasonCode => $reasonLabel)
                                <label class="flex cursor-pointer gap-3 rounded-2xl border border-slate-200 p-4 text-sm text-slate-700 transition hover:border-primary hover:bg-primary-light/40">
                                    <input type="checkbox" name="cancellation_reason_codes[]" value="{{ $reasonCode }}"
                                        @checked(in_array($reasonCode, $selectedReasons, true))
                                        class="mt-0.5 rounded border-slate-300 text-primary focus:ring-primary">
                                    <span>{{ $reasonLabel }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div id="other-reason-wrap" class="mt-4 hidden">
                            <label for="cancellation_reason_detail" class="mb-2 block text-sm font-semibold text-slate-700">Lý do chi tiết</label>
                            <textarea id="cancellation_reason_detail" name="cancellation_reason_detail" rows="4" maxlength="1000"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
                                placeholder="Nhập lý do hủy phòng của bạn...">{{ old('cancellation_reason_detail') }}</textarea>
                        </div>
                    </section>
                    @else
                        <section>
                            <div class="flex items-start gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                                <span class="material-symbols-outlined mt-0.5 text-emerald-600">check_circle</span>
                                <div>
                                    <h3 class="font-headline text-lg font-bold text-emerald-800">Đơn chưa thanh toán</h3>
                                    <p class="mt-1 text-sm leading-relaxed text-emerald-700">
                                        Bạn có thể hủy phòng ngay, không cần nhập thông tin hoàn tiền hoặc khảo sát lý do hủy.
                                    </p>
                                </div>
                            </div>
                        </section>
                    @endif

                    <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                        <a href="{{ route('account.bookings') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                            Không hủy nữa
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
                            Xác nhận hủy phòng
                        </button>
                    </div>
                </form>
            </div>

            <aside class="h-fit rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <h3 class="font-headline text-lg font-bold text-slate-800">Tóm tắt đặt chỗ</h3>

                <div class="mt-5 space-y-4 text-sm">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Không gian</div>
                        <div class="mt-1 font-semibold text-slate-800">{{ $booking->workspace?->name ?? '--' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Thời gian</div>
                        <div class="mt-1 font-semibold text-slate-800">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tổng thanh toán</div>
                        <div class="mt-1 font-semibold text-slate-800">{{ number_format((float) $booking->total_amount, 0, ',', '.') }} VND</div>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-800">Chính sách hủy</p>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $policy['reason'] }}</p>
                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Phí phạt</span>
                            <span class="font-semibold text-rose-600">{{ number_format((float) $policy['fee'], 0, ',', '.') }} VND</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Dự kiến hoàn</span>
                            <span class="font-semibold text-emerald-600">{{ number_format((float) $policy['refund'], 0, ',', '.') }} VND</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection
