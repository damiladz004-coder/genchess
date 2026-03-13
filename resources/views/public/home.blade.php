@extends('layouts.public')

@section('content')
<section class="relative overflow-hidden py-20 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-4xl md:text-5xl gc-heading leading-tight">
                Genchess Educational Services
            </h1>
            <p class="mt-5 text-lg text-slate-600">
                We use chess to build critical thinking, discipline, confidence, and strategic decision-making in children across schools, homes, and communities.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('register.school') }}" class="gc-btn-primary">Register Your School</a>
                <a href="{{ route('contact') }}" class="gc-btn-secondary">Talk to Us</a>
            </div>
        </div>
        <div>
            <img src="{{ asset('images/hero/genchess-hero.jpg') }}" alt="Genchess Educational Services" class="w-full rounded-xl2 shadow-soft border border-slate-200">
        </div>
    </div>
</section>

<section class="py-16">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl gc-heading mb-8">What We Do</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <article class="gc-panel p-5">
                <img src="{{ asset('icons/school.svg') }}" alt="Chess in Schools icon" class="mb-3">
                <h3 class="text-xl font-semibold mb-2">Chess in Schools</h3>
                <p class="text-slate-600 text-sm mb-4">
                    Structured programs for primary and secondary classes, delivered as subject or club.
                </p>
                <a href="{{ route('register.school') }}" class="gc-btn-secondary">Register Your School</a>
            </article>

            <article class="gc-panel p-5">
                <img src="{{ asset('icons/community.svg') }}" alt="Chess in Communities and Homes icon" class="mb-3">
                <h3 class="text-xl font-semibold mb-2">Chess in Communities & Homes</h3>
                <p class="text-slate-600 text-sm mb-4">
                    Home tutorials available online or offline. Families submit location and preference, then a certified instructor is assigned within that area/community.
                </p>
                <a href="{{ route('chess.communities.homes') }}#booking-form" class="gc-btn-secondary">Book Appointment</a>
            </article>

            <article class="gc-panel p-5">
                <img src="{{ asset('icons/chess-board.svg') }}" alt="Chess products icon" class="mb-3">
                <h3 class="text-xl font-semibold mb-2">Chess Products</h3>
                <p class="text-slate-600 text-sm mb-4">
                    Boards, clocks, books, and materials for schools, clubs, and individual learners.
                </p>
                <a href="{{ route('store.index') }}" class="gc-btn-secondary">Shop Products</a>
            </article>
        </div>
    </div>
</section>

