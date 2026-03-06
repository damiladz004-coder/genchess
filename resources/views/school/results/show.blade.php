@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Student Result Detail</h2>
        <a href="{{ route('school.results.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    <div class="gc-panel p-4 space-y-2">
        <div><strong>Student:</strong> {{ $result->student->first_name }} {{ $result->student->last_name }}</div>
        <div><strong>Class:</strong> {{ $result->classroom->name ?? '-' }}</div>
        <div><strong>Term/Session:</strong> {{ $result->term }} / {{ $result->academic_session }}</div>
        <div><strong>Final:</strong> {{ $result->final_percentage }}% ({{ $result->grade }})</div>
        <div><strong>Test:</strong> {{ $result->test_score }}/{{ $result->test_max }}</div>
        <div><strong>Practical:</strong> {{ $result->practical_score }}/{{ $result->practical_max }}</div>
        <div><strong>Exam:</strong> {{ $result->exam_score }}/{{ $result->exam_max }} ({{ ucfirst($result->exam_mode) }})</div>
        <div><strong>Instructor Comment:</strong> {{ $result->instructor_comment ?: '-' }}</div>
        <div><strong>System Feedback:</strong> {{ $result->system_feedback ?: '-' }}</div>
        <div><strong>Graded By:</strong> {{ $result->grader->name ?? '-' }} at {{ optional($result->approved_at)->format('Y-m-d H:i') }}</div>
    </div>

    <div class="gc-panel p-4">
        <h3 class="font-semibold mb-2">Audit Trail</h3>
        @if($result->audits->isEmpty())
            <p class="text-slate-500">No audit records.</p>
        @else
            <ul class="space-y-2 text-sm">
                @foreach($result->audits->sortByDesc('changed_at') as $audit)
                    <li>{{ $audit->changed_at?->format('Y-m-d H:i') }} - {{ strtoupper($audit->action) }} by User #{{ $audit->changed_by ?? '-' }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
