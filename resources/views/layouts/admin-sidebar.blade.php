<aside class="w-72 min-h-screen border-r border-slate-200/80 bg-white/80 backdrop-blur">
    <div class="p-5 border-b border-slate-200/80">
        <div class="text-xs uppercase tracking-wider text-slate-500">Super Admin</div>
        <div class="mt-2">
            <img
                src="{{ asset('images/logo/genchess-logo-brick.png') }}"
                alt="Genchess logo"
                class="h-10 w-auto"
            >
        </div>
    </div>
    <nav class="p-4 space-y-1.5 text-sm">
        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-link>
        <x-nav-link :href="route('admin.schools.index')" :active="request()->routeIs('admin.schools.*')">Schools</x-nav-link>
        <x-nav-link :href="route('admin.classes.index')" :active="request()->routeIs('admin.classes.*')">Classes</x-nav-link>
        <x-nav-link :href="route('admin.students.index')" :active="request()->routeIs('admin.students.*')">Students</x-nav-link>
        <x-nav-link :href="route('admin.instructors.index')" :active="request()->routeIs('admin.instructors.*')">Chess Instructors</x-nav-link>
        <x-nav-link :href="route('admin.instructor-assignments.index')" :active="request()->routeIs('admin.instructor-assignments.*')">Instructor Assignments</x-nav-link>
        <x-nav-link :href="route('admin.timetables.index')" :active="request()->routeIs('admin.timetables.*')">Timetables</x-nav-link>
        <x-nav-link :href="route('admin.lesson-plans.index')" :active="request()->routeIs('admin.lesson-plans.*')">Lesson Plans Review</x-nav-link>
        <x-nav-link :href="route('admin.attendance.index')" :active="request()->routeIs('admin.attendance.*')">Attendance</x-nav-link>
        <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">Reports</x-nav-link>
        <x-nav-link :href="route('admin.finance.index')" :active="request()->routeIs('admin.finance.*')">Payments</x-nav-link>
        <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">Settings</x-nav-link>
        <x-nav-link :href="route('admin.class-teacher-feedback.index')" :active="request()->routeIs('admin.class-teacher-feedback.*')">Feedback</x-nav-link>
        <x-nav-link :href="route('admin.exams.templates.index')" :active="request()->routeIs('admin.exams.*')">Exams</x-nav-link>
        <x-nav-link :href="route('admin.scheme.index')" :active="request()->routeIs('admin.scheme.*')">Scheme of Work</x-nav-link>
        <x-nav-link :href="route('admin.training.index')" :active="request()->routeIs('admin.training.*')">Training</x-nav-link>
        <x-nav-link :href="route('admin.store.products.index')" :active="request()->routeIs('admin.store.products.*')">Store Products</x-nav-link>
        <x-nav-link :href="route('admin.store.categories.index')" :active="request()->routeIs('admin.store.categories.*')">Store Categories</x-nav-link>
        <x-nav-link :href="route('admin.store.orders.index')" :active="request()->routeIs('admin.store.orders.*')">Store Orders</x-nav-link>
        <x-nav-link :href="route('admin.store.inventory.index')" :active="request()->routeIs('admin.store.inventory.*')">Store Inventory</x-nav-link>
        <x-nav-link :href="route('admin.store.bulk-orders.index')" :active="request()->routeIs('admin.store.bulk-orders.*')">Store Bulk Orders</x-nav-link>
        <x-nav-link :href="route('admin.careers.index')" :active="request()->routeIs('admin.careers.*')">Careers</x-nav-link>
        <x-nav-link :href="route('admin.instructor-screenings.index')" :active="request()->routeIs('admin.instructor-screenings.*')">Instructor Screening</x-nav-link>
        <x-nav-link :href="route('admin.enrollments.index')" :active="request()->routeIs('admin.enrollments.*')">Enrollments</x-nav-link>
    </nav>
</aside>
