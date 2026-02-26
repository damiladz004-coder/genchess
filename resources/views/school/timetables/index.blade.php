@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">School Timetable</h2>
        <a href="{{ route('school.timetables.create') }}" class="gc-btn-primary">Add Entry</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">
            {{ session('error') }}
        </div>
    @endif

    @if($timetables->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No timetable entries yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Review Comment</th>
                        <th>Instructor Review</th>
                        <th>Instructor Feedback</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timetables as $entry)
                        <tr>
                            <td class="font-medium text-slate-800">{{ $entry->classroom->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($entry->day_of_week) }}</td>
                            <td>{{ $entry->start_time ?? '-' }} - {{ $entry->end_time ?? '-' }}</td>
                            <td>{{ $entry->location ?? '-' }}</td>
                            <td>
                                @php
                                    $badgeClass = match($entry->status) {
                                        'approved' => 'bg-emerald-100 text-emerald-700',
                                        'changes_requested' => 'bg-rose-100 text-rose-700',
                                        'submitted' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-amber-100 text-amber-700',
                                    };
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $entry->status)) }}
                                </span>
                            </td>
                            <td>{{ $entry->review_comment ?? '-' }}</td>
                            <td>
                                @php
                                    $instructorBadge = match($entry->instructor_review_status) {
                                        'accepted' => 'bg-emerald-100 text-emerald-700',
                                        'changes_requested' => 'bg-rose-100 text-rose-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $instructorBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $entry->instructor_review_status ?? 'pending')) }}
                                </span>
                            </td>
                            <td>{{ $entry->instructor_review_comment ?? '-' }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if(in_array($entry->status, ['draft', 'changes_requested'], true))
                                        <a href="{{ route('school.timetables.edit', $entry) }}" class="text-brand-700 text-sm font-semibold">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('school.timetables.submit', $entry) }}">
                                            @csrf
                                            <button type="submit" class="text-emerald-700 text-sm font-semibold">
                                                Submit
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-500 text-sm">No actions</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
