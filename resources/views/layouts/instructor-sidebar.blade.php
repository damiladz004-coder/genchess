<aside class="w-72 min-h-screen border-r border-slate-200/80 bg-white/80 backdrop-blur">
    <div class="p-5 border-b border-slate-200/80">
        <div class="text-xs uppercase tracking-wider text-slate-500">Instructor</div>
        <div class="font-display text-xl text-brand-800">Genchess</div>
    </div>
    <nav class="p-4 space-y-1.5 text-sm">
        <a href="{{ route('instructor.dashboard') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('instructor.dashboard') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Dashboard</a>
        <a href="{{ route('instructor.classes.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('instructor.classes.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">My Schools & Classes</a>
        <a href="{{ route('instructor.attendance.select') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('instructor.attendance.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Attendance</a>
        <a href="{{ route('instructor.lesson-plans.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('instructor.lesson-plans.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Lesson Plans</a>
        <a href="{{ route('instructor.timetable.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('instructor.timetable.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Timetable</a>
    </nav>
</aside>
