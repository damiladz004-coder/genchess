@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">My Schools & Classes</h2>
        <a href="{{ route('instructor.dashboard') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if($classes->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No classes assigned yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>School</th>
                        <th>Class</th>
                        <th>Schedule</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                        <tr>
                            <td>{{ $class->school->school_name ?? 'N/A' }}</td>
                            <td class="font-medium text-slate-800">{{ $class->name }}</td>
                            <td>
                                @php $items = $schedule[$class->id] ?? []; @endphp
                                {{ empty($items) ? '-' : implode(', ', $items) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
