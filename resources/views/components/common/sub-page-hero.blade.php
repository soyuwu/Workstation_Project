@props([
    'icon' => 'workspace_premium',
    'subtitle' => '',
    'title' => '',
    'description' => '',
])

<section class="bg-gradient-to-b from-primary/5 to-white pb-16 pt-28">
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
        <div class="mx-auto max-w-3xl text-center">
            <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-primary-light px-4 py-2 text-sm font-headline font-semibold text-primary">
                <span class="material-symbols-outlined text-lg">{{ $icon }}</span>
                {{ $subtitle }}
            </div>
            <h1 class="mb-6 font-headline text-4xl font-bold leading-tight text-on-surface lg:text-5xl">
                {!! $title !!}
            </h1>
            <p class="text-lg leading-relaxed text-slate-500">
                {{ $description }}
            </p>
        </div>
    </div>
</section>
