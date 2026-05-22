@extends('layouts.app')

@section('title', 'Trang cá nhân')

@section('content')
<div class="pt-24 pb-16 bg-slate-50/50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
                <span class="material-symbols-outlined text-rose-600">error</span>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-rose-600">error</span>
                    <span class="font-semibold text-sm">Đã có lỗi xảy ra:</span>
                </div>
                <ul class="list-disc pl-5 space-y-1 text-xs text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            {{-- Left Side: Profile Summary --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary to-cyan-400"></div>
                    
                    {{-- User Initials Badge --}}
                    <div class="mx-auto w-24 h-24 rounded-full bg-gradient-to-tr from-primary to-cyan-400 flex items-center justify-center text-white font-extrabold text-3xl shadow-xl border-4 border-white mb-4 transition-transform duration-300 hover:scale-105">
                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                    </div>

                    <h2 class="font-headline font-bold text-lg text-on-surface mb-1">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-400 mb-3">{{ $user->email }}</p>
                    
                    {{-- Account Verification Status --}}
                    <div class="mb-4">
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full border border-emerald-100">
                                <span class="material-symbols-outlined text-sm font-bold">verified</span>
                                Đã kích hoạt email
                            </span>
                        @else
                            <div class="flex flex-col items-center gap-2">
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-amber-700 bg-amber-50 rounded-full border border-amber-100">
                                    <span class="material-symbols-outlined text-sm font-bold">warning</span>
                                    Chưa kích hoạt email
                                </span>
                                <form action="{{ route('profile.verify_quick') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="text-[11px] text-primary font-bold hover:underline">
                                        [Kích hoạt nhanh để test]
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <hr class="border-slate-100 my-4">

                    <div class="space-y-3 text-left">
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <span class="material-symbols-outlined text-[20px] text-slate-400">phone</span>
                            <span>{{ $user->phone ?? 'Chưa cập nhật SĐT' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <span class="material-symbols-outlined text-[20px] text-slate-400">badge</span>
                            <span class="capitalize">{{ $user->role === 'admin' ? 'Quản trị viên' : ($user->role === 'staff' ? 'Nhân viên' : 'Thành viên') }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <span class="material-symbols-outlined text-[20px] text-slate-400">calendar_today</span>
                            <span>Gia nhập: {{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Tab Buttons Panel --}}
                <div class="bg-white rounded-3xl p-3 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                    <nav class="flex flex-col gap-1">
                        <button onclick="switchTab('tab-bookings')" id="btn-tab-bookings" class="tab-btn w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-2xl transition-all duration-200 bg-primary-light text-primary">
                            <span class="flex items-center gap-3">
                                <span class="material-symbols-outlined">history</span>
                                Đặt chỗ của tôi
                            </span>
                            <span class="bg-primary/10 text-primary text-xs px-2 py-0.5 rounded-full font-bold">
                                {{ $upcoming->count() + $active->count() + $past->count() }}
                            </span>
                        </button>
                        
                        <button onclick="switchTab('tab-invoices')" id="btn-tab-invoices" class="tab-btn w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-2xl transition-all duration-200 text-slate-600 hover:bg-slate-50">
                            <span class="flex items-center gap-3">
                                <span class="material-symbols-outlined">receipt_long</span>
                                Hóa đơn & Thanh toán
                            </span>
                            <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full font-bold">
                                {{ $payments->count() }}
                            </span>
                        </button>

                        <button onclick="switchTab('tab-vouchers')" id="btn-tab-vouchers" class="tab-btn w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-2xl transition-all duration-200 text-slate-600 hover:bg-slate-50">
                            <span class="flex items-center gap-3">
                                <span class="material-symbols-outlined">local_activity</span>
                                Ưu đãi / Vouchers
                            </span>
                            <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full font-bold">
                                {{ $vouchers->count() }}
                            </span>
                        </button>

                        <button onclick="switchTab('tab-settings')" id="btn-tab-settings" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-2xl transition-all duration-200 text-slate-600 hover:bg-slate-50">
                            <span class="material-symbols-outlined">manage_accounts</span>
                            Thông tin tài khoản
                        </button>

                        <button onclick="switchTab('tab-security')" id="btn-tab-security" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-2xl transition-all duration-200 text-slate-600 hover:bg-slate-50">
                            <span class="material-symbols-outlined">shield</span>
                            Đổi mật khẩu
                        </button>
                    </nav>
                </div>
            </div>

            {{-- Right Side: Tab Contents --}}
            <div class="lg:col-span-3 space-y-6">
                
                {{-- TAB 1: BOOKING HISTORY --}}
                <div id="tab-bookings" class="tab-content block">
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-6">
                            <h3 class="font-headline font-bold text-xl text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">history</span>
                                Lịch sử đặt chỗ
                            </h3>
                            
                            {{-- Filter Buttons for Bookings --}}
                            <div class="flex bg-slate-100 p-1 rounded-xl text-xs font-semibold">
                                <button onclick="filterBookings('b-upcoming')" id="btn-b-upcoming" class="filter-btn px-4 py-2 rounded-lg bg-white shadow-sm text-primary transition-all">
                                    Sắp diễn ra
                                </button>
                                <button onclick="filterBookings('b-active')" id="btn-b-active" class="filter-btn px-4 py-2 rounded-lg text-slate-500 hover:text-slate-800 transition-all">
                                    Đang sử dụng
                                </button>
                                <button onclick="filterBookings('b-past')" id="btn-b-past" class="filter-btn px-4 py-2 rounded-lg text-slate-500 hover:text-slate-800 transition-all">
                                    Lịch sử
                                </button>
                            </div>
                        </div>

                        {{-- SUB-TAB: UPCOMING --}}
                        <div id="b-upcoming" class="booking-filter-content block space-y-4">
                            @if($upcoming->isEmpty())
                                <div class="text-center py-12">
                                    <span class="material-symbols-outlined text-5xl text-slate-300 mb-2">event_busy</span>
                                    <p class="text-slate-400 text-sm">Không có lịch đặt chỗ sắp diễn ra.</p>
                                    <a href="{{ route('booking.index') }}" class="inline-block mt-4 px-6 py-2.5 bg-primary text-white text-xs font-semibold rounded-xl hover:opacity-90 transition-opacity">Đặt chỗ ngay</a>
                                </div>
                            @else
                                @foreach($upcoming as $b)
                                    @include('profile.partials.booking_card', ['booking' => $b, 'type' => 'upcoming'])
                                @endforeach
                            @endif
                        </div>

                        {{-- SUB-TAB: ACTIVE --}}
                        <div id="b-active" class="booking-filter-content hidden space-y-4">
                            @if($active->isEmpty())
                                <div class="text-center py-12">
                                    <span class="material-symbols-outlined text-5xl text-slate-300 mb-2">ambient_screen</span>
                                    <p class="text-slate-400 text-sm">Hiện tại không có gói giờ đặt hoặc phòng họp đang sử dụng.</p>
                                </div>
                            @else
                                @foreach($active as $b)
                                    @include('profile.partials.booking_card', ['booking' => $b, 'type' => 'active'])
                                @endforeach
                            @endif
                        </div>

                        {{-- SUB-TAB: PAST --}}
                        <div id="b-past" class="booking-filter-content hidden space-y-4">
                            @if($past->isEmpty())
                                <div class="text-center py-12">
                                    <span class="material-symbols-outlined text-5xl text-slate-300 mb-2">history</span>
                                    <p class="text-slate-400 text-sm">Lịch sử đặt chỗ trống.</p>
                                </div>
                            @else
                                @foreach($past as $b)
                                    @include('profile.partials.booking_card', ['booking' => $b, 'type' => 'past'])
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TAB 2: INVOICES & PAYMENTS --}}
                <div id="tab-invoices" class="tab-content hidden">
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                        <div class="border-b border-slate-100 pb-5 mb-6">
                            <h3 class="font-headline font-bold text-xl text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">receipt_long</span>
                                Lịch sử giao dịch & Hóa đơn
                            </h3>
                        </div>

                        @if($payments->isEmpty())
                            <div class="text-center py-12">
                                <span class="material-symbols-outlined text-5xl text-slate-300 mb-2">payments</span>
                                <p class="text-slate-400 text-sm">Bạn chưa có lịch sử giao dịch thanh toán.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase">
                                            <th class="py-4 px-3">Mã GD</th>
                                            <th class="py-4 px-3">Đặt chỗ</th>
                                            <th class="py-4 px-3">Số tiền</th>
                                            <th class="py-4 px-3">Cổng thanh toán</th>
                                            <th class="py-4 px-3">Trạng thái</th>
                                            <th class="py-4 px-3 text-right">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50 text-sm">
                                        @foreach($payments as $p)
                                            <tr>
                                                <td class="py-4 px-3 font-semibold text-slate-700">
                                                    {{ $p->transaction_code ?? 'Chưa tạo mã' }}
                                                </td>
                                                <td class="py-4 px-3">
                                                    <div class="font-medium text-slate-800">{{ $p->booking->booking_code }}</div>
                                                    <div class="text-xs text-slate-400">{{ $p->booking->workspace->name ?? 'Workspace' }}</div>
                                                </td>
                                                <td class="py-4 px-3 font-bold text-slate-800">
                                                    {{ number_format($p->final_amount, 0, ',', '.') }}đ
                                                </td>
                                                <td class="py-4 px-3">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg capitalize border bg-slate-50 text-slate-600 border-slate-200">
                                                        @if($p->payment_method === 'momo')
                                                            <span class="w-2 h-2 rounded-full bg-pink-500"></span> MoMo
                                                        @elseif($p->payment_method === 'bank_transfer')
                                                            <span class="w-2 h-2 rounded-full bg-blue-500"></span> VietQR
                                                        @else
                                                            <span class="w-2 h-2 rounded-full bg-slate-400"></span> Tiền mặt
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="py-4 px-3">
                                                    @if($p->payment_status === 'completed')
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full border border-emerald-100">Thành công</span>
                                                    @elseif($p->payment_status === 'pending')
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold text-amber-700 bg-amber-50 rounded-full border border-amber-100">Chờ TT</span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold text-rose-700 bg-rose-50 rounded-full border border-rose-100">Thất bại</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-3 text-right">
                                                    @if($p->payment_status !== 'completed' && $p->booking->status !== 'cancelled')
                                                        <a href="{{ route('profile.payment.pay', $p->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-accent hover:opacity-90 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                                                            <span class="material-symbols-outlined text-sm font-bold">payment</span>
                                                            Thanh toán lại
                                                        </a>
                                                    @else
                                                        <span class="text-xs text-slate-400">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- TAB 3: VOUCHERS / REWARDS --}}
                <div id="tab-vouchers" class="tab-content hidden">
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                        <div class="border-b border-slate-100 pb-5 mb-6">
                            <h3 class="font-headline font-bold text-xl text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">local_activity</span>
                                Ưu đãi / Vouchers giảm giá
                            </h3>
                        </div>

                        @if($vouchers->isEmpty())
                            <div class="text-center py-12">
                                <span class="material-symbols-outlined text-5xl text-slate-300 mb-2">style</span>
                                <p class="text-slate-400 text-sm">Hiện không có mã giảm giá nào dành cho bạn.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($vouchers as $v)
                                    {{-- Voucher Ticket Card --}}
                                    <div class="relative bg-gradient-to-br from-primary-light to-blue-50/50 p-6 border border-primary/20 rounded-2xl flex items-center justify-between shadow-[0_4px_20px_rgba(37,99,235,0.03)] overflow-hidden">
                                        {{-- Ticket punched holes decorations --}}
                                        <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-white border-r border-primary/20"></div>
                                        <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-white border-l border-primary/20"></div>
                                        
                                        <div class="flex-1 pl-4 pr-6">
                                            <span class="inline-block px-2.5 py-0.5 bg-primary text-white text-[10px] font-bold rounded-lg mb-2 tracking-wider">CODE</span>
                                            <h4 class="font-headline font-bold text-lg text-primary tracking-tight">{{ $v->code }}</h4>
                                            <p class="text-xs text-slate-600 mt-1 font-medium">{{ $v->description }}</p>
                                            
                                            <div class="mt-3 flex items-center gap-2 text-[10px] text-slate-400">
                                                <span class="material-symbols-outlined text-xs">schedule</span>
                                                <span>Hạn dùng: {{ $v->valid_until ? \Carbon\Carbon::parse($v->valid_until)->format('d/m/Y') : 'Không giới hạn' }}</span>
                                            </div>
                                        </div>

                                        <div class="text-right flex flex-col justify-between items-end h-full z-10">
                                            <div class="text-xs font-extrabold text-accent">
                                                @if($v->discount_type === 'percentage')
                                                    Giảm {{ number_format($v->discount_value, 0) }}%
                                                @else
                                                    Giảm {{ number_format($v->discount_value, 0, ',', '.') }}đ
                                                @endif
                                            </div>
                                            
                                            <button onclick="copyToClipboard('{{ $v->code }}', this)" class="mt-8 flex items-center gap-1.5 px-3.5 py-2 bg-white border border-primary/30 hover:border-primary text-primary hover:bg-primary-light text-xs font-bold rounded-xl transition-all shadow-sm active:scale-95">
                                                <span class="material-symbols-outlined text-sm font-bold">content_copy</span>
                                                <span>Sao chép</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- TAB 4: ACCOUNT SETTINGS --}}
                <div id="tab-settings" class="tab-content hidden">
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                        <div class="border-b border-slate-100 pb-5 mb-6">
                            <h3 class="font-headline font-bold text-xl text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">manage_accounts</span>
                                Thông tin tài khoản cá nhân
                            </h3>
                        </div>

                        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Họ và tên <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined text-[20px] text-slate-400 absolute left-4 top-1/2 -translate-y-1/2">person</span>
                                        <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3.5 text-sm font-medium focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary shadow-sm bg-slate-50/30">
                                    </div>
                                </div>

                                <div>
                                    <label for="phone" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Số điện thoại</label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined text-[20px] text-slate-400 absolute left-4 top-1/2 -translate-y-1/2">phone</span>
                                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại của bạn" class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3.5 text-sm font-medium focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary shadow-sm bg-slate-50/30">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Địa chỉ Email (Không thay đổi)</label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined text-[20px] text-slate-300 absolute left-4 top-1/2 -translate-y-1/2">mail</span>
                                        <input type="email" disabled value="{{ $user->email }}" class="w-full rounded-2xl border border-slate-200 bg-slate-100 text-slate-400 pl-11 pr-4 py-3.5 text-sm font-medium cursor-not-allowed">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4 border-t border-slate-100">
                                <button type="submit" class="px-6 py-3 bg-primary text-white text-sm font-bold rounded-2xl hover:opacity-90 shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm font-bold">save</span>
                                    Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- TAB 5: PASSWORD CHANGE --}}
                <div id="tab-security" class="tab-content hidden">
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                        <div class="border-b border-slate-100 pb-5 mb-6">
                            <h3 class="font-headline font-bold text-xl text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">shield</span>
                                Thay đổi mật khẩu tài khoản
                            </h3>
                        </div>

                        <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <div class="max-w-md space-y-5">
                                <div>
                                    <label for="current_password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mật khẩu hiện tại <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined text-[20px] text-slate-400 absolute left-4 top-1/2 -translate-y-1/2">lock</span>
                                        <input type="password" name="current_password" id="current_password" required class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3.5 text-sm font-medium focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary shadow-sm bg-slate-50/30">
                                    </div>
                                </div>

                                <div>
                                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mật khẩu mới <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined text-[20px] text-slate-400 absolute left-4 top-1/2 -translate-y-1/2">lock_reset</span>
                                        <input type="password" name="password" id="password" required placeholder="Tối thiểu 4 ký tự" class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3.5 text-sm font-medium focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary shadow-sm bg-slate-50/30">
                                    </div>
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Xác nhận mật khẩu mới <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined text-[20px] text-slate-400 absolute left-4 top-1/2 -translate-y-1/2">check_circle</span>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-3.5 text-sm font-medium focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary shadow-sm bg-slate-50/30">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4 border-t border-slate-100">
                                <button type="submit" class="px-6 py-3 bg-primary text-white text-sm font-bold rounded-2xl hover:opacity-90 shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm font-bold">vpn_key</span>
                                    Cập nhật mật khẩu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- MODAL 1: VIEW QR CODE --}}
