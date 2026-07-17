@props([
    'title' => '',
    'description' => null,
    'icon' => null,
    'class' => '',
])

<div class="rounded-2xl p-5 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 shadow-sm {{ $class }}">
    <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
            @if($icon)
                {!! $icon !!}
            @else
                <svg class="w-8 h-8 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12l2-2 2 2V5h2v10H5V5h2v7z"/></svg>
            @endif
        </div>
        <div>
            <h4 class="text-lg font-semibold text-black">{{ $title }}</h4>
            @if($description)
                <p class="mt-1 text-sm text-black">{{ $description }}</p>
            @endif
        </div>
    </div>
</div>
