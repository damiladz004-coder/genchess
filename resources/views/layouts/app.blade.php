<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

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
    <body class="font-sans antialiased text-slate-900 transition-colors duration-300">
        <div class="min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-transparent">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="pb-10">
                @php
                    $sidebarView = match(auth()->user()?->role) {
                        'super_admin' => 'layouts.admin-sidebar',
                        'school_admin' => 'layouts.school-sidebar',
                        'instructor' => 'layouts.instructor-sidebar',
                        'class_teacher' => 'layouts.class-teacher-sidebar',
                        default => null,
                    };
                @endphp

                @if($sidebarView)
                    <div class="block lg:flex max-w-[1400px] mx-auto">
                        <div class="hidden lg:block">
                            @include($sidebarView)
                        </div>
                        <div class="flex-1 p-4 md:p-6 lg:p-7">
                            @if (isset($slot) && trim((string) $slot) !== '')
                                {{ $slot }}
                            @else
                                @yield('content')
                            @endif
                        </div>
                    </div>
                @else
                    @if (isset($slot) && trim((string) $slot) !== '')
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endif
                @endif
            </main>
        </div>
    </body>
</html>
