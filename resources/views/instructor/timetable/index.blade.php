@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Timetable</h2>
        <a href="{{ route('instructor.timetable.create') }}" class="gc-btn-primary">Add Entry</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif

    <form method="GET" class="gc-panel p-4">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
                <select name="class_id">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Day</label>
                <select name="day">
                    <option value="">All days</option>
                    @foreach($days as $day)
                        <option value="{{ $day }}" @selected(request('day') == $day)>{{ $day }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="gc-btn-primary">Filter</button>
                <a href="{{ route('instructor.timetable.index') }}" class="gc-btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    @if($timetable->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No timetable entries yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Class</th>
                        @foreach($days as $day)
                            <th>{{ $day }}</th>
                        @endforeach
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                        <tr>
                            <td class="font-medium text-slate-800">{{ $class->name }}</td>
                            @foreach($days as $day)
                                <td class="text-sm">
                                    @php $slots = $schedule[$class->id][$day] ?? []; @endphp
                                    {{ empty($slots) ? '-' : implode(', ', $slots) }}
                                </td>
                            @endforeach
                            <td>
                                <a href="{{ route('instructor.timetable.create') }}" class="text-brand-700 text-sm font-semibold">Add Entry</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
