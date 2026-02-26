<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Genchess Educational Services</title>
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

        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="public-site overflow-x-hidden font-sans antialiased bg-slate-50 text-slate-900">
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
