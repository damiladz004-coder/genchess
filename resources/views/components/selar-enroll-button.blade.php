@props([
    'label' => 'Enroll Now',
    'productUrl' => null,
    'checkoutUrl' => null,
    'mode' => 'redirect',
    'campaign' => null,
    'content' => null,
    'fallbackLabel' => 'Continue to Selar',
    'buttonClass' => 'gc-btn-primary',
])

@php
    $targetUrl = $checkoutUrl ?: $productUrl;
@endphp

@if($targetUrl)
    <div class="space-y-2">
        <button
            type="button"
            {{ $attributes->merge(['class' => $buttonClass]) }}
            data-selar-enroll
            data-selar-url="{{ $targetUrl }}"
            data-selar-product-url="{{ $productUrl ?: $targetUrl }}"
            data-selar-mode="{{ $mode }}"
            @if($campaign) data-selar-campaign="{{ $campaign }}" @endif
            @if($content) data-selar-content="{{ $content }}" @endif
            @if(config('services.selar.training_success_url')) data-selar-success-url="{{ config('services.selar.training_success_url') }}" @endif
            aria-live="polite"
        >
            <span data-selar-label>{{ $label }}</span>
        </button>
        <p class="text-xs text-slate-500">Secure checkout powered by Selar.</p>
        <noscript>
            <a href="{{ $targetUrl }}" class="inline-flex items-center text-sm font-medium text-brand-700 underline">
                {{ $fallbackLabel }}
            </a>
        </noscript>
    </div>
@endif
