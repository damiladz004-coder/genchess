@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    <h2 class="text-xl font-bold mb-4">Assign Exam to Class</h2>

    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('school.exams.assignments.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium mb-1">Exam Template</label>
            <select name="exam_template_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select template</option>
                @foreach($templates as $template)
                    <option value="{{ $template->id }}">{{ $template->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium mb-1">Class</label>
            <select name="class_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Term</label>
                <select name="term" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select term</option>
                    @foreach($terms as $term)
                        <option value="{{ $term }}">{{ $term }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-medium mb-1">Session</label>
                <select name="session" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select session</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session }}">{{ $session }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Mode</label>
                <select name="mode" class="w-full border rounded px-3 py-2" required>
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                    <option value="manual">Manual</option>
                </select>
            </div>
            <div>
                <label class="block font-medium mb-1">Exam Date (Optional)</label>
                <input type="date" name="exam_date" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">Assign</button>
            <a href="{{ route('school.exams.assignments.index') }}" class="text-gray-700 underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
