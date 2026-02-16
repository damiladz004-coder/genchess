@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    <h2 class="text-xl font-bold mb-4">Create Exam Template</h2>

    <form method="POST" action="{{ route('admin.exams.templates.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-medium mb-1">Description (Optional)</label>
            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block font-medium mb-1">Duration (Minutes, Optional)</label>
            <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}"
                class="w-full border rounded px-3 py-2" min="5" max="240">
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">Create</button>
            <a href="{{ route('admin.exams.templates.index') }}" class="text-gray-700 underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
