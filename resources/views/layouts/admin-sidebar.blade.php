<aside class="w-72 min-h-screen border-r border-slate-200/80 bg-white/80 backdrop-blur">
    <div class="p-5 border-b border-slate-200/80">
        <div class="text-xs uppercase tracking-wider text-slate-500">Super Admin</div>
        <div class="font-display text-xl text-brand-800">Genchess HQ</div>
    </div>
    <nav class="p-4 space-y-1.5 text-sm">
        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Dashboard</a>
        <a href="{{ route('admin.schools.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.schools.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Schools</a>
        <a href="{{ route('admin.classes.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.classes.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Classes</a>
        <a href="{{ route('admin.students.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.students.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Students</a>
        <a href="{{ route('admin.instructors.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.instructors.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Chess Instructors</a>
        <a href="{{ route('admin.instructor-assignments.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.instructor-assignments.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Instructor Assignments</a>
        <a href="{{ route('admin.timetables.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.timetables.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Timetables</a>
        <a href="{{ route('admin.attendance.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.attendance.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Attendance</a>
        <a href="{{ route('admin.reports.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Reports</a>
        <a href="{{ route('admin.finance.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.finance.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Payments</a>
        <a href="{{ route('admin.settings.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Settings</a>
        <a href="{{ route('admin.class-teacher-feedback.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.class-teacher-feedback.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Feedback</a>
        <a href="{{ route('admin.exams.templates.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.exams.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Exams</a>
        <a href="{{ route('admin.scheme.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.scheme.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Scheme of Work</a>
        <a href="{{ route('admin.training.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.training.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Training</a>
        <a href="{{ route('admin.careers.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.careers.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Careers</a>
        <a href="{{ route('admin.instructor-screenings.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.instructor-screenings.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Instructor Screening</a>
        <a href="{{ route('admin.enrollments.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.enrollments.*') ? 'bg-brand-700 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Enrollments</a>
    </nav>
</aside>
