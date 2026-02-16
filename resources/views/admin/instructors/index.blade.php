@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Chess Instructors</h2>
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 underline">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 text-red-600">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white border rounded p-4">
            <h3 class="font-semibold mb-3">Add Instructor</h3>
            <form method="POST" action="{{ route('admin.instructors.store') }}">
                @csrf
                <div class="grid gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Name</label>
                        <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Phone</label>
                        <input type="text" name="phone" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Certification Level</label>
                        <input type="text" name="certification_level" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status" class="w-full border rounded px-3 py-2" required>
                            @foreach($statusOptions as $statusOption)
                                <option value="{{ $statusOption }}">{{ ucfirst($statusOption) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Password</label>
                        <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-blue-700 text-white px-4 py-2 rounded">
                    Create Instructor
                </button>
            </form>
        </div>

        <div class="bg-white border rounded p-4">
            <h3 class="font-semibold mb-3">Filter</h3>
            <form method="GET">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="all">All statuses</option>
                    @foreach($statusOptions as $statusOption)
                        <option value="{{ $statusOption }}" @selected(request('status') == $statusOption)>
                            {{ ucfirst($statusOption) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="mt-4 bg-gray-900 text-white px-4 py-2 rounded">
                    Apply
                </button>
            </form>
        </div>
    </div>

    @if($instructors->isEmpty())
        <p>No instructors found.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Name</th>
                        <th class="text-left px-4 py-2 border-b">Email</th>
                        <th class="text-left px-4 py-2 border-b">Phone</th>
                        <th class="text-left px-4 py-2 border-b">Certification</th>
                        <th class="text-left px-4 py-2 border-b">Status</th>
                        <th class="text-left px-4 py-2 border-b">Assignments</th>
                        <th class="text-left px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instructors as $instructor)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $instructor->name }}</td>
                            <td class="px-4 py-2">{{ $instructor->email }}</td>
                            <td class="px-4 py-2">{{ $instructor->phone ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $instructor->certification_level ?? '-' }}</td>
                            <td class="px-4 py-2">{{ ucfirst($instructor->status ?? 'active') }}</td>
                            <td class="px-4 py-2">
                                <a class="text-blue-600 underline" href="{{ route('admin.instructors.show', $instructor) }}">
                                    {{ $instructor->teachingClasses->count() }} classes
                                </a>
                            </td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('admin.instructors.status', $instructor) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="border px-2 py-1">
                                        @foreach($statusOptions as $option)
                                            <option value="{{ $option }}" @selected($instructor->status === $option)>
                                                {{ ucfirst($option) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="ml-2 bg-gray-800 text-white px-3 py-1 rounded">
                                        Update
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.instructors.reset-link', $instructor) }}" class="mt-2">
                                    @csrf
                                    <button type="submit" class="bg-blue-700 text-white px-3 py-1 rounded">
                                        Send Reset Link
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
