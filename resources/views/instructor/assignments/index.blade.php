@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">My Class Assignments</h2>
        <a href="{{ route('instructor.dashboard') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">{{ session('error') }}</div>
    @endif

    @if($classes->isEmpty())
        <div class="gc-panel p-6 text-slate-600">You have no assigned classes.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>School</th>
                        <th>Class</th>
                        <th>Level</th>
                        <th>Chess Mode</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $classroom)
                        <tr>
                            <td>{{ $classroom->school->school_name ?? 'N/A' }}</td>
                            <td class="font-medium text-slate-800">{{ $classroom->name }}</td>
                            <td>{{ ucfirst($classroom->level) }}</td>
                            <td>{{ ucfirst($classroom->chess_mode) }}</td>
                            <td>
                                <form method="POST" action="{{ route('instructor.assignments.destroy', $classroom) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 text-sm font-semibold">Remove</button>
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