<section class="py-16 md:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6 space-y-8 md:space-y-10">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-soft">
            <img
                src="{{ $classroomImages['hero'] ?? asset('images/placeholders/chess-classroom-hero.svg') }}"
                alt="Chess classroom hero placeholder showing instructor teaching students with demonstration board"
                class="w-full h-64 md:h-96 object-cover"
            >
        </div>

        <div class="space-y-3">
            <h2 class="text-3xl md:text-4xl gc-heading">Chess in Schools Classroom Experience</h2>
            <p class="text-slate-600 max-w-3xl">
                See how Genchess runs practical chess lessons inside schools through instructor-led classes, puzzle sessions, and healthy competitions.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <figure class="gc-panel p-3">
                <img src="{{ $classroomImages['lesson'] ?? asset('images/placeholders/chess-classroom-lesson.svg') }}" alt="Classroom chess lesson placeholder" class="w-full h-44 object-cover rounded-lg">
                <figcaption class="mt-3">
                    <h3 class="font-semibold text-slate-900">Classroom Chess Lessons</h3>
                    <p class="text-sm text-slate-600">Students learn openings, tactics, and checkmates.</p>
                </figcaption>
            </figure>

            <figure class="gc-panel p-3">
                <img src="{{ $classroomImages['play'] ?? asset('images/placeholders/chess-classroom-play.svg') }}" alt="Students playing chess in class placeholder" class="w-full h-44 object-cover rounded-lg">
                <figcaption class="mt-3">
                    <h3 class="font-semibold text-slate-900">Students Playing in Class</h3>
                    <p class="text-sm text-slate-600">Learners practice positions and apply lesson concepts.</p>
                </figcaption>
            </figure>

            <figure class="gc-panel p-3">
                <img src="{{ $classroomImages['puzzle'] ?? asset('images/placeholders/chess-classroom-puzzle.svg') }}" alt="Students solving chess puzzles placeholder" class="w-full h-44 object-cover rounded-lg">
                <figcaption class="mt-3">
                    <h3 class="font-semibold text-slate-900">Puzzle Solving Sessions</h3>
                    <p class="text-sm text-slate-600">Students sharpen analysis through guided puzzles.</p>
                </figcaption>
            </figure>

            <figure class="gc-panel p-3">
                <img src="{{ $classroomImages['competition'] ?? asset('images/placeholders/chess-classroom-competition.svg') }}" alt="School chess competition placeholder" class="w-full h-44 object-cover rounded-lg">
                <figcaption class="mt-3">
                    <h3 class="font-semibold text-slate-900">School Chess Competition</h3>
                    <p class="text-sm text-slate-600">Students develop confidence through competition.</p>
                </figcaption>
            </figure>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <article class="gc-panel p-4">
                <div class="w-10 h-10 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17l6-10m-7 5h8M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-900">Critical Thinking</h3>
                <p class="text-sm text-slate-600 mt-1">Chess teaches planning and strategic thinking.</p>
            </article>

            <article class="gc-panel p-4">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17a4 4 0 100-8 4 4 0 000 8zm0 0v4m-7-4h14"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-900">Academic Improvement</h3>
                <p class="text-sm text-slate-600 mt-1">Chess strengthens mathematics and reading skills.</p>
            </article>

            <article class="gc-panel p-4">
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l2.8 5.7L21 9.6l-4.5 4.4 1.1 6.2L12 17.3 6.4 20.2 7.5 14 3 9.6l6.2-.9L12 3z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-900">Confidence</h3>
                <p class="text-sm text-slate-600 mt-1">Students gain confidence through tournament play.</p>
            </article>

            <article class="gc-panel p-4">
                <div class="w-10 h-10 rounded-lg bg-violet-100 text-violet-700 flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-900">Leadership</h3>
                <p class="text-sm text-slate-600 mt-1">Chess clubs build leadership and teamwork.</p>
            </article>
        </div>

        <div class="pt-2">
            <a href="https://school.genchess.ng/register" class="gc-btn-primary inline-flex">
                Register Your School
            </a>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-900 text-white">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl md:text-4xl font-display mb-3">Instructor Pathways</h2>
        <p class="text-slate-300 mb-8">
            Choose the path that matches your experience level.
        </p>
        <div class="grid md:grid-cols-2 gap-6">
            <article class="rounded-xl border border-slate-700 bg-slate-800 p-6">
                <h3 class="text-xl font-semibold mb-3">1. Apply to Become a Chess Instructor (Screening Route)</h3>
                <p class="text-slate-300 text-sm mb-4">
                    For experienced candidates. Stage 1 screening test, Stage 2 interview (chess knowledge), Stage 3 interview (classroom management/teaching).
                </p>
                <a href="{{ route('instructor.screening.create') }}" class="inline-flex items-center justify-center rounded-lg px-5 py-3 font-semibold bg-white text-slate-900">
                    Start Screening Route
                </a>
            </article>

            <article class="rounded-xl border border-slate-700 bg-slate-800 p-6">
                <h3 class="text-xl font-semibold mb-3">2. Apply for Certified Chess Instructor Training Program</h3>
                <p class="text-slate-300 text-sm mb-4">
                    For candidates who need structured preparation. Successful completion leads to certification and Instructor Dashboard access.
                </p>
                <a href="{{ route('instructor.training') }}" class="inline-flex items-center justify-center rounded-lg px-5 py-3 font-semibold bg-white text-slate-900">
                    Apply for Training
                </a>
            </article>
        </div>
        <div class="mt-8">
            <img src="{{ asset('images/instructors/certified-coach.jpg') }}" alt="Chess instructor training" class="w-full rounded-xl2 border border-slate-700">
        </div>
    </div>
</section>
@endsection
