@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Manage Classes</h2>
        <a href="{{ route('school.classes.create') }}" class="gc-btn-primary">
            Add Class
        </a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($classes->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No classes have been created for this school yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Level</th>
                        <th>Mode</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                        <tr>
                            <td class="font-medium text-slate-800">{{ $class->name }}</td>
                            <td>{{ ucfirst($class->level) }}</td>
                            <td>{{ ucfirst($class->chess_mode) }}</td>
                            <td>
                                @php
                                    $badgeClass = match($class->status) {
                                        'active' => 'bg-emerald-100 text-emerald-700',
                                        'disabled' => 'bg-rose-100 text-rose-700',
                                        default => 'bg-amber-100 text-amber-700',
                                    };
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                    {{ ucfirst($class->status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="flex items-center gap-3">
                                <a class="text-brand-700 text-sm font-semibold" href="{{ route('school.classes.edit', $class) }}">Edit</a>
                                <a class="text-slate-700 text-sm font-semibold" href="{{ route('school.attendance.create', $class->id) }}">
                                    Take Attendance
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
