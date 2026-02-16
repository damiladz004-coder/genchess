@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Exam Attempts (Super Admin)</h2>
        <a href="{{ route('admin.exams.templates.index') }}" class="text-gray-700 underline">Templates</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="mb-4 bg-white border rounded p-4">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Search student</label>
                <input type="text" name="q" value="{{ request('q') }}" class="w-full border rounded px-3 py-2"
                    placeholder="Name or admission #">
            </div>
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
        </div>

        <div class="mt-4 flex items-center gap-3">
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">Filter</button>
            <a href="{{ route('admin.exams.attempts.index') }}" class="text-gray-700 underline">Reset</a>
        </div>
    </form>

    @if($attempts->isEmpty())
        <p>No attempts recorded.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Student</th>
                        <th class="text-left px-4 py-2 border-b">School</th>
                        <th class="text-left px-4 py-2 border-b">Class</th>
                        <th class="text-left px-4 py-2 border-b">Template</th>
                        <th class="text-left px-4 py-2 border-b">Score</th>
                        <th class="text-left px-4 py-2 border-b">Submitted</th>
                        <th class="text-left px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attempts as $attempt)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $attempt->student->first_name ?? '' }} {{ $attempt->student->last_name ?? '' }}</td>
                            <td class="px-4 py-2">{{ $attempt->assignment->school->school_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $attempt->assignment->classroom->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $attempt->assignment->template->title ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $attempt->score }} / {{ $attempt->total_marks }}</td>
                            <td class="px-4 py-2">{{ optional($attempt->submitted_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('admin.exams.attempts.reset', $attempt) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 underline"
                                        onclick="return confirm('Reset this attempt? The student can retake.')">
                                        Reset
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
