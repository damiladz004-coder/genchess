@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Exams (Read Only)</h2>
        <a href="{{ route('school.exams.assignments.index') }}" class="gc-btn-secondary">Exam Questions</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($exams->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No manual exam records yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Class</th>
                        <th>Term</th>
                        <th>Session</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exams as $exam)
                        <tr>
                            <td>{{ $exam->title }}</td>
                            <td>{{ $exam->classroom->name ?? 'N/A' }}</td>
                            <td>{{ $exam->term }}</td>
                            <td>{{ $exam->session }}</td>
                            <td>{{ $exam->exam_date ?? '-' }}</td>
                            <td>
                                <a href="{{ route('school.exams.show', $exam) }}" class="text-brand-700 underline">View Results</a>
                                <span class="text-slate-400 px-1">|</span>
                                <form method="POST" action="{{ route('school.exams.destroy', $exam) }}" style="display:inline;" onsubmit="return confirm('Delete this exam? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-700 underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
