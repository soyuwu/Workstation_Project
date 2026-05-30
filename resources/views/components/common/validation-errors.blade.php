@props([
    'timeout' => 5000,
    'title' => null,
])

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'space-y-3']) }}>
        <div
            role="alert"
            data-auto-dismiss="{{ (int) $timeout }}"
            class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-center text-sm text-rose-700 shadow-sm transition-opacity duration-300"
        >
            @if ($title)
                <p class="font-semibold">{{ $title }}</p>
            @endif

            <ul class="{{ $title ? 'mt-2' : '' }} space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

