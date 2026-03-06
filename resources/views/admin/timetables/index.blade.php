@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">School Timetables (Review)</h2>
        <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
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

    <form method="GET" class="gc-panel p-4">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">School</label>
                <select name="school_id" class="w-full">
                    <option value="">All schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                <select name="status" class="w-full">
                    <option value="all">All statuses</option>
                    @foreach($statusOptions as $statusOption)
                        <option value="{{ $statusOption }}" @selected($status === $statusOption)>
                            {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 flex items-center gap-3">
            <button type="submit" class="gc-btn-primary">Filter</button>
            <a href="{{ route('admin.timetables.index') }}" class="gc-btn-secondary">Reset</a>
        </div>
    </form>

    @if($timetables->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No timetable entries found.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>School</th>
                        <th>Class</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Review Comment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timetables as $entry)
                        <tr>
                            <td>{{ $entry->school->school_name ?? 'N/A' }}</td>
                            <td>{{ $entry->classroom->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($entry->day_of_week) }}</td>
                            <td>{{ $entry->start_time ?? '-' }} - {{ $entry->end_time ?? '-' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $entry->status)) }}</td>
                            <td>{{ $entry->review_comment ?? '-' }}</td>
                            <td>
                                <div class="flex flex-col gap-2">
                                    <form method="POST" action="{{ route('admin.timetables.approve', $entry) }}">
                                        @csrf
                                        <button type="submit" class="gc-btn-primary text-xs px-3 py-1.5">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.timetables.request-changes', $entry) }}">
                                        @csrf
                                        <textarea name="review_comment" rows="2" class="w-full" placeholder="Comment"></textarea>
                                        <button type="submit" class="mt-1 gc-btn-secondary text-xs px-3 py-1.5">
                                            Request Changes
                                        </button>
                                    </form>
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
