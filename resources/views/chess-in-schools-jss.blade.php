@extends('layouts.public')

@section('content')
<section class="bg-white py-16">
    <div class="max-w-6xl mx-auto px-6 space-y-6">
        <nav aria-label="Breadcrumb" class="text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-slate-700">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('chess.in.schools') }}" class="hover:text-slate-700">Services</a>
            <span class="mx-2">/</span>
            <span class="text-slate-700">JSS 1-3</span>
        </nav>
        <h1 class="text-4xl gc-heading">Chess in Schools - JSS 1-3</h1>
        <p class="text-lg text-slate-700">
            At Junior Secondary level, Genchess transitions students from core chess fundamentals to structured
            strategy, analysis, and competitive preparation.
        </p>
        <p class="text-slate-700">
            Chess remains a formal school subject within the Basic Education phase and is delivered with clear
            learning objectives per term.
        </p>
    </div>
</section>

<section class="py-10 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid gap-6 md:grid-cols-2">
            <figure class="gc-panel p-3">
                <img src="/images/placeholders/chess-in-school-placeholder.svg" alt="JSS classroom placeholder image" class="w-full h-56 object-cover rounded-lg">
                <figcaption class="mt-2 text-sm text-slate-600">JSS classroom strategy lesson</figcaption>
            </figure>
            <figure class="gc-panel p-3">
                <img src="/images/placeholders/chess-in-school-placeholder.svg" alt="JSS tournament prep placeholder image" class="w-full h-56 object-cover rounded-lg">
                <figcaption class="mt-2 text-sm text-slate-600">JSS competitive preparation session</figcaption>
            </figure>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-6xl mx-auto px-6 space-y-6">
        <h2 class="text-3xl gc-heading">JSS Curriculum Focus</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <article class="gc-panel p-6 space-y-3">
                <h3 class="text-xl font-semibold">JSS 1</h3>
                <ul class="space-y-2 text-slate-700">
                    <li>Opening principles and piece activity</li>
                    <li>Tactical combinations and pattern drills</li>
                    <li>Notation and recording games correctly</li>
                    <li>Guided analysis of student games</li>
                </ul>
            </article>
            <article class="gc-panel p-6 space-y-3">
                <h3 class="text-xl font-semibold">JSS 2</h3>
                <ul class="space-y-2 text-slate-700">
                    <li>Strategic planning and pawn structures</li>
                    <li>Piece coordination and positional play</li>
                    <li>Endgame essentials and technique</li>
                    <li>Decision-making under practical pressure</li>
                </ul>
            </article>
            <article class="gc-panel p-6 space-y-3">
                <h3 class="text-xl font-semibold">JSS 3</h3>
                <ul class="space-y-2 text-slate-700">
                    <li>Advanced tactical awareness</li>
                    <li>Deep game analysis and self-evaluation</li>
                    <li>Competitive match preparation</li>
                    <li>Leadership and peer mentoring in chess</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6 space-y-6">
        <h2 class="text-3xl gc-heading">Skills Developed at JSS Level</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="gc-panel p-6">
                <h3 class="text-xl font-semibold mb-2">Cognitive Skills</h3>
                <ul class="space-y-2 text-slate-700">
                    <li>Analytical and critical thinking</li>
                    <li>Long-term planning and foresight</li>
                    <li>Accurate evaluation of positions</li>
                    <li>Logical reasoning and pattern recognition</li>
                </ul>
            </div>
            <div class="gc-panel p-6">
                <h3 class="text-xl font-semibold mb-2">Life and Leadership Skills</h3>
                <ul class="space-y-2 text-slate-700">
                    <li>Emotional regulation under pressure</li>
                    <li>Discipline and personal responsibility</li>
                    <li>Respectful competition and teamwork</li>
                    <li>Confidence in decision-making</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-900 text-white">
    <div class="max-w-6xl mx-auto px-6 space-y-5">
        <h2 class="text-3xl md:text-4xl font-display">Deploy JSS Chess Program in Your School</h2>
        <p class="text-slate-200">
            Genchess provides certified instructors, a structured JSS framework, and implementation support
            tailored to your school's timetable.
        </p>
        <div>
            <a href="{{ route('register.school') }}" class="inline-flex items-center justify-center rounded-lg px-6 py-3 font-semibold bg-white text-slate-900">
                Register Your School
            </a>
        </div>
    </div>
</section>
@endsection
