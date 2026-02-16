@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Class Teacher Feedback (All Schools)</h2>

    <form method="GET" class="mb-4 bg-white border rounded p-4">
        <div class="grid md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">School</label>
                <select name="school_id" class="w-full border rounded px-3 py-2">
                    <option value="">All schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Class</label>
                <select name="class_id" class="w-full border rounded px-3 py-2">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Term</label>
                <select name="term" class="w-full border rounded px-3 py-2">
                    <option value="">All terms</option>
                    @foreach($terms as $term)
                        <option value="{{ $term }}" @selected(request('term') == $term)>
                            {{ $term }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Academic Year</label>
                <select name="academic_year" class="w-full border rounded px-3 py-2">
                    <option value="">All years</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year }}" @selected(request('academic_year') == $year)>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 flex items-center gap-3">
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">Filter</button>
            <a href="{{ route('admin.class-teacher-feedback.index') }}" class="text-gray-700 underline">Reset</a>
        </div>
    </form>

    @if($feedback->isEmpty())
        <p>No feedback submitted yet.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Date</th>
                        <th class="text-left px-4 py-2 border-b">School</th>
                        <th class="text-left px-4 py-2 border-b">Class</th>
                        <th class="text-left px-4 py-2 border-b">Class Teacher</th>
                        <th class="text-left px-4 py-2 border-b">Instructor</th>
                        <th class="text-left px-4 py-2 border-b">Rating</th>
                        <th class="text-left px-4 py-2 border-b">Term</th>
                        <th class="text-left px-4 py-2 border-b">Academic Year</th>
                        <th class="text-left px-4 py-2 border-b">Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedback as $item)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $item->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-2">{{ $item->school->school_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $item->classroom->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $item->classTeacher->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $item->instructor->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $item->rating ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $item->term ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $item->academic_year ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $item->comments }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
