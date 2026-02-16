@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Students (Approval)</h2>
    </div>

    <form method="GET" class="gc-panel p-4">
        <div class="grid md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Search</label>
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Name, admission #, or school">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">School</label>
                <select name="school_id">
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
                <select name="class_id">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                <select name="status">
                    <option value="all">All statuses</option>
                    @foreach($statusOptions as $statusOption)
                        <option value="{{ $statusOption }}" @selected(request('status') == $statusOption)>
                            {{ ucfirst($statusOption) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 flex items-center gap-3">
            <button type="submit" class="gc-btn-primary">Filter</button>
            <a href="{{ route('admin.students.index') }}" class="gc-btn-secondary">Reset</a>
        </div>
    </form>

    @if($students->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No students found.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Admission #</th>
                        <th>School</th>
                        <th>Class</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td class="font-medium text-slate-800">{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>{{ ucfirst($student->gender) }}</td>
                            <td>{{ $student->admission_number ?? '-' }}</td>
                            <td>{{ $student->school->school_name ?? 'N/A' }}</td>
                            <td>{{ $student->class->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $badgeClass = match($student->status) {
                                        'approved' => 'bg-emerald-100 text-emerald-700',
                                        'rejected' => 'bg-rose-100 text-rose-700',
                                        default => 'bg-amber-100 text-amber-700',
                                    };
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                    {{ ucfirst($student->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    @if($student->status !== 'approved')
                                        <form method="POST" action="{{ route('admin.students.approve', $student) }}">
                                            @csrf
                                            <button type="submit" class="gc-btn-primary text-xs px-3 py-1.5">
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                                    @if($student->status !== 'rejected')
                                        <form method="POST" action="{{ route('admin.students.reject', $student) }}">
                                            @csrf
                                            <button type="submit" class="gc-btn-secondary text-xs px-3 py-1.5">
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.students.move', $student) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="class_id" class="text-sm">
                                            @foreach($classes as $class)
                                                @if($class->id == $student->class_id)
                                                    <option value="{{ $class->id }}" selected>{{ $class->name }}</option>
                                                @elseif($class->school_id == $student->school_id)
                                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="submit" class="gc-btn-secondary text-xs px-3 py-1.5">
                                            Move
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
