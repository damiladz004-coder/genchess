<nav x-data="{ open: false, mobileServicesOpen: false }" class="public-nav sticky top-0 z-50 border-b border-purple-700/70 bg-purple-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-3">
        <a href="{{ route('home') }}" class="inline-flex items-center">
            <x-brand-logo class="h-10 sm:h-12 w-auto" alt="Genchess logo" />
        </a>

        <div class="hidden lg:flex items-center gap-6 text-sm font-medium">
            <a href="{{ route('home') }}" class="text-purple-100 transition-all duration-200 hover:text-white">Home</a>
            <a href="{{ route('about') }}" class="text-purple-100 transition-all duration-200 hover:text-white">About</a>

            <div class="relative" x-data="{ servicesOpen: false }" @mouseleave="servicesOpen = false">
                <button
                    type="button"
                    @mouseenter="servicesOpen = true"
                    @click="servicesOpen = !servicesOpen"
                    class="inline-flex items-center gap-1 text-purple-100 transition-all duration-200 hover:text-white focus:outline-none"
                >
                    <span>Services</span>
                    <svg class="h-4 w-4 transition-transform duration-200" :class="servicesOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div
                    x-show="servicesOpen"
                    x-transition.opacity.duration.150ms
                    @mouseenter="servicesOpen = true"
                    @click.outside="servicesOpen = false"
                    x-cloak
                    class="absolute left-0 top-full z-50 mt-1 min-w-64 rounded-lg border border-purple-200 bg-white text-slate-800 shadow-lg"
                >
                    <a href="{{ route('chess.in.schools') }}" class="block px-4 py-3 transition-colors duration-200 hover:bg-purple-50 hover:text-purple-800">Chess in Schools</a>
                    <a href="{{ route('chess.communities.homes') }}" class="block px-4 py-3 transition-colors duration-200 hover:bg-purple-50 hover:text-purple-800">Chess in Communities &amp; Homes</a>
                </div>
            </div>

            <a href="{{ route('store.index') }}" class="text-purple-100 transition-all duration-200 hover:text-white">Store</a>
            <a href="{{ route('contact') }}" class="text-purple-100 transition-all duration-200 hover:text-white">Contact</a>
        </div>

        <div class="flex items-center gap-2 text-sm shrink-0">
            <button type="button" data-theme-toggle class="hidden sm:inline-flex items-center justify-center rounded-lg px-3 py-2 text-xs font-semibold border border-purple-300 text-purple-100 hover:text-white hover:border-white/70 transition-colors duration-200">
                <span data-theme-toggle-label>Dark</span>
            </button>
            @auth
                <a href="/dashboard" class="hidden sm:inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold bg-white text-purple-800">Dashboard</a>
            @else
                <a href="/login" class="hidden sm:inline-block text-purple-100 font-medium transition-colors duration-200 hover:text-white">Login</a>
            @endauth

            <button @click="open = !open" class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-purple-300 text-white" aria-label="Toggle menu" :aria-expanded="open ? 'true' : 'false'">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak class="lg:hidden border-t border-purple-700/70 px-4 sm:px-6 py-3">
        <div class="flex flex-col gap-2 text-sm font-medium">
            @auth
                <a href="/dashboard" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Dashboard</a>
            @else
                <a href="/login" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Login</a>
            @endauth
            <a href="{{ route('home') }}" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Home</a>
            <a href="{{ route('about') }}" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">About</a>
            <button type="button" data-theme-toggle class="rounded-md px-2 py-1 text-purple-100 text-left transition-colors duration-200 hover:bg-purple-700 hover:text-white">
                Theme: <span data-theme-toggle-label>Dark</span>
            </button>

            <button @click="mobileServicesOpen = !mobileServicesOpen" type="button" class="inline-flex items-center justify-between rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">
                <span>Services</span>
                <svg class="h-4 w-4 transition-transform duration-200" :class="mobileServicesOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="mobileServicesOpen" x-cloak class="ml-3 flex flex-col gap-1 border-l border-purple-600 pl-3">
                <a href="{{ route('chess.in.schools') }}" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Chess in Schools</a>
                <a href="{{ route('chess.communities.homes') }}" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Chess in Communities &amp; Homes</a>
            </div>

            <a href="{{ route('store.index') }}" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Store</a>
            <a href="{{ route('contact') }}" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Contact</a>
        </div>
    </div>
</nav>
