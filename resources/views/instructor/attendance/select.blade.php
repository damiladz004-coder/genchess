@extends('layouts.app')

@section('content')
<div class="max-w-4xl space-y-6">
    <h2 class="text-3xl gc-heading">Select Class for Attendance</h2>

    @if($classes->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No classes assigned yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Schedule</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                        <tr>
                            <td class="font-medium text-slate-800">{{ $class->name }}</td>
                            <td>
                                @php $items = $schedule[$class->id] ?? []; @endphp
                                {{ empty($items) ? '-' : implode(', ', $items) }}
                            </td>
                            <td class="flex items-center gap-3">
                                <a class="text-brand-700 text-sm font-semibold" href="{{ route('instructor.attendance.index', $class->id) }}">
                                    Take Attendance
                                </a>
                                <a class="text-slate-700 text-sm font-semibold" href="{{ route('instructor.attendance.index', $class->id) }}?date={{ now()->toDateString() }}">
                                    Take Today
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
