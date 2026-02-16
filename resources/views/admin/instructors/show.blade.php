@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-xl font-bold">{{ $instructor->name }}</h2>
            <p class="text-gray-600">{{ $instructor->email }}</p>
        </div>
        <a href="{{ route('admin.instructors.index') }}" class="text-blue-600 underline">Back to Instructors</a>
    </div>

    <div class="bg-white border rounded p-4 mb-6">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <div class="text-sm text-gray-500">Phone</div>
                <div>{{ $instructor->phone ?? '-' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Certification Level</div>
                <div>{{ $instructor->certification_level ?? '-' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Status</div>
                <div>{{ ucfirst($instructor->status ?? 'active') }}</div>
            </div>
        </div>
    </div>

    <h3 class="font-semibold mb-2">Assigned Classes</h3>
    @if($instructor->teachingClasses->isEmpty())
        <p>No class assignments.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">School</th>
                        <th class="text-left px-4 py-2 border-b">Class</th>
                        <th class="text-left px-4 py-2 border-b">Level</th>
                        <th class="text-left px-4 py-2 border-b">Chess Mode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instructor->teachingClasses as $classroom)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $classroom->school->school_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $classroom->name }}</td>
                            <td class="px-4 py-2">{{ ucfirst($classroom->level) }}</td>
                            <td class="px-4 py-2">{{ ucfirst($classroom->chess_mode) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
