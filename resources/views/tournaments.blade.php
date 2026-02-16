@extends('layouts.public')

@section('content')
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Chess Tournaments</h1>
        <p class="text-lg text-gray-700 max-w-3xl">
            We organize school, community, and inter‑school tournaments to build confidence,
            sportsmanship, and competitive thinking.
        </p>
    </div>
</section>

<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-6">
        <div class="bg-white border rounded-xl p-6">
            <h3 class="text-xl font-semibold mb-2">School Tournaments</h3>
            <p class="text-gray-600">Termly competitions for classes and school houses.</p>
        </div>
        <div class="bg-white border rounded-xl p-6">
            <h3 class="text-xl font-semibold mb-2">Inter‑School Events</h3>
            <p class="text-gray-600">Regional contests that connect schools and celebrate talent.</p>
        </div>
        <div class="bg-white border rounded-xl p-6">
            <h3 class="text-xl font-semibold mb-2">Community Events</h3>
            <p class="text-gray-600">Open tournaments for neighborhoods and chess clubs.</p>
        </div>
        <div class="bg-white border rounded-xl p-6">
            <h3 class="text-xl font-semibold mb-2">Training Camps</h3>
            <p class="text-gray-600">Focused preparation for upcoming competitions.</p>
        </div>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-2xl font-semibold mb-3">Want a tournament in your school?</h2>
        <p class="text-gray-700 mb-6">
            We plan and host tournaments tailored to your school size and goals.
        </p>
        <a href="{{ route('contact') }}" class="bg-gray-900 text-white px-4 py-2 rounded">
            Contact Us
        </a>
    </div>
</section>
@endsection
