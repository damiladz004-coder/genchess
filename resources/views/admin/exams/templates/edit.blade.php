@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <h2 class="text-3xl gc-heading">Edit Exam Template</h2>

    <form method="POST" action="{{ route('admin.exams.templates.update', $template) }}" class="gc-panel p-4 space-y-4">
        @csrf
        @method('PATCH')

        <div>
            <label class="block font-medium mb-1">Target Class</label>
            <select name="class_id" class="w-full" required>
                <option value="">Select class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ old('class_id', $template->class_id) == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $template->title) }}" class="w-full" required>
        </div>

        <div>
            <label class="block font-medium mb-1">Description (Optional)</label>
            <textarea name="description" rows="3" class="w-full">{{ old('description', $template->description) }}</textarea>
        </div>

        <div>
            <label class="block font-medium mb-1">Duration (Minutes, Optional)</label>
            <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $template->duration_minutes) }}"
                class="w-full" min="5" max="240">
        </div>

        <div>
            <label class="block font-medium mb-1">Result Comment (Shown after online submission)</label>
            <input type="text" name="result_comment" value="{{ old('result_comment', $template->result_comment) }}" class="w-full" maxlength="255">
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="gc-btn-primary">Save Changes</button>
            <a href="{{ route('admin.exams.templates.show', $template) }}" class="gc-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
