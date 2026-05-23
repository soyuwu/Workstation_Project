@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')
@section('nav-mode', 'solid')

@section('content')
    <x-common.sub-page-hero
        icon="person"
        subtitle="Tài khoản"
        :title="'Hồ sơ <span class=&quot;text-primary&quot;>cá nhân</span>'"
        description="Xem thông tin tài khoản của bạn."
    />

    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-4xl px-6 lg:px-12">
            <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-headline text-xl font-bold text-on-surface">Thông tin tài khoản</h2>
                        <p class="mt-1 text-sm text-slate-500">Một số trường có thể được cập nhật sau.</p>
                    </div>
                    <a href="{{ route('account.bookings') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
                        Xem lịch sử đặt chỗ
                    </a>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Họ tên</div>
                        <div class="mt-2 rounded-xl bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Email</div>
                        <div class="mt-2 rounded-xl bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Số điện thoại</div>
                        <div class="mt-2 rounded-xl bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700">
                            {{ $user->phone ?: '--' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Vai trò</div>
                        <div class="mt-2 rounded-xl bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700">
                            {{ $user->role }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

