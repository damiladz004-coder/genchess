@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="text-3xl gc-heading">{{ $instructor->name }}</h2>
            <p class="text-slate-600">{{ $instructor->email }}</p>
        </div>
        <a href="{{ route('admin.instructors.index') }}" class="gc-btn-secondary">Back to Instructors</a>
    </div>

    <div class="gc-panel p-4">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <div class="text-sm text-slate-500">Phone</div>
                <div>{{ $instructor->phone ?? '-' }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-500">Certification Level</div>
                <div>{{ $instructor->certification_level ?? '-' }}</div>
            </div>
            <div>
                <div class="text-sm text-slate-500">Status</div>
                <div>{{ ucfirst($instructor->status ?? 'active') }}</div>
            </div>
        </div>
    </div>

    <h3 class="text-lg font-semibold">Assigned Classes</h3>
    @if($instructor->teachingClasses->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No class assignments.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>School</th>
                        <th>Class</th>
                        <th>Level</th>
                        <th>Chess Mode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instructor->teachingClasses as $classroom)
                        <tr>
                            <td>{{ $classroom->school->school_name ?? 'N/A' }}</td>
                            <td>{{ $classroom->name }}</td>
                            <td>{{ ucfirst($classroom->level) }}</td>
                            <td>{{ ucfirst($classroom->chess_mode) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
