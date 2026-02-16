@extends('layouts.public')

@section('content')
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Chess Products & Supplies</h1>
        <p class="text-lg text-gray-700 max-w-3xl">
            We supply durable, school-ready chess materials for classrooms, clubs, and competitions.
            Order individual items or full school kits.
        </p>
    </div>
</section>

<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-3 gap-6">
        <a href="{{ route('products.boards') }}" class="block bg-white border rounded-xl p-6 hover:shadow">
            <h3 class="text-xl font-semibold mb-2">Chess Boards</h3>
            <p class="text-gray-600">Standard tournament boards and classroom-friendly sets.</p>
        </a>
        <a href="{{ route('products.clocks') }}" class="block bg-white border rounded-xl p-6 hover:shadow">
            <h3 class="text-xl font-semibold mb-2">Chess Clocks</h3>
            <p class="text-gray-600">Reliable digital clocks for training and tournaments.</p>
        </a>
        <a href="{{ route('products.books') }}" class="block bg-white border rounded-xl p-6 hover:shadow">
            <h3 class="text-xl font-semibold mb-2">Books & Materials</h3>
            <p class="text-gray-600">Beginner to advanced learning resources and worksheets.</p>
        </a>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-2xl font-semibold mb-3">Bulk School Kits</h2>
        <p class="text-gray-700 mb-6">
            We can equip an entire school with boards, clocks, and training guides.
        </p>
        <a href="{{ route('contact') }}" class="bg-gray-900 text-white px-4 py-2 rounded">
            Request a Quote
        </a>
    </div>
</section>
@endsection
