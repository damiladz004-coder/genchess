<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Genchess Academy') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="public-site font-sans antialiased bg-slate-50 text-slate-900">
        <div class="min-h-screen flex flex-col">
            @include('layouts.public-nav')

            <main class="public-content flex-1">
                @yield('content')
            </main>

            @include('layouts.public-cta')

            @include('layouts.public-footer')
        </div>
    </body>
</html>
