@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Submit Class Teacher Feedback</h2>
        <a href="{{ route('school.class-teacher-feedback.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('school.class-teacher-feedback.store') }}" class="gc-panel p-5 space-y-4">
        @csrf
        <div>
            <label class="block font-medium mb-1 text-slate-600">Class</label>
            <select name="class_id" required>
                <option value="">Select class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-medium mb-1 text-slate-600">Class Teacher</label>
            <select name="class_teacher_id" required>
                <option value="">Select class teacher</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(old('class_teacher_id') == $teacher->id)>{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-medium mb-1 text-slate-600">Instructor (Optional)</label>
            <select name="instructor_id">
                <option value="">Select instructor</option>
                @foreach($instructors as $instructor)
                    <option value="{{ $instructor->id }}" @selected(old('instructor_id') == $instructor->id)>
                        {{ $instructor->name }}{{ $instructor->email ? ' (' . $instructor->email . ')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-medium mb-1 text-slate-600">Rating (1-5)</label>
            <select name="rating">
                <option value="">Select rating</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" @selected(old('rating') == $i)>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1 text-slate-600">Term (Optional)</label>
                <input type="text" name="term" value="{{ old('term') }}" placeholder="e.g. 1st Term">
            </div>
            <div>
                <label class="block font-medium mb-1 text-slate-600">Academic Year (Optional)</label>
                <input type="text" name="academic_year" value="{{ old('academic_year') }}" placeholder="e.g. 2025/2026">
            </div>
        </div>
        <div>
            <label class="block font-medium mb-1 text-slate-600">Comments</label>
            <textarea name="comments" rows="4" required>{{ old('comments') }}</textarea>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="gc-btn-primary">Submit</button>
            <a href="{{ route('school.class-teacher-feedback.index') }}" class="gc-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
