@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-3xl gc-heading">Attendance - {{ $classroom->name }}</h2>

    @forelse($attendances as $date => $records)
        <div class="space-y-2">
            <h4 class="font-semibold">{{ \Carbon\Carbon::parse($date)->toFormattedDateString() }}</h4>

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
    @empty
        <div class="gc-panel p-6 text-slate-600">No attendance records found.</div>
    @endforelse
</div>
@endsection
