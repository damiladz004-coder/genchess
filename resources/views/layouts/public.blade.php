<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $defaultTitle = 'Genchess.ng - Chess Education in Nigeria';
            $defaultDescription = 'Genchess Educational Services Ltd provides chess training in schools, homes, and communities across Nigeria.';
            $defaultKeywords = 'chess Nigeria, chess training Lagos, Genchess, chess schools Nigeria';
            $defaultImage = asset('images/hero/genchess-hero.jpg');

            $pageTitle = trim($__env->yieldContent('title')) ?: ($seo['title'] ?? $defaultTitle);
            $pageDescription = trim($__env->yieldContent('description')) ?: ($seo['description'] ?? $defaultDescription);
            $pageKeywords = trim($__env->yieldContent('keywords')) ?: ($seo['keywords'] ?? $defaultKeywords);
            $pageCanonical = trim($__env->yieldContent('canonical')) ?: ($seo['canonical'] ?? url()->current());
            $pageRobots = trim($__env->yieldContent('robots')) ?: ($seo['robots'] ?? 'index, follow');
            $pageImage = trim($__env->yieldContent('image')) ?: ($seo['image'] ?? $defaultImage);
            $pageType = trim($__env->yieldContent('og_type')) ?: ($seo['og_type'] ?? 'website');
            $twitterCard = trim($__env->yieldContent('twitter_card')) ?: ($seo['twitter_card'] ?? 'summary_large_image');

            $organizationSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => 'Genchess Educational Services Ltd',
                'url' => config('app.url', 'https://genchess.ng'),
                'logo' => asset('images/logo/genchess-logo-brick.png'),
                'description' => $defaultDescription,
                'sameAs' => [
                    'https://web.facebook.com/genchesssacademy',
                    'https://www.instagram.com/genchesseducation/',
                    'https://x.com/genchess1234',
                    'https://www.youtube.com/@GenchessAcademy-c2z',
                ],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => '+2348078462223',
                    'contactType' => 'customer support',
                    'areaServed' => 'NG',
                    'availableLanguage' => ['en'],
                ],
            ];
        @endphp
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
        <meta name="description" content="{{ $pageDescription }}">
        <meta name="keywords" content="{{ $pageKeywords }}">
        <meta name="robots" content="{{ $pageRobots }}">
        <link rel="canonical" href="{{ $pageCanonical }}">

        <meta property="og:type" content="{{ $pageType }}">
        <meta property="og:site_name" content="Genchess Educational Services">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $pageDescription }}">
        <meta property="og:image" content="{{ $pageImage }}">
        <meta property="og:url" content="{{ $pageCanonical }}">

        <meta name="twitter:card" content="{{ $twitterCard }}">
        <meta name="twitter:title" content="{{ $pageTitle }}">
        <meta name="twitter:description" content="{{ $pageDescription }}">
        <meta name="twitter:image" content="{{ $pageImage }}">

        <script type="application/ld+json">
            {!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
        @yield('structured_data')

        <script>
            (function () {
                try {
                    var key = 'gc_theme';
                    var saved = localStorage.getItem(key);
                    var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    var useDark = saved ? (saved === 'dark') : prefersDark;
                    document.documentElement.classList.toggle('dark', useDark);
                    document.documentElement.setAttribute('data-theme', useDark ? 'dark' : 'light');
                } catch (e) {}
            })();
        </script>

        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="public-site overflow-x-hidden font-sans antialiased bg-slate-50 text-slate-900 transition-colors duration-300">
        <div class="min-h-screen flex flex-col">
            @include('layouts.public-nav')

            <main class="public-content flex-1 w-full">
                @if (isset($slot) && trim((string) $slot) !== '')
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>

            @include('layouts.public-cta')

            @include('layouts.public-footer')
        </div>
    </body>
</html>

