@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold">{{ $exam->title }}</h2>
            <p class="text-sm text-gray-600">
                Class: {{ $exam->classroom->name ?? 'N/A' }} · Term: {{ $exam->term }} · Session: {{ $exam->session }} · Total: {{ $exam->total_marks }}
            </p>
        </div>
        <a href="{{ route('school.exams.index') }}" class="text-blue-600 underline">
            Back to Exams
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-50 border border-green-200 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('school.exams.results.store', $exam) }}">
        @csrf
        <div class="overflow-x-auto bg-white border rounded">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Student</th>
                        <th class="text-left px-4 py-2 border-b">Score</th>
                        <th class="text-left px-4 py-2 border-b">Grade</th>
                        <th class="text-left px-4 py-2 border-b">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        @php
                            $result = $results[$student->id] ?? null;
                        @endphp
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </td>
                            <td class="px-4 py-2">
                                <input type="number"
                                       name="scores[{{ $student->id }}]"
                                       class="border px-2 py-1 w-24"
                                       min="0"
                                       max="{{ $exam->total_marks }}"
                                       value="{{ $result->score ?? '' }}">
                            </td>
                            <td class="px-4 py-2">
                                <input type="text"
                                       name="grades[{{ $student->id }}]"
                                       class="border px-2 py-1 w-24"
                                       value="{{ $result->grade ?? '' }}">
                            </td>
                            <td class="px-4 py-2">
                                <input type="text"
                                       name="remarks[{{ $student->id }}]"
                                       class="border px-2 py-1 w-full"
                                       value="{{ $result->remarks ?? '' }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
            Save Results
        </button>
    </form>
</div>
@endsection
