<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Genchess Educational Services</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
        <meta name="description" content="Genchess Educational Services promotes structured chess education for schools, homes, and communities.">
        <meta name="keywords" content="chess, chess educational services, chess in homes, chess in communities, chess in schools, certified chess instructor program">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="https://genchess.ng">

        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Genchess Educational Services">
        <meta property="og:title" content="Genchess Educational Services">
        <meta property="og:description" content="Genchess Educational Services promotes structured chess education for schools, homes, and communities.">
        <meta property="og:url" content="https://genchess.ng">

        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="Genchess Educational Services">
        <meta name="twitter:description" content="Genchess Educational Services promotes structured chess education for schools, homes, and communities.">

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


