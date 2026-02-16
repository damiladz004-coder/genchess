<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900">
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
                @if(auth()->check() && auth()->user()->role === 'super_admin')
                    <div class="block lg:flex max-w-[1400px] mx-auto">
                        <div class="hidden lg:block">
                            @include('layouts.admin-sidebar')
                        </div>
                        <div class="flex-1 p-4 md:p-6 lg:p-7">
                            @if (isset($slot) && trim($slot) !== '')
                                {{ $slot }}
                            @else
                                @yield('content')
                            @endif
                        </div>
                    </div>
                @elseif(auth()->check() && auth()->user()->role === 'school_admin')
                    <div class="block lg:flex max-w-[1400px] mx-auto">
                        <div class="hidden lg:block">
                            @include('layouts.school-sidebar')
                        </div>
                        <div class="flex-1 p-4 md:p-6 lg:p-7">
                            @if (isset($slot) && trim($slot) !== '')
                                {{ $slot }}
                            @else
                                @yield('content')
                            @endif
                        </div>
                    </div>
                @elseif(auth()->check() && auth()->user()->role === 'instructor')
                    <div class="block lg:flex max-w-[1400px] mx-auto">
                        <div class="hidden lg:block">
                            @include('layouts.instructor-sidebar')
                        </div>
                        <div class="flex-1 p-4 md:p-6 lg:p-7">
                            @if (isset($slot) && trim($slot) !== '')
                                {{ $slot }}
                            @else
                                @yield('content')
                            @endif
                        </div>
                    </div>
                @elseif(auth()->check() && auth()->user()->role === 'class_teacher')
                    <div class="block lg:flex max-w-[1400px] mx-auto">
                        <div class="hidden lg:block">
                            @include('layouts.class-teacher-sidebar')
                        </div>
                        <div class="flex-1 p-4 md:p-6 lg:p-7">
                            @if (isset($slot) && trim($slot) !== '')
                                {{ $slot }}
                            @else
                                @yield('content')
                            @endif
                        </div>
                    </div>
                @else
                    @if (isset($slot) && trim($slot) !== '')
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endif
                @endif
            </main>
        </div>
    </body>
</html>
