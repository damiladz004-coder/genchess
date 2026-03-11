<nav x-data="{ open: false, mobileProgramsOpen: false, mobilePortalOpen: false }" class="public-nav sticky top-0 z-50 border-b border-purple-700/70 bg-purple-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-3">
        <a href="{{ route('home') }}" class="inline-flex items-center">
            <x-brand-logo class="h-10 sm:h-12 w-auto" alt="Genchess logo" />
        </a>

        <div class="hidden lg:flex items-center gap-6 text-sm font-medium">
            <a href="{{ route('home') }}" class="text-purple-100 transition-all duration-200 hover:text-white">Home</a>
            <a href="{{ route('about') }}" class="text-purple-100 transition-all duration-200 hover:text-white">About</a>

            <div class="relative" x-data="{ openPrograms: false }" @mouseleave="openPrograms = false">
                <button
                    type="button"
                    class="inline-flex items-center gap-1 text-purple-100 transition-all duration-200 hover:text-white"
                    @click="openPrograms = !openPrograms"
                    :aria-expanded="openPrograms ? 'true' : 'false'"
                >
                    Chess Programs
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div
                    x-show="openPrograms"
                    x-cloak
                    @click.outside="openPrograms = false"
                    class="absolute left-0 mt-2 w-64 rounded-lg border border-purple-700 bg-purple-900 py-2 shadow-lg"
                >
                    <a href="{{ route('chess.in.schools') }}" class="block px-4 py-2 text-purple-100 hover:bg-purple-700 hover:text-white">Chess in Schools</a>
                    <a href="{{ route('chess.communities.homes') }}" class="block px-4 py-2 text-purple-100 hover:bg-purple-700 hover:text-white">Chess in Communities & Homes</a>
                </div>
            </div>
            <a href="{{ route('instructor.training') }}" class="text-purple-100 transition-all duration-200 hover:text-white">Instructor Training</a>
            <a href="{{ route('store.index') }}" class="text-purple-100 transition-all duration-200 hover:text-white">Chess Store</a>

            <div class="relative" x-data="{ openPortal: false }" @mouseleave="openPortal = false">
                <button
                    type="button"
                    class="inline-flex items-center gap-1 text-purple-100 transition-all duration-200 hover:text-white"
                    @click="openPortal = !openPortal"
                    :aria-expanded="openPortal ? 'true' : 'false'"
                >
                    Portal
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div
                    x-show="openPortal"
                    x-cloak
                    @click.outside="openPortal = false"
                    class="absolute left-0 mt-2 w-64 rounded-lg border border-purple-700 bg-purple-900 py-2 shadow-lg"
                >
                    <a href="https://admin.genchess.ng" class="block px-4 py-2 text-purple-100 hover:bg-purple-700 hover:text-white">Admin Portal</a>
                    <a href="https://school.genchess.ng" class="block px-4 py-2 text-purple-100 hover:bg-purple-700 hover:text-white">School Portal</a>
                    <a href="https://instructor.genchess.ng" class="block px-4 py-2 text-purple-100 hover:bg-purple-700 hover:text-white">Instructor Dashboard</a>
                    <a href="https://training.genchess.ng" class="block px-4 py-2 text-purple-100 hover:bg-purple-700 hover:text-white">Instructor Training</a>
                    <a href="https://store.genchess.ng" class="block px-4 py-2 text-purple-100 hover:bg-purple-700 hover:text-white">Chess Store</a>
                </div>
            </div>
            <a href="{{ route('contact') }}" class="text-purple-100 transition-all duration-200 hover:text-white">Contact</a>
        </div>

        <div class="flex items-center gap-2 text-sm shrink-0">
            <button type="button" data-theme-toggle class="hidden sm:inline-flex items-center justify-center rounded-lg px-3 py-2 text-xs font-semibold border border-purple-300 text-purple-100 hover:text-white hover:border-white/70 transition-colors duration-200">
                <span data-theme-toggle-label>Dark</span>
            </button>
            @auth
                <a href="/dashboard" class="hidden sm:inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold bg-white text-purple-800">Dashboard</a>
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
            @endauth
            <a href="{{ route('home') }}" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Home</a>
            <a href="{{ route('about') }}" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">About</a>

            <div class="rounded-md border border-purple-700/70">
                <button
                    type="button"
                    class="flex w-full items-center justify-between px-2 py-2 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white"
                    @click="mobileProgramsOpen = !mobileProgramsOpen"
                    :aria-expanded="mobileProgramsOpen ? 'true' : 'false'"
                >
                    <span>Chess Programs</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="mobileProgramsOpen" x-cloak class="pb-2">
                    <a href="{{ route('chess.in.schools') }}" class="block rounded-md px-4 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Chess in Schools</a>
                    <a href="{{ route('chess.communities.homes') }}" class="block rounded-md px-4 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Chess in Communities & Homes</a>
                </div>
            </div>

            <a href="{{ route('instructor.training') }}" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Instructor Training</a>
            <a href="{{ route('store.index') }}" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Chess Store</a>

            <div class="rounded-md border border-purple-700/70">
                <button
                    type="button"
                    class="flex w-full items-center justify-between px-2 py-2 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white"
                    @click="mobilePortalOpen = !mobilePortalOpen"
                    :aria-expanded="mobilePortalOpen ? 'true' : 'false'"
                >
                    <span>Portal</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="mobilePortalOpen" x-cloak class="pb-2">
                    <a href="https://admin.genchess.ng" class="block rounded-md px-4 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Admin Portal</a>
                    <a href="https://school.genchess.ng" class="block rounded-md px-4 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">School Portal</a>
                    <a href="https://instructor.genchess.ng" class="block rounded-md px-4 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Instructor Dashboard</a>
                    <a href="https://training.genchess.ng" class="block rounded-md px-4 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Instructor Training</a>
                    <a href="https://store.genchess.ng" class="block rounded-md px-4 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Chess Store</a>
                </div>
            </div>

            <a href="{{ route('contact') }}" class="rounded-md px-2 py-1 text-purple-100 transition-colors duration-200 hover:bg-purple-700 hover:text-white">Contact</a>
            <button type="button" data-theme-toggle class="rounded-md px-2 py-1 text-purple-100 text-left transition-colors duration-200 hover:bg-purple-700 hover:text-white">
                Theme: <span data-theme-toggle-label>Dark</span>
            </button>
        </div>
    </div>
</nav>
