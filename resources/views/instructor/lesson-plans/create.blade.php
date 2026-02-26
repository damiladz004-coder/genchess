@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Create Lesson Plan</h2>
        <a href="{{ route('instructor.lesson-plans.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    <form method="POST" action="{{ route('instructor.lesson-plans.store') }}" enctype="multipart/form-data" class="gc-panel p-5 space-y-4">
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
            <label class="block text-sm font-medium text-slate-600 mb-1">Lesson Date</label>
            <input type="date" name="lesson_date">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Topic</label>
            <input type="text" name="topic" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Scheme of Work Reference</label>
            <input type="text" name="scheme_reference">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Objectives</label>
            <textarea name="objectives" rows="3"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Materials Required</label>
            <textarea name="materials_required" rows="3" placeholder="Chess boards, demo board, timer, worksheets, projector..."></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Resource Text Content</label>
            <textarea name="resource_text_content" rows="4" placeholder="Key definitions, examples, puzzle set, teaching script, and reading text..."></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Resource Links (one per line)</label>
            <textarea name="resource_links_text" rows="3" placeholder="https://example.com/video-lesson&#10;https://example.com/worksheet"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Upload Resource Files</label>
            <input type="file" name="resource_files[]" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.mp4,.mov,.avi,.mkv,.webm,.jpg,.jpeg,.png,.gif">
            <p class="mt-1 text-xs text-slate-500">Upload videos, documents, images, or text files. Maximum 10 files.</p>
        </div>
        <div class="border border-slate-200 rounded-lg p-4 space-y-3">
            <h3 class="text-base font-semibold text-slate-800">Procedure / Sequence (WIPPEA Model)</h3>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">W - Warm Up</label>
                <textarea name="wippea_warm_up" rows="2" placeholder="Starter activity, recap, or quick puzzle"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">I - Introduction</label>
                <textarea name="wippea_introduction" rows="2" placeholder="State objectives and connect to prior learning"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">P - Presentation</label>
                <textarea name="wippea_presentation" rows="2" placeholder="Direct instruction, modeling, or demonstration"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">P - Practice</label>
                <textarea name="wippea_practice" rows="2" placeholder="Guided and independent practice activities"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">E - Evaluation</label>
                <textarea name="wippea_evaluation" rows="2" placeholder="Check for understanding and assessment method"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">A - Application</label>
                <textarea name="wippea_application" rows="2" placeholder="Real-life transfer, assignment, or extension task"></textarea>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Notes</label>
            <textarea name="notes" rows="3"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
            <select name="status" required>
                <option value="planned">Planned</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="gc-btn-primary">Save</button>
            <a href="{{ route('instructor.lesson-plans.index') }}" class="gc-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
