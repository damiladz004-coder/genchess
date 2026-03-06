<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/85 backdrop-blur">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="/dashboard" class="inline-flex items-center">
                        <x-brand-logo class="h-10 w-auto" alt="Genchess logo" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 mt-5 mb-5">
                            {{ str_replace('_', ' ', Auth::user()->role) }}
                        </span>
                    @endauth
                    <a href="/dashboard" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-brand-700">Dashboard</a>
                </div>
            </div>

            @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <button type="button" data-theme-toggle class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-xs font-semibold border border-slate-300 text-slate-600 bg-white hover:bg-slate-50 me-3">
                    <span data-theme-toggle-label>Dark</span>
                </button>
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button class="relative inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-slate-600 hover:text-slate-900 me-3">
                            <span class="text-sm">Notifications</span>
                            @if(auth()->user()->unreadNotifications()->count() > 0)
                                <span class="ml-2 inline-flex items-center justify-center min-w-5 h-5 px-1 rounded-full bg-rose-600 text-white text-[10px]">
                                    {{ auth()->user()->unreadNotifications()->count() }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-3 py-2 border-b text-xs font-semibold text-slate-600">Recent Notifications</div>
                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                            @php $data = $notification->data ?? []; @endphp
                            <div class="px-3 py-2 text-xs border-b {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                                <div class="font-semibold">{{ $data['title'] ?? 'Notification' }}</div>
                                <div class="text-slate-600">{{ $data['message'] ?? '' }}</div>
                                <div class="mt-1 text-[10px] text-slate-400">{{ $notification->created_at?->diffForHumans() }}</div>
                                @if(!$notification->read_at)
                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="mt-1">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-[10px] text-brand-700 underline" type="submit">Mark read</button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="px-3 py-2 text-xs text-slate-500">No notifications yet.</div>
                        @endforelse
                        <div class="px-3 py-2 flex justify-between gap-2">
                            <form method="POST" action="{{ route('notifications.read-all') }}">
                                @csrf
                                @method('PATCH')
                                <button class="text-xs text-brand-700 underline" type="submit">Mark all read</button>
                            </form>
                            <a href="{{ route('notifications.index') }}" class="text-xs text-brand-700 underline">View all</a>
                        </div>
                    </x-slot>
                </x-dropdown>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border text-sm leading-4 font-medium rounded-lg text-slate-600 bg-white hover:text-slate-900 focus:outline-none transition ease-in-out duration-150 border-slate-200">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth

            @guest
                <a href="/login" class="text-sm font-medium text-slate-700 hover:text-brand-700">Login</a>
            @endguest

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @auth
        <div class="lg:hidden border-t border-slate-200 bg-white px-3 py-2">
            <div class="flex gap-2 overflow-x-auto whitespace-nowrap text-xs">
                <button type="button" data-theme-toggle class="gc-btn-secondary px-3 py-1.5">
                    Theme: <span data-theme-toggle-label>Dark</span>
                </button>
                @if(auth()->user()->role === 'super_admin')
                    <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary px-3 py-1.5">Dashboard</a>
                    <a href="{{ route('admin.schools.index') }}" class="gc-btn-secondary px-3 py-1.5">Schools</a>
                    <a href="{{ route('admin.classes.index') }}" class="gc-btn-secondary px-3 py-1.5">Classes</a>
                    <a href="{{ route('admin.students.index') }}" class="gc-btn-secondary px-3 py-1.5">Students</a>
                    <a href="{{ route('admin.lesson-plans.index') }}" class="gc-btn-secondary px-3 py-1.5">Lesson Plans</a>
                    <a href="{{ route('admin.finance.index') }}" class="gc-btn-secondary px-3 py-1.5">Payments</a>
                @elseif(auth()->user()->role === 'school_admin')
                    <a href="{{ route('school.dashboard') }}" class="gc-btn-secondary px-3 py-1.5">Dashboard</a>
                    <a href="{{ route('school.profile.edit') }}" class="gc-btn-secondary px-3 py-1.5">Profile</a>
                    <a href="{{ route('school.classes.index') }}" class="gc-btn-secondary px-3 py-1.5">Classes</a>
                    <a href="{{ route('school.students.index') }}" class="gc-btn-secondary px-3 py-1.5">Students</a>
                    <a href="{{ route('school.timetables.index') }}" class="gc-btn-secondary px-3 py-1.5">Timetable</a>
                @elseif(auth()->user()->role === 'instructor')
                    <a href="{{ route('instructor.dashboard') }}" class="gc-btn-secondary px-3 py-1.5">Dashboard</a>
                    <a href="{{ route('instructor.classes.index') }}" class="gc-btn-secondary px-3 py-1.5">Classes</a>
                    <a href="{{ route('instructor.attendance.select') }}" class="gc-btn-secondary px-3 py-1.5">Attendance</a>
                    <a href="{{ route('instructor.lesson-plans.index') }}" class="gc-btn-secondary px-3 py-1.5">Lesson Plans</a>
                    <a href="{{ route('instructor.timetable.index') }}" class="gc-btn-secondary px-3 py-1.5">Timetable</a>
                @elseif(auth()->user()->role === 'class_teacher')
                    <a href="{{ route('class-teacher.dashboard') }}" class="gc-btn-secondary px-3 py-1.5">Dashboard</a>
                    <a href="{{ route('class-teacher.timetable.index') }}" class="gc-btn-secondary px-3 py-1.5">Timetable</a>
                    <a href="{{ route('class-teacher.feedback.create') }}" class="gc-btn-secondary px-3 py-1.5">Feedback</a>
                @endif
            </div>
        </div>
    @endauth

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="/dashboard">Dashboard</a>
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div>
                        {{ Auth::user()->name }}
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth

        @guest
            <div>
                <a href="/login">Login</a>
            </div>
        @endguest
    </div>
</nav>
