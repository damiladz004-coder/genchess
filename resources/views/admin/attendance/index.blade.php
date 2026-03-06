@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Attendance (Read-Only)</h2>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.attendance.export', request()->query()) }}" class="gc-btn-secondary text-xs px-3 py-1.5">Export CSV</a>
            <a href="{{ route('admin.attendance.export-summary', array_merge(request()->query(), ['group' => 'school'])) }}" class="gc-btn-secondary text-xs px-3 py-1.5">Summary (School)</a>
            <a href="{{ route('admin.attendance.export-summary', array_merge(request()->query(), ['group' => 'class'])) }}" class="gc-btn-secondary text-xs px-3 py-1.5">Summary (Class)</a>
            <a href="{{ route('admin.attendance.export-summary', array_merge(request()->query(), ['group' => 'instructor'])) }}" class="gc-btn-secondary text-xs px-3 py-1.5">Summary (Instructor)</a>
            <a href="{{ route('admin.attendance.export-summary-all', request()->query()) }}" class="gc-btn-primary text-xs px-3 py-1.5">Export All Summaries</a>
            <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary text-xs px-3 py-1.5">Back to Dashboard</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="gc-panel p-4">
            <div class="text-sm text-slate-500">Total Records</div>
            <div class="text-2xl font-bold">{{ $totalRecords }}</div>
        </div>
        <div class="gc-panel p-4">
            <div class="text-sm text-slate-500">Present</div>
            <div class="text-2xl font-bold">{{ $presentCount }}</div>
        </div>
        <div class="gc-panel p-4">
            <div class="text-sm text-slate-500">Absent</div>
            <div class="text-2xl font-bold">{{ $absentCount }}</div>
        </div>
        <div class="gc-panel p-4">
            <div class="text-sm text-slate-500">Attendance Rate</div>
            <div class="text-2xl font-bold">{{ $attendanceRate }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="gc-panel p-4">
            <h3 class="font-semibold mb-2">By School</h3>
            @if($bySchool->isEmpty())
                <p class="text-slate-600 text-sm">No data.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="gc-table min-w-full">
                        <thead>
                            <tr>
                                <th>School</th>
                                <th>Present</th>
                                <th>Total</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bySchool as $row)
                                @php
                                    $rate = $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0;
                                    $schoolName = $schools->firstWhere('id', $row->school_id)->school_name ?? 'N/A';
                                @endphp
                                <tr>
                                    <td>{{ $schoolName }}</td>
                                    <td>{{ $row->present_count }}</td>
                                    <td>{{ $row->total }}</td>
                                    <td>{{ $rate }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="gc-panel p-4">
            <h3 class="font-semibold mb-2">By Class</h3>
            @if($byClass->isEmpty())
                <p class="text-slate-600 text-sm">No data.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="gc-table min-w-full">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Present</th>
                                <th>Total</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($byClass as $row)
                                @php
                                    $rate = $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0;
                                    $className = $classes->firstWhere('id', $row->class_id)->name ?? 'N/A';
                                @endphp
                                <tr>
                                    <td>{{ $className }}</td>
                                    <td>{{ $row->present_count }}</td>
                                    <td>{{ $row->total }}</td>
                                    <td>{{ $rate }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="gc-panel p-4">
            <h3 class="font-semibold mb-2">By Instructor</h3>
            @if($byInstructor->isEmpty())
                <p class="text-slate-600 text-sm">No data.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="gc-table min-w-full">
                        <thead>
                            <tr>
                                <th>Instructor</th>
                                <th>Present</th>
                                <th>Total</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($byInstructor as $row)
                                @php
                                    $rate = $row->total > 0 ? round(($row->present_count / $row->total) * 100, 1) : 0;
                                    $inst = $instructorMap[$row->marked_by] ?? null;
                                    $instName = $inst ? $inst->name : 'N/A';
                                @endphp
                                <tr>
                                    <td>{{ $instName }}</td>
                                    <td>{{ $row->present_count }}</td>
                                    <td>{{ $row->total }}</td>
                                    <td>{{ $rate }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <form method="GET" class="gc-panel p-4">
        <div class="grid md:grid-cols-3 gap-4">
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
                <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
                <select name="class_id" class="w-full">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Instructor</label>
                <select name="instructor_id" class="w-full">
                    <option value="">All instructors</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" @selected(request('instructor_id') == $instructor->id)>
                            {{ $instructor->name }}{{ $instructor->email ? ' (' . $instructor->email . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">From</label>
                <input type="date" name="from" value="{{ request('from') }}" class="w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">To</label>
                <input type="date" name="to" value="{{ request('to') }}" class="w-full">
            </div>
        </div>

        <div class="mt-4 flex items-center gap-3">
            <button type="submit" class="gc-btn-primary">Filter</button>
            <a href="{{ route('admin.attendance.index') }}" class="gc-btn-secondary">Reset</a>
        </div>
    </form>

    @if($records->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No attendance records found.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>School</th>
                        <th>Class</th>
                        <th>Student</th>
                        <th>Status</th>
                        <th>Marked By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $row)
                        <tr>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->classroom->school->school_name ?? 'N/A' }}</td>
                            <td>{{ $row->classroom->name ?? 'N/A' }}</td>
                            <td>{{ $row->student->first_name ?? '' }} {{ $row->student->last_name ?? '' }}</td>
                            <td>{{ ucfirst($row->status) }}</td>
                            <td>{{ $row->marker->name ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
