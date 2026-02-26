@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Edit Lesson Plan</h2>
        <a href="{{ route('instructor.lesson-plans.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if($lessonPlan->review_status === 'changes_requested' && filled($lessonPlan->review_feedback))
        <div class="gc-panel p-4 border-amber-300 bg-amber-50 text-amber-900">
            <div class="font-semibold mb-1">Super Admin Feedback</div>
            <p class="text-sm">{{ $lessonPlan->review_feedback }}</p>
            <p class="text-xs mt-2 text-amber-700">Update the plan and submit again from the Lesson Plans list.</p>
        </div>
    @endif

    <form method="POST" action="{{ route('instructor.lesson-plans.update', $lessonPlan) }}" enctype="multipart/form-data" class="gc-panel p-5 space-y-4">
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
            <label class="block text-sm font-medium text-slate-600 mb-1">Materials Required</label>
            <textarea name="materials_required" rows="3" placeholder="Chess boards, demo board, timer, worksheets, projector...">{{ $lessonPlan->materials_required }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Resource Text Content</label>
            <textarea name="resource_text_content" rows="4" placeholder="Key definitions, examples, puzzle set, teaching script, and reading text...">{{ $lessonPlan->resource_text_content }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Resource Links (one per line)</label>
            <textarea name="resource_links_text" rows="3" placeholder="https://example.com/video-lesson&#10;https://example.com/worksheet">{{ is_array($lessonPlan->resource_links) ? implode("\n", $lessonPlan->resource_links) : '' }}</textarea>
        </div>

        @if(!empty($lessonPlan->resource_files))
            <div class="border border-slate-200 rounded-lg p-3">
                <h3 class="text-sm font-semibold text-slate-800 mb-2">Existing Resource Files</h3>
                <div class="space-y-2">
                    @foreach($lessonPlan->resource_files as $file)
                        @php
                            $path = is_array($file) ? ($file['path'] ?? null) : $file;
                            $name = is_array($file) ? ($file['name'] ?? basename((string) $path)) : basename((string) $path);
                        @endphp
                        @if($path)
                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input type="checkbox" name="remove_resource_files[]" value="{{ $path }}">
                                Remove
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($path) }}" class="text-brand-700 underline" target="_blank" rel="noopener">
                                    {{ $name }}
                                </a>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Upload New Resource Files</label>
            <input type="file" name="resource_files[]" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.mp4,.mov,.avi,.mkv,.webm,.jpg,.jpeg,.png,.gif">
            <p class="mt-1 text-xs text-slate-500">Upload videos, documents, images, or text files. Maximum 10 files.</p>
        </div>
        <div class="border border-slate-200 rounded-lg p-4 space-y-3">
            <h3 class="text-base font-semibold text-slate-800">Procedure / Sequence (WIPPEA Model)</h3>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">W - Warm Up</label>
                <textarea name="wippea_warm_up" rows="2" placeholder="Starter activity, recap, or quick puzzle">{{ $lessonPlan->wippea_warm_up }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">I - Introduction</label>
                <textarea name="wippea_introduction" rows="2" placeholder="State objectives and connect to prior learning">{{ $lessonPlan->wippea_introduction }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">P - Presentation</label>
                <textarea name="wippea_presentation" rows="2" placeholder="Direct instruction, modeling, or demonstration">{{ $lessonPlan->wippea_presentation }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">P - Practice</label>
                <textarea name="wippea_practice" rows="2" placeholder="Guided and independent practice activities">{{ $lessonPlan->wippea_practice }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">E - Evaluation</label>
                <textarea name="wippea_evaluation" rows="2" placeholder="Check for understanding and assessment method">{{ $lessonPlan->wippea_evaluation }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">A - Application</label>
                <textarea name="wippea_application" rows="2" placeholder="Real-life transfer, assignment, or extension task">{{ $lessonPlan->wippea_application }}</textarea>
            </div>
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
