@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Class Results</h2>
        <a href="{{ route('instructor.results.create') }}" class="gc-btn-primary">Enter Result</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="gc-panel p-4 grid md:grid-cols-4 gap-3">
        <select name="class_id">
            <option value="">All Classes</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
            @endforeach
        </select>
        <input type="text" name="term" placeholder="Term" value="{{ request('term') }}">
        <input type="text" name="academic_session" placeholder="Session e.g. 2025/2026" value="{{ request('academic_session') }}">
        <button type="submit" class="gc-btn-secondary">Filter</button>
    </form>

    <div class="gc-panel overflow-x-auto">
        <table class="gc-table min-w-full">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Class</th>
                    <th>Term</th>
                    <th>Session</th>
                    <th>Final %</th>
                    <th>Grade</th>
                    <th>Mode</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
                    <tr>
                        <td>{{ $result->student->first_name ?? '' }} {{ $result->student->last_name ?? '' }}</td>
                        <td>{{ $result->classroom->name ?? '-' }}</td>
                        <td>{{ $result->term }}</td>
                        <td>{{ $result->academic_session }}</td>
                        <td>{{ $result->final_percentage }}</td>
                        <td>{{ $result->grade }}</td>
                        <td>{{ ucfirst($result->exam_mode) }}</td>
                        <td><a href="{{ route('instructor.results.edit', $result) }}" class="text-brand-700 underline">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $results->links() }}
</div>
@endsection
