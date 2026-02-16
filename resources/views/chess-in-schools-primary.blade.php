@extends('layouts.public')

@section('content')
<section class="bg-white py-16">
    <div class="max-w-6xl mx-auto px-6 space-y-6">
        <nav aria-label="Breadcrumb" class="text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-slate-700">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('chess.in.schools') }}" class="hover:text-slate-700">Services</a>
            <span class="mx-2">/</span>
            <span class="text-slate-700">Primary 1-6</span>
        </nav>
        <h1 class="text-4xl gc-heading">Chess in Schools - Primary 1-6</h1>
        <p class="text-lg text-slate-700">
            Genchess delivers a structured Primary School chess curriculum that builds strong thinking habits,
            discipline, and confidence from Primary 1 to Primary 6.
        </p>
        <p class="text-slate-700">
            Chess is delivered as a school subject with clear term objectives, weekly lesson plans,
            practical activities, and measurable learning outcomes.
        </p>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-6xl mx-auto px-6 space-y-6">
        <h2 class="text-3xl gc-heading">Primary Progression Pathway</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <article class="gc-panel p-6 space-y-3">
                <h3 class="text-xl font-semibold">Primary 1-2</h3>
                <ul class="space-y-2 text-slate-700">
                    <li>Board orientation: ranks, files, and squares</li>
                    <li>Piece names, movement, and values</li>
                    <li>Simple mini-games and guided play</li>
                    <li>Basic check and checkmate awareness</li>
                </ul>
            </article>
            <article class="gc-panel p-6 space-y-3">
                <h3 class="text-xl font-semibold">Primary 3-4</h3>
                <ul class="space-y-2 text-slate-700">
                    <li>Opening principles and development</li>
                    <li>Tactical themes: forks, pins, skewers</li>
                    <li>Pattern recognition and puzzle drills</li>
                    <li>Recording simple games and reflection</li>
                </ul>
            </article>
            <article class="gc-panel p-6 space-y-3">
                <h3 class="text-xl font-semibold">Primary 5-6</h3>
                <ul class="space-y-2 text-slate-700">
                    <li>Strategic planning and piece coordination</li>
                    <li>Intermediate tactics and endgame basics</li>
                    <li>Structured match practice and mini tournaments</li>
                    <li>Sportsmanship, resilience, and leadership</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6 space-y-6">
        <h2 class="text-3xl gc-heading">What Pupils Gain</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="gc-panel p-6">
                <h3 class="text-xl font-semibold mb-2">Academic Benefits</h3>
                <ul class="space-y-2 text-slate-700">
                    <li>Stronger mathematics reasoning</li>
                    <li>Improved concentration and memory</li>
                    <li>Better problem-solving confidence</li>
                    <li>Improved classroom participation</li>
                </ul>
            </div>
            <div class="gc-panel p-6">
                <h3 class="text-xl font-semibold mb-2">Character Development</h3>
                <ul class="space-y-2 text-slate-700">
                    <li>Patience and emotional control</li>
                    <li>Respect, fairness, and sportsmanship</li>
                    <li>Critical and strategic thinking</li>
                    <li>Resilience after mistakes and losses</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-900 text-white">
    <div class="max-w-6xl mx-auto px-6 space-y-5">
        <h2 class="text-3xl md:text-4xl font-display">Bring Structured Chess to Your Primary School</h2>
        <p class="text-slate-200">
            Partner with Genchess to implement a complete Primary 1-6 chess program with trained instructors,
            term plans, and progress tracking.
        </p>
        <div>
            <a href="{{ route('register.school') }}" class="inline-flex items-center justify-center rounded-lg px-6 py-3 font-semibold bg-white text-slate-900">
                Register Your School
            </a>
        </div>
    </div>
</section>
@endsection
