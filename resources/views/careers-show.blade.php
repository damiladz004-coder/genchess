@extends('layouts.public')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">{{ $job->title }}</h1>
        <p class="text-sm text-gray-600">
            {{ $job->location ?? 'Location: Flexible' }}
            @if($job->type) · {{ ucfirst($job->type) }} @endif
        </p>
    </div>

    <div class="bg-white border rounded p-6 mb-8">
        <h2 class="text-lg font-semibold mb-2">Description</h2>
        <p class="text-gray-700 whitespace-pre-line">{{ $job->description }}</p>

        @if($job->requirements)
            <h2 class="text-lg font-semibold mt-6 mb-2">Requirements</h2>
            <p class="text-gray-700 whitespace-pre-line">{{ $job->requirements }}</p>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-50 border border-green-200 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 text-red-700 bg-red-50 border border-red-200 px-4 py-2 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border rounded p-6">
        <h2 class="text-lg font-semibold mb-4">Apply for this role</h2>
        <form method="POST" action="{{ route('careers.apply', $job) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Full Name</label>
                <input name="name" class="border w-full px-3 py-2" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" class="border w-full px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Phone</label>
                    <input name="phone" class="border w-full px-3 py-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium">Cover Letter</label>
                <textarea name="cover_letter" class="border w-full px-3 py-2" rows="4"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium">Upload CV (PDF/DOC)</label>
                <input type="file" name="cv" class="border w-full px-3 py-2">
            </div>
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">
                Submit Application
            </button>
        </form>
    </div>
</div>
@endsection
