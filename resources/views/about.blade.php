@extends('layouts.public')

@section('content')

<!-- HERO SECTION -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                About genchess.ng
            </h1>
            <p class="text-lg text-gray-700">
                At genchess.ng, we believe chess is more than a game.
                It is a powerful educational tool that unlocks critical thinking,
                creativity, discipline, and confidence in young minds.
            </p>
        </div>

        <!-- Image placeholder -->
        <img 
            src="{{ asset('images/hero/genchess-hero.jpg') }}" 
            alt="genchess.ng chess training session"
            class="rounded-xl shadow-lg"
        >
    </div>
</section>

<!-- WHO WE ARE -->
<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-10 items-center">
        <img 
            src="{{ asset('images/programs/primary-chess.jpg') }}" 
            alt="Primary school chess training"
            class="rounded-xl shadow-md"
        >

        <div>
            <h2 class="text-3xl font-semibold mb-4">
                Who We Are
            </h2>
            <p class="text-gray-700 leading-relaxed">
                genchess.ng is an educational chess organization focused on
                teaching chess in schools, homes, and communities.
                We work closely with primary and secondary schools to integrate
                chess as both a subject and a club activity.
            </p>
            <p class="text-gray-700 leading-relaxed mt-4">
                Our programs are carefully structured to suit different age groups,
                learning abilities, and educational environments.
            </p>
        </div>
    </div>
</section>

<!-- MISSION & VISION -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-10">
        <div>
            <h2 class="text-3xl font-semibold mb-4">
                Our Mission
            </h2>
            <p class="text-gray-700 leading-relaxed">
                To unlock the genius within every child by using chess as a tool
                for intellectual development, character building, and lifelong learning.
            </p>
        </div>

        <div>
            <h2 class="text-3xl font-semibold mb-4">
                Our Vision
            </h2>
            <p class="text-gray-700 leading-relaxed">
                To become Africa’s leading chess education academy, shaping a generation
                of strategic thinkers, problem solvers, and confident leaders.
            </p>
        </div>
    </div>
</section>

<!-- TEACHING PHILOSOPHY -->
<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-3xl font-semibold mb-4">
                Our Teaching Philosophy
            </h2>
            <ul class="space-y-3 text-gray-700">
                <li>♟️ Learning through play and structured discovery</li>
                <li>♟️ Age-appropriate and child-friendly instruction</li>
                <li>♟️ Emphasis on thinking, not memorization</li>
                <li>♟️ Building patience, focus, and decision-making skills</li>
                <li>♟️ Encouraging sportsmanship and resilience</li>
            </ul>
        </div>

        <img 
            src="{{ asset('images/programs/secondary-chess.jpg') }}" 
            alt="Secondary school chess students"
            class="rounded-xl shadow-md"
        >
    </div>
</section>

<!-- INSTRUCTORS -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-10 items-center">
        <img 
            src="{{ asset('images/instructors/certified-coach.jpg') }}" 
            alt="Certified Genchess instructor"
            class="rounded-xl shadow-md"
        >

        <div>
            <h2 class="text-3xl font-semibold mb-4">
                Our Instructors
            </h2>
            <p class="text-gray-700 leading-relaxed">
                Our instructors are trained educators and chess coaches who understand
                both the game and how children learn.
            </p>
            <p class="text-gray-700 leading-relaxed mt-4">
                Every instructor follows the Genchess curriculum and teaching standards,
                ensuring consistency and quality across all programs.
            </p>
        </div>
    </div>
</section>

<!-- WHY CHESS -->
<section class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-6 py-16 text-center">
        <h2 class="text-3xl font-semibold mb-6">
            Why Chess Matters
        </h2>
        <p class="max-w-3xl mx-auto text-lg text-gray-300">
            Chess improves concentration, memory, logical reasoning, and emotional control.
            At genchess.ng, we use chess to prepare children not just for competitions,
            but for real-life challenges.
        </p>

        <p class="mt-6 text-xl font-semibold">
            “Unlocking the Genius Within.”
        </p>
    </div>
</section>

@endsection

