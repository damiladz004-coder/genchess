@extends('layouts.public')

@section('content')

<!-- HERO SECTION -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16 text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Genchess Instructor Training Program
        </h1>
        <p class="text-lg text-gray-700 max-w-3xl mx-auto">
            A structured professional training program designed to equip
            chess instructors with deep chess knowledge, effective teaching
            skills, and real-world classroom experience.
        </p>
    </div>
</section>

<!-- WHY THIS PROGRAM -->
<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-semibold text-center mb-10">
            Why Train With Genchess?
        </h2>

        <div class="grid md:grid-cols-3 gap-8 text-center">
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-2">Structured Curriculum</h3>
                <p class="text-gray-600">
                    Learn chess from fundamentals to advanced concepts
                    using a carefully designed teaching framework.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-2">Teaching-Focused</h3>
                <p class="text-gray-600">
                    This program goes beyond playing chess —
                    it trains you to teach chess effectively.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-2">Career Pathway</h3>
                <p class="text-gray-600">
                    Certified instructors are eligible for deployment
                    to schools, communities, and Genchess programs.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- PROGRAM STRUCTURE -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-semibold text-center mb-12">
            Program Structure
        </h2>

        <!-- LEVEL 1 -->
        <div class="mb-10">
            <h3 class="text-2xl font-semibold mb-4">
                Level 1 — Beginner Instructor Program
            </h3>
            <p class="text-gray-700 mb-4">
                This level focuses on mastering chess fundamentals
                and learning how to teach beginners confidently.
            </p>

            <ul class="list-disc list-inside text-gray-700 space-y-2">
                <li>Chess board, setup, and piece movement</li>
                <li>Rules, special moves, and chess etiquette</li>
                <li>Basic tactics and strategy</li>
                <li>Opening principles and game phases</li>
                <li>Common checkmate patterns</li>
            </ul>
        </div>

        <!-- LEVEL 2 -->
        <div class="mb-10">
            <h3 class="text-2xl font-semibold mb-4">
                Level 2 — Advanced Instructor Training
            </h3>
            <p class="text-gray-700 mb-4">
                This level prepares instructors for advanced students
                and structured school programs.
            </p>

            <ul class="list-disc list-inside text-gray-700 space-y-2">
                <li>Advanced tactics and combinations</li>
                <li>Endgame fundamentals</li>
                <li>Teaching chess in schools</li>
                <li>Lesson planning and assessments</li>
                <li>Psychology and student development</li>
            </ul>
        </div>

        <!-- TEACHING PRACTICE -->
        <div>
            <h3 class="text-2xl font-semibold mb-4">
                Teaching Practice & Certification
            </h3>
            <ul class="list-disc list-inside text-gray-700 space-y-2">
                <li>Micro-teaching sessions</li>
                <li>Observed teaching practice</li>
                <li>Mentor feedback</li>
                <li>Final evaluation and certification</li>
            </ul>
        </div>
    </div>
</section>

<!-- WHO CAN APPLY -->
<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-semibold text-center mb-10">
            Who Can Apply?
        </h2>

        <div class="grid md:grid-cols-4 gap-6 text-center">
            <div class="bg-white p-5 rounded-xl shadow">Chess Players</div>
            <div class="bg-white p-5 rounded-xl shadow">Teachers & Educators</div>
            <div class="bg-white p-5 rounded-xl shadow">University Students (16+)</div>
            <div class="bg-white p-5 rounded-xl shadow">Youth Leaders</div>
        </div>
    </div>
</section>

<!-- CERTIFICATION BENEFITS -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-semibold text-center mb-10">
            After Certification
        </h2>

        <div class="grid md:grid-cols-3 gap-8 text-center">
            <div class="bg-gray-50 p-6 rounded-xl shadow">
                Teach in Schools
            </div>
            <div class="bg-gray-50 p-6 rounded-xl shadow">
                Community & Home Programs
            </div>
            <div class="bg-gray-50 p-6 rounded-xl shadow">
                Ongoing Professional Development
            </div>
        </div>
    </div>
</section>

<!-- CALL TO ACTION -->
<section class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-6 py-16 text-center">
        <h2 class="text-3xl font-semibold mb-4">
            Become a Certified Genchess Instructor
        </h2>
        <p class="text-lg text-gray-300 max-w-3xl mx-auto mb-8">
            Join a growing network of instructors shaping young minds
            through chess education.
        </p>

        <a 
            href="{{ url('/careers') }}"
            class="inline-block bg-white text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-200 transition"
        >
            Apply Now
        </a>
    </div>
</section>

@endsection
