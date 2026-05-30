@props([
    'timeout' => 5000,
])

@php
    $flashMessages = [
        'success' => session('success'),
        'warning' => session('warning'),
        'error' => session('error'),
        'info' => session('info'),
    ];

    $flashMessages = array_filter($flashMessages, fn ($message) => filled($message));

    $styles = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-700',
        'error' => 'border-rose-200 bg-rose-50 text-rose-700',
        'info' => 'border-sky-200 bg-sky-50 text-sky-700',
    ];
@endphp

@if (!empty($flashMessages))
    <div {{ $attributes->merge(['class' => 'space-y-3']) }}>
        @foreach ($flashMessages as $type => $message)
            <div
                role="alert"
                data-auto-dismiss="{{ (int) $timeout }}"
                class="rounded-2xl border px-4 py-3 text-center text-sm font-medium shadow-sm transition-opacity duration-300 {{ $styles[$type] ?? $styles['info'] }}"
            >
                {{ $message }}
            </div>
        @endforeach
    </div>
@endif

