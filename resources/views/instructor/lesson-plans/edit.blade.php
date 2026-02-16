@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Edit Lesson Plan</h2>
        <a href="{{ route('instructor.lesson-plans.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    <form method="POST" action="{{ route('instructor.lesson-plans.update', $lessonPlan) }}" class="gc-panel p-5 space-y-4">
        @csrf
        @method('PATCH')
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
            <select name="class_id" required>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected($lessonPlan->class_id == $class->id)>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Lesson Date</label>
            <input type="date" name="lesson_date" value="{{ $lessonPlan->lesson_date?->format('Y-m-d') }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Topic</label>
            <input type="text" name="topic" required value="{{ $lessonPlan->topic }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Scheme of Work Reference</label>
            <input type="text" name="scheme_reference" value="{{ $lessonPlan->scheme_reference }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Objectives</label>
            <textarea name="objectives" rows="3">{{ $lessonPlan->objectives }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Notes</label>
            <textarea name="notes" rows="3">{{ $lessonPlan->notes }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
            <select name="status" required>
                <option value="planned" @selected($lessonPlan->status === 'planned')>Planned</option>
                <option value="completed" @selected($lessonPlan->status === 'completed')>Completed</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="gc-btn-primary">Update</button>
            <a href="{{ route('instructor.lesson-plans.index') }}" class="gc-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
