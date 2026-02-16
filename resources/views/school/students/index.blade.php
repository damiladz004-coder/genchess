@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Students</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('school.students.bulk.form') }}" class="gc-btn-secondary">Bulk Upload (CSV)</a>
            <a href="{{ route('school.students.create') }}" class="gc-btn-primary">Add Student</a>
        </div>
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
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Name or admission number">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
                <select name="class_id">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="gc-btn-primary">Filter</button>
                <a href="{{ route('school.students.index') }}" class="gc-btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    @if($students->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No students added yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Admission #</th>
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
                            <td>{{ $student->class->name }}</td>
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
                            <td class="flex items-center gap-3">
                                <a class="text-brand-700 text-sm font-semibold" href="{{ route('school.students.edit', $student) }}">Edit</a>
                                <form method="POST" action="{{ route('school.students.destroy', $student) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 text-sm font-semibold"
                                        onclick="return confirm('Delete this student?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
