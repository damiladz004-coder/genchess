@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Chess Instructors</h2>
        <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
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

    <div class="grid md:grid-cols-2 gap-6">
        <div class="gc-panel p-4">
            <h3 class="text-lg font-semibold mb-3">Add Instructor</h3>
            <form method="POST" action="{{ route('admin.instructors.store') }}">
                @csrf
                <div class="grid gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Name</label>
                        <input type="text" name="name" class="w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Email</label>
                        <input type="email" name="email" class="w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Phone</label>
                        <input type="text" name="phone" class="w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Certification Level</label>
                        <input type="text" name="certification_level" class="w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                        <select name="status" class="w-full" required>
                            @foreach($statusOptions as $statusOption)
                                <option value="{{ $statusOption }}">{{ ucfirst($statusOption) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Password</label>
                        <input type="password" name="password" class="w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full" required>
                    </div>
                </div>
                <button type="submit" class="mt-4 gc-btn-primary">Create Instructor</button>
            </form>
        </div>

        <div class="gc-panel p-4">
            <h3 class="text-lg font-semibold mb-3">Filter</h3>
            <form method="GET">
                <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                <select name="status" class="w-full">
                    <option value="all">All statuses</option>
                    @foreach($statusOptions as $statusOption)
                        <option value="{{ $statusOption }}" @selected(request('status') == $statusOption)>
                            {{ ucfirst($statusOption) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="mt-4 gc-btn-secondary">Apply</button>
            </form>
        </div>
    </div>

    @if($instructors->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No instructors found.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Certification</th>
                        <th>Status</th>
                        <th>Assignments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instructors as $instructor)
                        <tr>
                            <td>{{ $instructor->name }}</td>
                            <td>{{ $instructor->email }}</td>
                            <td>{{ $instructor->phone ?? '-' }}</td>
                            <td>{{ $instructor->certification_level ?? '-' }}</td>
                            <td>{{ ucfirst($instructor->status ?? 'active') }}</td>
                            <td>
                                <a class="text-brand-700 underline" href="{{ route('admin.instructors.show', $instructor) }}">
                                    {{ $instructor->teachingClasses->count() }} classes
                                </a>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.instructors.status', $instructor) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status">
                                        @foreach($statusOptions as $option)
                                            <option value="{{ $option }}" @selected($instructor->status === $option)>
                                                {{ ucfirst($option) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="gc-btn-secondary text-xs px-3 py-1.5">Update</button>
                                </form>
                                <form method="POST" action="{{ route('admin.instructors.reset-link', $instructor) }}" class="mt-2">
                                    @csrf
                                    <button type="submit" class="gc-btn-primary text-xs px-3 py-1.5">Send Reset Link</button>
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
