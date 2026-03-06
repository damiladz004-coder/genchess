@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="text-3xl gc-heading">{{ $exam->title }}</h2>
            <p class="text-sm text-slate-600">
                Class: {{ $exam->classroom->name ?? 'N/A' }} - Term: {{ $exam->term }} - Session: {{ $exam->session }} - Total: {{ $exam->total_marks }}
            </p>
        </div>
        <a href="{{ route('school.exams.index') }}" class="gc-btn-secondary">Back to Exams</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="gc-panel overflow-x-auto">
        <table class="gc-table min-w-full">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Score</th>
                    <th>Grade</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    @php($result = $results[$student->id] ?? null)
                    <tr>
                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>{{ $result->score ?? '-' }}</td>
                        <td>{{ $result->grade ?? '-' }}</td>
                        <td>{{ $result->remarks ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
