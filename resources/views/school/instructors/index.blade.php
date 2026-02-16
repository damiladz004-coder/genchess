@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Chess Instructors (View Only)</h2>
        <a href="{{ route('school.dashboard') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if($instructors->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No instructors assigned to your school yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Assigned Classes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instructors as $instructor)
                        <tr>
                            <td class="font-medium text-slate-800">{{ $instructor->name }}</td>
                            <td>{{ $instructor->email }}<br>{{ $instructor->phone ?? '-' }}</td>
                            <td>
                                @if($instructor->teachingClasses->isEmpty())
                                    -
                                @else
                                    {{ $instructor->teachingClasses->pluck('name')->implode(', ') }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="gc-panel p-4">
        <h3 class="font-semibold mb-3">Timetable</h3>
        @if($timetables->isEmpty())
            <p class="text-slate-600">No timetable entries yet.</p>
        @else
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Instructor</th>
                            <th>Class</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timetables as $row)
                            <tr>
                                <td>{{ $row->instructor->name ?? 'N/A' }}</td>
                                <td>{{ $row->classroom->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($row->day_of_week) }}</td>
                                <td>{{ $row->start_time }} - {{ $row->end_time }}</td>
                                <td>{{ $row->location ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
