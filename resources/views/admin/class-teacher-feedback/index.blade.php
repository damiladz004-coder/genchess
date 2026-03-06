@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-3xl gc-heading">Class Teacher Feedback</h2>

    <form method="GET" class="gc-panel p-4">
        <div class="grid md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">School</label>
                <select name="school_id" class="w-full">
                    <option value="">All schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
                <select name="class_id" class="w-full">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Term</label>
                <select name="term" class="w-full">
                    <option value="">All terms</option>
                    @foreach($terms as $term)
                        <option value="{{ $term }}" @selected(request('term') == $term)>
                            {{ $term }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Academic Year</label>
                <select name="academic_year" class="w-full">
                    <option value="">All years</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year }}" @selected(request('academic_year') == $year)>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 flex items-center gap-2">
            <button type="submit" class="gc-btn-primary">Filter</button>
            <a href="{{ route('admin.class-teacher-feedback.index') }}" class="gc-btn-secondary">Reset</a>
        </div>
    </form>

    @if($feedback->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No feedback submitted yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>School</th>
                        <th>Class</th>
                        <th>Class Teacher</th>
                        <th>Instructor</th>
                        <th>Rating</th>
                        <th>Term</th>
                        <th>Academic Year</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedback as $item)
                        <tr>
                            <td>{{ $item->created_at->format('Y-m-d') }}</td>
                            <td>{{ $item->school->school_name ?? 'N/A' }}</td>
                            <td>{{ $item->classroom->name ?? 'N/A' }}</td>
                            <td>{{ $item->classTeacher->name ?? 'N/A' }}</td>
                            <td>{{ $item->instructor->name ?? 'N/A' }}</td>
                            <td>{{ $item->rating ?? '-' }}</td>
                            <td>{{ $item->term ?? '-' }}</td>
                            <td>{{ $item->academic_year ?? '-' }}</td>
                            <td>{{ $item->comments }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