<div id="modal-qr" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div onclick="closeModalQR()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white rounded-3xl p-6 max-w-sm w-full mx-4 shadow-2xl border border-slate-100 z-10 transform scale-95 transition-all text-center animate-zoom-in">
        <div class="flex justify-end">
            <button onclick="closeModalQR()" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <h4 class="font-headline font-bold text-lg text-on-surface mb-1">Mã QR Mở cửa & Check-in</h4>
        <p class="text-xs text-slate-400 mb-5">Đưa mã này vào máy quét tại cửa hàng để mở cửa check-in tự động.</p>
        
        {{-- QR Code Image Container --}}
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 inline-block mb-4 shadow-inner">
            <img id="qr-img" src="" alt="Booking QR Code" class="w-48 h-48 mx-auto">
        </div>
        
        <div class="text-sm font-semibold text-slate-700 mb-2">
            Mã đặt chỗ: <span id="qr-code-text" class="text-primary font-bold"></span>
        </div>
        <div class="text-xs text-slate-500" id="qr-workspace-text"></div>
        <div class="text-xs text-slate-500 mt-1" id="qr-time-text"></div>

        <button onclick="closeModalQR()" class="mt-6 w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-2xl transition-all active:scale-95">
            Đóng lại
        </button>
    </div>
</div>

