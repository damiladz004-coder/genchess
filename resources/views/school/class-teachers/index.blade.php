@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Class Teachers</h2>
        <a href="{{ route('school.class-teachers.create') }}" class="gc-btn-primary">Add Class Teacher</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">{{ session('error') }}</div>
    @endif

    @if($teachers->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No class teachers added yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teachers as $teacher)
                        <tr>
                            <td class="font-medium text-slate-800">{{ $teacher->name }}</td>
                            <td>{{ $teacher->classroom->name ?? 'N/A' }}</td>
                            <td>{{ $teacher->email ?? '-' }}</td>
                            <td>{{ $teacher->phone ?? '-' }}</td>
                            <td>{{ ucfirst($teacher->status) }}</td>
                            <td class="flex items-center gap-3">
                                <a class="text-brand-700 text-sm font-semibold" href="{{ route('school.class-teachers.edit', $teacher) }}">Edit</a>
                                <form method="POST" action="{{ route('school.class-teachers.status', $teacher) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $teacher->status === 'active' ? 'inactive' : 'active' }}">
                                    <button type="submit" class="text-rose-600 text-sm font-semibold"
                                        onclick="return confirm('Change status for this class teacher?')">
                                        {{ $teacher->status === 'active' ? 'Disable' : 'Activate' }}
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
