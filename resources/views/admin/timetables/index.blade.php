@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">School Timetables (Review)</h2>
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 underline">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" class="mb-4 bg-white border rounded p-4">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">School</label>
                <select name="school_id" class="w-full border rounded px-3 py-2">
                    <option value="">All schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
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
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">Filter</button>
            <a href="{{ route('admin.timetables.index') }}" class="text-gray-700 underline">Reset</a>
        </div>
    </form>

    @if($timetables->isEmpty())
        <p>No timetable entries found.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">School</th>
                        <th class="text-left px-4 py-2 border-b">Class</th>
                        <th class="text-left px-4 py-2 border-b">Day</th>
                        <th class="text-left px-4 py-2 border-b">Time</th>
                        <th class="text-left px-4 py-2 border-b">Status</th>
                        <th class="text-left px-4 py-2 border-b">Review Comment</th>
                        <th class="text-left px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timetables as $entry)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $entry->school->school_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $entry->classroom->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ ucfirst($entry->day_of_week) }}</td>
                            <td class="px-4 py-2">{{ $entry->start_time ?? '-' }} - {{ $entry->end_time ?? '-' }}</td>
                            <td class="px-4 py-2">{{ ucfirst(str_replace('_', ' ', $entry->status)) }}</td>
                            <td class="px-4 py-2">{{ $entry->review_comment ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <div class="flex flex-col gap-2">
                                    <form method="POST" action="{{ route('admin.timetables.approve', $entry) }}">
                                        @csrf
                                        <button type="submit" class="bg-green-700 text-white px-3 py-1 rounded">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.timetables.request-changes', $entry) }}">
                                        @csrf
                                        <textarea name="review_comment" rows="2" class="w-full border rounded px-2 py-1" placeholder="Comment"></textarea>
                                        <button type="submit" class="mt-1 bg-amber-700 text-white px-3 py-1 rounded">
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
