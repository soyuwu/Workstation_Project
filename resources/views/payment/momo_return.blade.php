@extends('layouts.app')

@section('title', 'Kết quả thanh toán')
@section('nav-mode', 'solid')

@section('content')
<div class="bg-slate-50 min-h-screen py-20 flex items-center justify-center">
    <div class="max-w-md w-full mx-auto p-8 bg-white rounded-3xl shadow-sm text-center border border-slate-100">
        @if($status === 'success')
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 text-green-600">
                <span class="material-symbols-outlined text-4xl">check_circle</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Thanh toán thành công!</h2>
            <p class="text-slate-600 mb-8">{{ $msg }}</p>
        @else
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600">
                <span class="material-symbols-outlined text-4xl">cancel</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Thanh toán thất bại!</h2>
            <p class="text-slate-600 mb-8">{{ $msg }}</p>
        @endif

        <a href="{{ route('booking.index') }}" class="inline-block w-full py-3.5 bg-primary text-white rounded-xl font-semibold hover:bg-primary-dark transition-colors">
            Quay về trang chủ
        </a>
    </div>
</div>
@endsection
