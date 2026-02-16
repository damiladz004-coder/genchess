@extends('layouts.public')

@section('content')
<section class="bg-white">
    <div class="max-w-6xl mx-auto px-6 py-16">
        <h1 class="text-4xl font-bold mb-4">Careers: Chess Instructors</h1>
        <p class="text-lg text-gray-700 max-w-3xl">
            Teach structured chess programs in schools and help students build thinking skills.
        </p>
        <div class="mt-6">
            <a href="{{ route('careers') }}" class="bg-gray-900 text-white px-4 py-2 rounded">View Open Roles</a>
        </div>
    </div>
</section>
@endsection
