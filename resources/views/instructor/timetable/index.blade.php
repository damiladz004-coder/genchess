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
    @if($errors->any())
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">{{ $errors->first() }}</div>
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

    <div class="gc-panel p-5 space-y-4">
        <h3 class="text-xl font-semibold text-slate-900">School Timetables Pending Your Review</h3>

        @if($schoolTimetables->isEmpty())
            <p class="text-slate-600 text-sm">No school timetable submissions available for your classes.</p>
        @else
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>School</th>
                            <th>Class</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>School Status</th>
                            <th>Your Review</th>
                            <th>Feedback</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schoolTimetables as $entry)
                            <tr>
                                <td>{{ $entry->school->school_name ?? 'N/A' }}</td>
                                <td>{{ $entry->classroom->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($entry->day_of_week) }}</td>
                                <td>{{ $entry->start_time ?? '-' }} - {{ $entry->end_time ?? '-' }}</td>
                                <td>{{ $entry->location ?? '-' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $entry->status)) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $entry->instructor_review_status ?? 'pending')) }}</td>
                                <td class="max-w-xs">{{ $entry->instructor_review_comment ?? '-' }}</td>
                                <td class="min-w-[280px]">
                                    <form method="POST" action="{{ route('instructor.school-timetables.respond', $entry) }}" class="space-y-2">
                                        @csrf
                                        <select name="instructor_review_status" required>
                                            <option value="">Select response</option>
                                            <option value="accepted">Convenient / Accept</option>
                                            <option value="changes_requested">Request Changes</option>
                                        </select>
                                        <textarea name="instructor_review_comment" rows="2" placeholder="State what to adjust if changes are needed."></textarea>
                                        <button type="submit" class="gc-btn-primary text-xs">Submit Review</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
