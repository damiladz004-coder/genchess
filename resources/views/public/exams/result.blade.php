@extends('layouts.public')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12 space-y-5">
    <h1 class="text-3xl gc-heading">Exam Result</h1>

    <div class="gc-panel p-6 space-y-3">
        <div><strong>Student:</strong> {{ $attempt->student->first_name ?? '' }} {{ $attempt->student->last_name ?? '' }}</div>
        <div><strong>Exam:</strong> {{ $assignment->template->title ?? 'N/A' }}</div>
        <div><strong>Score:</strong> {{ $attempt->score }} / {{ $attempt->total_marks }}</div>
        <div><strong>Submitted:</strong> {{ optional($attempt->submitted_at)->format('Y-m-d H:i') }}</div>
        @if($assignment->result_comment)
            <div class="pt-3 border-t border-slate-200">
                <strong>Admin Comment:</strong>
                <p class="text-slate-700 mt-1">{{ $assignment->result_comment }}</p>
            </div>
        @endif
    </div>

    <a href="{{ route('public.exams.code') }}" class="gc-btn-secondary">Back to Exam Portal</a>
</div>
@endsection
