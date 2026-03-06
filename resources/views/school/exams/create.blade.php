@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Create Exam</h2>
        <a href="{{ route('school.exams.index') }}" class="gc-btn-secondary">Back to Exams</a>
    </div>

    @if($errors->any())
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('school.exams.store') }}" class="gc-panel p-4 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
            <select name="class_id" required>
                <option value="">Select class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Title</label>
            <input name="title" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Term</label>
                <select name="term" required>
                    <option value="">Select term</option>
                    @foreach($terms as $term)
                        <option value="{{ $term }}">{{ $term }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Session</label>
                <select name="session" required>
                    <option value="">Select session</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session }}">{{ $session }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Exam Date</label>
                <input type="date" name="exam_date">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Total Marks</label>
                <input type="number" name="total_marks" value="100" min="1" max="1000">
            </div>
        </div>

        <button type="submit" class="gc-btn-primary">Create Exam</button>
    </form>
</div>
@endsection
