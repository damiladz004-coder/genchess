<aside class="role-sidebar w-72 min-h-screen border-r border-slate-200/80 bg-white/80 backdrop-blur">
    <div class="p-5 border-b border-slate-200/80">
        <div class="text-xs uppercase tracking-wider text-slate-500">School Admin</div>
        <div class="mt-2">
            <x-brand-logo class="h-10 w-auto" alt="Genchess logo" />
        </div>
    </div>
    <nav class="p-4 space-y-1.5 text-sm">
        <a href="{{ route('school.dashboard') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('school.dashboard') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Dashboard</a>
        <a href="{{ route('school.profile.edit') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('school.profile.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">School Profile</a>
        <a href="{{ route('school.classes.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('school.classes.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Classes</a>
        <a href="{{ route('school.class-teachers.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('school.class-teachers.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Class Teachers</a>
        <a href="{{ route('school.students.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('school.students.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Students</a>
        <a href="{{ route('school.instructors.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('school.instructors.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Chess Instructor</a>
        <a href="{{ route('school.timetables.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('school.timetables.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Timetable</a>
        <a href="{{ route('school.finance.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('school.finance.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Payments</a>
        <a href="{{ route('school.results.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('school.results.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Results</a>
    </nav>
</aside>
