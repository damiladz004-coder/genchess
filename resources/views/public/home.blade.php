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
                <a href="{{ route('contact') }}?service=home-tutorial" class="gc-btn-secondary">Book Appointment</a>
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

<section class="py-16 bg-slate-900 text-white">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-3xl md:text-4xl font-display mb-3">Genchess Certified Chess Instructor Program (GCCIP)</h2>
            <p class="text-slate-300 mb-6">
                Build practical teaching capacity, class management skills, and Genchess curriculum delivery standards.
            </p>
            <a href="{{ route('instructor.training') }}" class="inline-flex items-center justify-center rounded-lg px-5 py-3 font-semibold bg-white text-slate-900">
                Register for Training
            </a>
        </div>
        <div>
            <img src="{{ asset('images/instructors/certified-coach.jpg') }}" alt="Chess instructor training" class="w-full rounded-xl2 border border-slate-700">
        </div>
    </div>
</section>
@endsection


