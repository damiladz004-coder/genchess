<aside class="role-sidebar w-72 min-h-screen border-r border-slate-200/80 bg-white/80 backdrop-blur">
    <div class="p-5 border-b border-slate-200/80">
        <div class="text-xs uppercase tracking-wider text-slate-500">Class Teacher</div>
        <div class="mt-2">
            <x-brand-logo class="h-10 w-auto" alt="Genchess logo" />
        </div>
    </div>
    <nav class="p-4 space-y-1.5 text-sm">
        <a href="{{ route('class-teacher.dashboard') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('class-teacher.dashboard') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Dashboard</a>
        <a href="{{ route('class-teacher.timetable.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('class-teacher.timetable.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Timetable</a>
        <a href="{{ route('class-teacher.feedback.create') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('class-teacher.feedback.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Feedback</a>
    </nav>
</aside>
