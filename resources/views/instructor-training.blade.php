@extends('layouts.public')

@section('content')
@php
    $standardPrice = ($course->price_kobo ?? 3500000) / 100;
    $discountPrice = ($course->discount_price_kobo ?? 2500000) / 100;
@endphp

<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 md:py-20">
        <div class="max-w-4xl">
            <h1 class="text-4xl md:text-5xl gc-heading leading-tight">Genchess Chess Instructor Training Program</h1>
            <p class="mt-4 text-lg text-slate-600">
                Premium certification training for chess instructors. Course content is unlocked only after successful payment.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                @auth
                    <a href="{{ route('training.checkout') }}" class="gc-btn-primary">Register Now</a>
                @else
                    <a href="{{ route('register', ['intent' => 'training']) }}" class="gc-btn-primary">Register Now</a>
                @endauth
                <a href="{{ route('contact') }}?service=instructor" class="gc-btn-secondary">Ask a Question</a>
            </div>
        </div>
    </div>
</section>

<section class="bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 md:py-16 grid gap-6 md:grid-cols-2">
        <article class="gc-panel p-6">
            <h2 class="text-2xl gc-heading">Course Overview</h2>
            <p class="mt-3 text-slate-600">Structured training in 8 modules with quizzes, practical assignments, capstone teaching practice, and mentor review.</p>
            <ul class="mt-4 list-disc pl-6 text-slate-700 space-y-1">
                <li>Course duration: {{ $course->duration_label ?? '8 weeks' }}</li>
                <li>Pass mark: 70% minimum on each topic quiz</li>
                <li>Certification: Level 2 Genchess Instructor</li>
            </ul>
        </article>
        <article class="gc-panel p-6">
            <h2 class="text-2xl gc-heading">Learning Objectives</h2>
            <ul class="mt-4 list-disc pl-6 text-slate-700 space-y-1">
                <li>Teach beginner to advanced chess concepts effectively.</li>
                <li>Run school/community chess classes with proper lesson plans.</li>
                <li>Assess learners with practical tasks and quizzes.</li>
                <li>Deliver capstone teaching demonstrations for certification.</li>
            </ul>
        </article>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 md:py-16">
        <h2 class="text-3xl gc-heading mb-6">Modules and Topics Breakdown</h2>
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($curriculum['modules'] as $module)
                <article class="gc-panel p-5">
                    <h3 class="text-xl font-semibold text-slate-900">
                        Module {{ $module['module_number'] ?? $loop->iteration }}: {{ $module['title'] ?? ('Module ' . ($module['module_number'] ?? $loop->iteration)) }}
                    </h3>
                    @if(!empty($module['goal']))
                        <p class="mt-2 text-sm text-slate-600">{{ $module['goal'] }}</p>
                    @endif
                    <ul class="mt-3 list-disc pl-5 text-sm text-slate-700 space-y-1">
                        @foreach(($module['topics'] ?? []) as $topic)
                            <li>{{ $topic['title'] }}</li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 md:py-16 grid gap-6 md:grid-cols-3">
        <article class="rounded-xl border border-slate-700 bg-slate-800 p-6">
            <h3 class="text-xl font-display">Certification</h3>
            <p class="mt-3 text-slate-300 text-sm">Issued only after quizzes, assignments, capstone, and mentor approval.</p>
        </article>
        <article class="rounded-xl border border-slate-700 bg-slate-800 p-6">
            <h3 class="text-xl font-display">Testimonials</h3>
            <p class="mt-3 text-slate-300 text-sm">Coming soon. This section will show trainee results and stories.</p>
        </article>
        <article class="rounded-xl border border-amber-400/40 bg-slate-800 p-6">
            <h3 class="text-xl font-display">Pricing</h3>
            <p class="mt-3 text-slate-300 text-sm">Standard Price: <span class="font-semibold text-white">₦{{ number_format($standardPrice) }}</span></p>
            <p class="mt-1 text-emerald-300 text-sm">Early Bird / Referral Price: <span class="font-semibold">₦{{ number_format($discountPrice) }}</span> (Save ₦10,000)</p>
            <div class="mt-4">
                @auth
                    <a href="{{ route('training.checkout') }}" class="gc-btn-primary">Register Now</a>
                @else
                    <a href="{{ route('register', ['intent' => 'training']) }}" class="gc-btn-primary">Register Now</a>
                @endauth
            </div>
        </article>
    </div>
</section>
@endsection
