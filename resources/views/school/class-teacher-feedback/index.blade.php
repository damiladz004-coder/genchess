@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Class Teacher Feedback</h2>
        <a href="{{ route('school.class-teacher-feedback.create') }}" class="gc-btn-primary">Submit Feedback</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">{{ session('error') }}</div>
    @endif

    @if($feedback->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No feedback submitted yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Class</th>
                        <th>Class Teacher</th>
                        <th>Instructor</th>
                        <th>Rating</th>
                        <th>Term</th>
                        <th>Academic Year</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedback as $item)
                        <tr>
                            <td>{{ $item->created_at->format('Y-m-d') }}</td>
                            <td>{{ $item->classroom->name ?? 'N/A' }}</td>
                            <td>{{ $item->classTeacher->name ?? 'N/A' }}</td>
                            <td>{{ $item->instructor->name ?? 'N/A' }}</td>
                            <td>{{ $item->rating ?? '-' }}</td>
                            <td>{{ $item->term ?? '-' }}</td>
                            <td>{{ $item->academic_year ?? '-' }}</td>
                            <td>{{ $item->comments }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
