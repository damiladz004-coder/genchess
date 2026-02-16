@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Attendance (Read-Only)</h2>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.attendance.export', request()->query()) }}"
               class="bg-gray-900 text-white px-4 py-2 rounded">
                Export CSV
            </a>
            <a href="{{ route('admin.attendance.export-summary', array_merge(request()->query(), ['group' => 'school'])) }}"
               class="bg-gray-700 text-white px-4 py-2 rounded">
                Export Summary (School)
            </a>
            <a href="{{ route('admin.attendance.export-summary', array_merge(request()->query(), ['group' => 'class'])) }}"
               class="bg-gray-700 text-white px-4 py-2 rounded">
                Export Summary (Class)
            </a>
            <a href="{{ route('admin.attendance.export-summary', array_merge(request()->query(), ['group' => 'instructor'])) }}"
               class="bg-gray-700 text-white px-4 py-2 rounded">
                Export Summary (Instructor)
            </a>
            <a href="{{ route('admin.attendance.export-summary-all', request()->query()) }}"
               class="bg-indigo-700 text-white px-4 py-2 rounded">
                Export All Summaries
            </a>
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 underline">Back to Dashboard</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div class="bg-white border rounded p-4">
            <div class="text-sm text-gray-500">Total Records</div>
            <div class="text-2xl font-bold">{{ $totalRecords }}</div>
        </div>
        <div class="bg-white border rounded p-4">
            <div class="text-sm text-gray-500">Present</div>
            <div class="text-2xl font-bold">{{ $presentCount }}</div>
        </div>
        <div class="bg-white border rounded p-4">
            <div class="text-sm text-gray-500">Absent</div>
            <div class="text-2xl font-bold">{{ $absentCount }}</div>
        </div>
        <div class="bg-white border rounded p-4">
            <div class="text-sm text-gray-500">Attendance Rate</div>
            <div class="text-2xl font-bold">{{ $attendanceRate }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="bg-white border rounded p-4">
            <h3 class="font-semibold mb-2">By School</h3>
            @if($bySchool->isEmpty())
                <p class="text-gray-600 text-sm">No data.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-2 py-1 border-b">School</th>
                                <th class="text-left px-2 py-1 border-b">Present</th>
                                <th class="text-left px-2 py-1 border-b">Total</th>
                                <th class="text-left px-2 py-1 border-b">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bySchool as $row)
                                @php
                                    $rate = $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0;
                                    $schoolName = $schools->firstWhere('id', $row->school_id)->school_name ?? 'N/A';
                                @endphp
                                <tr class="border-b">
                                    <td class="px-2 py-1">{{ $schoolName }}</td>
                                    <td class="px-2 py-1">{{ $row->present_count }}</td>
                                    <td class="px-2 py-1">{{ $row->total }}</td>
                                    <td class="px-2 py-1">{{ $rate }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="bg-white border rounded p-4">
            <h3 class="font-semibold mb-2">By Class</h3>
            @if($byClass->isEmpty())
                <p class="text-gray-600 text-sm">No data.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-2 py-1 border-b">Class</th>
                                <th class="text-left px-2 py-1 border-b">Present</th>
                                <th class="text-left px-2 py-1 border-b">Total</th>
                                <th class="text-left px-2 py-1 border-b">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($byClass as $row)
                                @php
                                    $rate = $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0;
                                    $className = $classes->firstWhere('id', $row->class_id)->name ?? 'N/A';
                                @endphp
                                <tr class="border-b">
                                    <td class="px-2 py-1">{{ $className }}</td>
                                    <td class="px-2 py-1">{{ $row->present_count }}</td>
                                    <td class="px-2 py-1">{{ $row->total }}</td>
                                    <td class="px-2 py-1">{{ $rate }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="bg-white border rounded p-4">
            <h3 class="font-semibold mb-2">By Instructor</h3>
            @if($byInstructor->isEmpty())
                <p class="text-gray-600 text-sm">No data.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-2 py-1 border-b">Instructor</th>
                                <th class="text-left px-2 py-1 border-b">Present</th>
                                <th class="text-left px-2 py-1 border-b">Total</th>
                                <th class="text-left px-2 py-1 border-b">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($byInstructor as $row)
                                @php
                                    $rate = $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0;
                                    $inst = $instructorMap[$row->marked_by] ?? null;
                                    $instName = $inst ? $inst->name : 'N/A';
                                @endphp
                                <tr class="border-b">
                                    <td class="px-2 py-1">{{ $instName }}</td>
                                    <td class="px-2 py-1">{{ $row->present_count }}</td>
                                    <td class="px-2 py-1">{{ $row->total }}</td>
                                    <td class="px-2 py-1">{{ $rate }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <form method="GET" class="mb-4 bg-white border rounded p-4">
        <div class="grid md:grid-cols-3 gap-4">
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
                <label class="block text-sm font-medium mb-1">Class</label>
                <select name="class_id" class="w-full border rounded px-3 py-2">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Instructor</label>
                <select name="instructor_id" class="w-full border rounded px-3 py-2">
                    <option value="">All instructors</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" @selected(request('instructor_id') == $instructor->id)>
                            {{ $instructor->name }}{{ $instructor->email ? ' (' . $instructor->email . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">From</label>
                <input type="date" name="from" value="{{ request('from') }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">To</label>
                <input type="date" name="to" value="{{ request('to') }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="mt-4 flex items-center gap-3">
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">Filter</button>
            <a href="{{ route('admin.attendance.index') }}" class="text-gray-700 underline">Reset</a>
        </div>
    </form>

    @if($records->isEmpty())
        <p>No attendance records found.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Date</th>
                        <th class="text-left px-4 py-2 border-b">School</th>
                        <th class="text-left px-4 py-2 border-b">Class</th>
                        <th class="text-left px-4 py-2 border-b">Student</th>
                        <th class="text-left px-4 py-2 border-b">Status</th>
                        <th class="text-left px-4 py-2 border-b">Marked By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $row)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $row->date }}</td>
                            <td class="px-4 py-2">{{ $row->classroom->school->school_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $row->classroom->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">
                                {{ $row->student->first_name ?? '' }} {{ $row->student->last_name ?? '' }}
                            </td>
                            <td class="px-4 py-2">{{ ucfirst($row->status) }}</td>
                            <td class="px-4 py-2">{{ $row->marker->name ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
