@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="text-3xl gc-heading">Grade Manual Exam</h2>
            <p class="text-sm text-slate-600">
                {{ $assignment->template->title ?? 'N/A' }} - {{ $assignment->classroom->name ?? 'N/A' }} - Total: {{ $totalMarks }}
            </p>
        </div>
        <a href="{{ route('instructor.exams.assignments.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('instructor.exams.assignments.grade.store', $assignment) }}">
        @csrf
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        @php($attempt = $attempts[$student->id] ?? null)
                        <tr>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>
                                <input type="number" name="scores[{{ $student->id }}]" min="0" max="{{ $totalMarks }}" value="{{ $attempt->score ?? '' }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="mt-4 gc-btn-primary">Save Scores</button>
    </form>
</div>
@endsection
