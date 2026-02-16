@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Instructor Assignments</h2>
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

    @if($classes->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No classes found.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>School</th>
                        <th>Class</th>
                        <th>Assigned Instructors</th>
                        <th>Assign</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                        <tr class="align-top">
                            <td>{{ $class->school->school_name ?? 'N/A' }}</td>
                            <td class="font-medium text-slate-800">{{ $class->name }}</td>
                            <td>
                                @if($class->instructors->isEmpty())
                                    <span class="text-slate-500">None</span>
                                @else
                                    <ul class="space-y-1">
                                        @foreach($class->instructors as $instructor)
                                            <li class="flex items-center justify-between gap-3">
                                                <span>
                                                    {{ $instructor->name }}
                                                    @if($instructor->email)
                                                        <span class="text-xs text-slate-500">({{ $instructor->email }})</span>
                                                    @endif
                                                </span>
                                                <form method="POST"
                                                    action="{{ route('admin.instructor-assignments.destroy', [$class, $instructor]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 text-xs font-semibold"
                                                        onclick="return confirm('Remove this instructor from class?')">
                                                        Remove
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.instructor-assignments.store') }}" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="class_id" value="{{ $class->id }}">
                                    <select name="instructor_id" class="text-sm" required>
                                        <option value="">Select instructor</option>
                                        @foreach($instructors as $instructor)
                                            <option value="{{ $instructor->id }}">
                                                {{ $instructor->name }}{{ $instructor->email ? ' (' . $instructor->email . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="gc-btn-primary text-xs px-3 py-1.5">
                                        Assign
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
