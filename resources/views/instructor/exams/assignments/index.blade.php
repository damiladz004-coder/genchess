@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Manual Exam Grading</h2>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif

    @if($assignments->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No manual/offline assignments for your classes yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Template</th>
                        <th>Class</th>
                        <th>Term</th>
                        <th>Session</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                        <tr>
                            <td>{{ $assignment->template->title ?? 'N/A' }}</td>
                            <td>{{ $assignment->classroom->name ?? 'N/A' }}</td>
                            <td>{{ $assignment->term }}</td>
                            <td>{{ $assignment->session }}</td>
                            <td>{{ ucfirst($assignment->status) }}</td>
                            <td>{{ $assignment->exam_date ?? '-' }}</td>
                            <td><a href="{{ route('instructor.exams.assignments.grade', $assignment) }}" class="text-brand-700 underline">Grade Scores</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
