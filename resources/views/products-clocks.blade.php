@extends('layouts.public')

@section('content')
<section class="bg-white">
    <div class="max-w-6xl mx-auto px-6 py-16">
        <h1 class="text-4xl font-bold mb-4">Chess Clocks</h1>
        <p class="text-lg text-gray-700 max-w-3xl">
            Training and tournament-ready clocks with school-friendly pricing options.
        </p>
        <a href="{{ route('contact') }}" class="inline-block mt-6 bg-gray-900 text-white px-4 py-2 rounded">
            Request Quote
        </a>
    </div>
</section>
@endsection