{{-- MODAL 2: WRITE REVIEW --}}
<div id="modal-review" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div onclick="closeModalReview()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white rounded-3xl p-6 max-w-md w-full mx-4 shadow-2xl border border-slate-100 z-10 transform scale-95 transition-all animate-zoom-in">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-5">
            <h4 class="font-headline font-bold text-lg text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">rate_review</span>
                Đánh giá không gian
            </h4>
            <button onclick="closeModalReview()" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <form action="{{ route('profile.booking.review') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="booking_id" id="rev-booking-id">
            <input type="hidden" name="workspace_id" id="rev-workspace-id">

            <div>
                <p class="text-sm font-medium text-slate-700 mb-1" id="rev-workspace-name">Workspace Name</p>
                <p class="text-xs text-slate-400" id="rev-booking-code">Mã đặt chỗ</p>
            </div>

            {{-- Star Rating Input --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Chấm điểm không gian</label>
                <div class="flex gap-2">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setRating({{ $i }})" class="star-btn focus:outline-none transition-transform active:scale-125">
                            <span class="material-symbols-outlined text-3xl text-slate-300 transition-colors" id="star-{{ $i }}">star</span>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rev-rating" value="5" required>
            </div>

            <div>
                <label for="content" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ý kiến của bạn <span class="text-rose-500">*</span></label>
                <textarea name="content" id="content" required rows="4" placeholder="Nhập trải nghiệm thực tế của bạn tại WorkStation... Yên tĩnh, Wi-Fi nhanh, nhân viên nhiệt tình..." class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary shadow-sm bg-slate-50/30"></textarea>
            </div>

            <div class="flex gap-4 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModalReview()" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-2xl transition-all">
                    Hủy bỏ
                </button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white text-sm font-bold rounded-2xl hover:opacity-90 shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-sm font-bold">send</span>
                    Gửi đánh giá
                </button>
            </div>
        </form>
    </div>
</div>
@vite(['resources/css/profile.css', 'resources/js/profile.js'])
@endsection
