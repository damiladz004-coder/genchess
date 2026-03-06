@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="text-3xl gc-heading">Exam Results Summary</h2>
            <p class="text-sm text-slate-600">
                {{ $assignment->template->title }} - {{ $assignment->classroom->name ?? 'N/A' }} - {{ $assignment->term }} - {{ $assignment->session }}
            </p>
            @if($assignment->exam_code)
                <p class="text-sm text-slate-500 mt-1">Exam Code: {{ $assignment->exam_code }}</p>
            @endif
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('school.exams.assignments.index') }}" class="gc-btn-secondary">Back</a>
            <a href="{{ route('admin.exams.attempts.index', ['school_id' => $assignment->school_id, 'class_id' => $assignment->class_id]) }}"
               class="gc-btn-primary text-sm">
                Request Retake (Super Admin)
            </a>
        </div>
    </div>

    <div class="grid md:grid-cols-4 gap-4">
        <div class="gc-panel p-4">
            <div class="text-xs text-slate-500">Attempts</div>
            <div class="text-lg font-semibold">{{ $summary['attempts'] }}</div>
        </div>
        <div class="gc-panel p-4">
            <div class="text-xs text-slate-500">Average</div>
            <div class="text-lg font-semibold">{{ $summary['average'] }}</div>
        </div>
        <div class="gc-panel p-4">
            <div class="text-xs text-slate-500">Highest</div>
            <div class="text-lg font-semibold">{{ $summary['highest'] }}</div>
        </div>
        <div class="gc-panel p-4">
            <div class="text-xs text-slate-500">Lowest</div>
            <div class="text-lg font-semibold">{{ $summary['lowest'] }}</div>
        </div>
    </div>

    @if($assignment->result_comment)
        <div class="gc-panel p-4 text-slate-700">
            <strong>Admin Result Comment:</strong> {{ $assignment->result_comment }}
        </div>
    @endif

    @if($attempts->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No attempts yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Score</th>
                        <th>Total Marks</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attempts as $attempt)
                        <tr>
                            <td>{{ $attempt->student->first_name ?? '' }} {{ $attempt->student->last_name ?? '' }}</td>
                            <td>{{ $attempt->score }}</td>
                            <td>{{ $attempt->total_marks }}</td>
                            <td>{{ optional($attempt->submitted_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            <td><span class="text-slate-500 text-sm">Reset via Super Admin</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
