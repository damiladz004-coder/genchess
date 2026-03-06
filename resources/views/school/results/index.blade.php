@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Result Overview</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('school.results.summary-pdf', request()->only(['class_id', 'term', 'academic_session'])) }}" class="gc-btn-secondary">Summary PDF</a>
            <a href="{{ route('school.results.export', request()->only(['class_id', 'term', 'academic_session'])) }}" class="gc-btn-secondary">Export CSV</a>
        </div>
    </div>

    <div class="grid md:grid-cols-4 gap-4">
        <div class="gc-panel p-4"><div class="text-xs text-slate-500">Records</div><div class="text-xl font-semibold">{{ $analytics['count'] }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs text-slate-500">Average</div><div class="text-xl font-semibold">{{ $analytics['average'] }}%</div></div>
        <div class="gc-panel p-4"><div class="text-xs text-slate-500">A Grades</div><div class="text-xl font-semibold">{{ $analytics['a_count'] }}</div></div>
        <div class="gc-panel p-4"><div class="text-xs text-slate-500">F Grades</div><div class="text-xl font-semibold">{{ $analytics['f_count'] }}</div></div>
    </div>

    <form method="GET" class="gc-panel p-4 grid md:grid-cols-4 gap-3">
        <select name="class_id">
            <option value="">All Classes</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
            @endforeach
        </select>
        <input type="text" name="term" placeholder="Term" value="{{ request('term') }}">
        <input type="text" name="academic_session" placeholder="Session" value="{{ request('academic_session') }}">
        <button class="gc-btn-secondary" type="submit">Filter</button>
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
                    <th>Graded By</th>
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
                        <td>{{ $result->grader->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('school.results.show', $result) }}" class="text-brand-700 underline">View</a>
                            <span class="text-slate-400 px-1">|</span>
                            <a href="{{ route('school.results.print', $result) }}" class="text-brand-700 underline">Print</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $results->links() }}
</div>
@endsection
