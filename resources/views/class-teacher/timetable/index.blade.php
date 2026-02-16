@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Chess Timetable</h2>
        <a href="{{ route('class-teacher.dashboard') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if($entries->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No approved chess periods yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            <td class="font-medium text-slate-800">{{ $entry->classroom->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($entry->day_of_week) }}</td>
                            <td>{{ $entry->start_time ?? '-' }} - {{ $entry->end_time ?? '-' }}</td>
                            <td>{{ $entry->location ?? '-' }}</td>
                            <td>{{ $entry->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
