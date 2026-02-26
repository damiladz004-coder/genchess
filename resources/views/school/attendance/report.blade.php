@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">Attendance Report</h2>

    @if(isset($classes))
        @if($classes->isEmpty())
            <p class="text-gray-600">No classes found for your school yet.</p>
        @else
            <div class="overflow-x-auto bg-white border rounded">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-2 border-b">Class</th>
                            <th class="text-left px-4 py-2 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classes as $classroom)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $classroom->name }}</td>
                                <td class="px-4 py-2 space-x-3">
                                    <a class="text-brand-700 text-sm font-semibold"
                                       href="{{ route('school.attendance.show', $classroom) }}">
                                        View Report
                                    </a>
                                    <a class="text-slate-700 text-sm font-semibold"
                                       href="{{ route('school.attendance.summary', $classroom) }}">
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
        <p class="text-sm text-gray-600 mb-4">Class: {{ $class->name }}</p>
        @if($attendances->isEmpty())
            <p class="text-gray-600">No attendance records yet.</p>
        @else
            @foreach($attendances as $date => $records)
                <div class="mb-6">
                    <h5 class="font-semibold mb-2">
                        {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                    </h5>

                    <div class="overflow-x-auto bg-white border rounded">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left px-4 py-2 border-b">Student</th>
                                    <th class="text-left px-4 py-2 border-b">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($records as $attendance)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">
                                            {{ $attendance->student->first_name }} {{ $attendance->student->last_name }}
                                        </td>
                                        <td class="px-4 py-2">{{ ucfirst($attendance->status) }}</td>
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
