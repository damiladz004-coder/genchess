@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Exam Attempts</h2>
        <a href="{{ route('admin.exams.templates.index') }}" class="gc-btn-secondary">Templates</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="gc-panel p-4">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Search Student</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Name or admission #">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">School</label>
                <select name="school_id">
                    <option value="">All schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
                <select name="class_id">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2">
            <button type="submit" class="gc-btn-primary">Filter</button>
            <a href="{{ route('admin.exams.attempts.index') }}" class="gc-btn-secondary">Reset</a>
        </div>
    </form>

    @if($attempts->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No attempts recorded.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>School</th>
                        <th>Class</th>
                        <th>Template</th>
                        <th>Score</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attempts as $attempt)
                        <tr>
                            <td>
                                {{ $attempt->student->first_name ?? '' }} {{ $attempt->student->last_name ?? '' }}
                                @if(!empty($attempt->student?->admission_number))
                                    <div class="text-xs text-slate-500">{{ $attempt->student->admission_number }}</div>
                                @endif
                            </td>
                            <td>{{ $attempt->assignment->school->school_name ?? 'N/A' }}</td>
                            <td>{{ $attempt->assignment->classroom->name ?? 'N/A' }}</td>
                            <td>{{ $attempt->assignment->template->title ?? 'N/A' }}</td>
                            <td>{{ $attempt->score }} / {{ $attempt->total_marks }}</td>
                            <td>{{ optional($attempt->submitted_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.exams.attempts.reset', $attempt) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-700 underline text-sm"
                                        onclick="return confirm('Reset this attempt? The student can retake.')">
                                        Reset
                                    </button>
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
