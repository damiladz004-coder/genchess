<section class="py-10 border-t border-b border-slate-200/80 bg-gradient-to-r from-brand-800 to-brand-700 text-white">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-display">Start With Genchess Academy</h3>
            <p class="text-sm text-brand-100">Bring structured chess education to your school or join as an instructor.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('register.school') }}" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold bg-accent-400 text-slate-900">Register Your School</a>
            <a href="{{ route('instructor.screening.create') }}" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold bg-white/10 border border-white/40">Become an Instructor</a>
        </div>
    </div>
</section>
