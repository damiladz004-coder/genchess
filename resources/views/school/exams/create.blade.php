@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold">Create Exam</h2>
        <a href="{{ route('school.exams.index') }}" class="text-blue-600 underline">
            Back to Exams
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 text-red-700 bg-red-50 border border-red-200 px-4 py-2 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('school.exams.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium">Class</label>
            <select name="class_id" class="border w-full px-3 py-2" required>
                <option value="">Select class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Title</label>
            <input name="title" class="border w-full px-3 py-2" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Term</label>
                <select name="term" class="border w-full px-3 py-2" required>
                    <option value="">Select term</option>
                    @foreach($terms as $term)
                        <option value="{{ $term }}">{{ $term }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Session</label>
                <select name="session" class="border w-full px-3 py-2" required>
                    <option value="">Select session</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session }}">{{ $session }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Exam Date</label>
                <input type="date" name="exam_date" class="border w-full px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium">Total Marks</label>
                <input type="number" name="total_marks" class="border w-full px-3 py-2" value="100" min="1" max="1000">
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Create Exam
        </button>
    </form>
</div>
@endsection
