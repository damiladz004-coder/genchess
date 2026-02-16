@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Classes (Review)</h2>
        <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="gc-panel p-4">
        <div class="grid md:grid-cols-2 gap-4">
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
            <a href="{{ route('admin.classes.index') }}" class="gc-btn-secondary">Reset</a>
        </div>
    </form>

    @if($classes->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No classes found.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>School</th>
                        <th>Class</th>
                        <th>Level</th>
                        <th>Chess Mode</th>
                        <th>Academic Level</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $classroom)
                        @php
                            $levelIndex = null;
                            if (preg_match('/(Primary|JSS|SS|Grade|Year)\s*(\d+)/i', $classroom->name, $m)) {
                                $levelIndex = $m[1] . ' ' . $m[2];
                            }
                        @endphp
                        <tr>
                            <td>{{ $classroom->school->school_name ?? 'N/A' }}</td>
                            <td class="font-medium text-slate-800">{{ $classroom->name }}</td>
                            <td>{{ ucfirst($classroom->level) }}</td>
                            <td>{{ ucfirst($classroom->chess_mode) }}</td>
                            <td>{{ $levelIndex ?? '-' }}</td>
                            <td>
                                @php
                                    $badgeClass = match($classroom->status) {
                                        'active' => 'bg-emerald-100 text-emerald-700',
                                        'disabled' => 'bg-rose-100 text-rose-700',
                                        default => 'bg-amber-100 text-amber-700',
                                    };
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                    {{ ucfirst($classroom->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.classes.status', $classroom) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="text-sm">
                                        @foreach($statusOptions as $option)
                                            <option value="{{ $option }}" @selected($classroom->status === $option)>
                                                {{ ucfirst($option) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="gc-btn-secondary text-xs px-3 py-1.5">
                                        Update
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
