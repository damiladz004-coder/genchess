@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-3xl gc-heading">Attendance Report</h2>

    @if(isset($classes))
        @if($classes->isEmpty())
            <div class="gc-panel p-6 text-slate-600">No classes found for your school yet.</div>
        @else
            <div class="gc-panel overflow-x-auto">
                <table class="gc-table min-w-full">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classes as $classroom)
                            <tr>
                                <td>{{ $classroom->name }}</td>
                                <td class="space-x-3">
                                    <a class="text-brand-700 text-sm font-semibold underline" href="{{ route('school.attendance.show', $classroom) }}">
                                        View Report
                                    </a>
                                    <a class="text-slate-700 text-sm font-semibold underline" href="{{ route('school.attendance.summary', $classroom) }}">
                                        View Summary
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <p class="text-sm text-slate-600">Class: {{ $class->name }}</p>
        @if($attendances->isEmpty())
            <div class="gc-panel p-6 text-slate-600">No attendance records yet.</div>
        @else
            @foreach($attendances as $date => $records)
                <div class="space-y-2">
                    <h5 class="font-semibold">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h5>
                    <div class="gc-panel overflow-x-auto">
                        <table class="gc-table min-w-full">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($records as $attendance)
                                    <tr>
                                        <td>{{ $attendance->student->first_name }} {{ $attendance->student->last_name }}</td>
                                        <td>{{ ucfirst($attendance->status) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif
    @endif
</div>
@endsection
