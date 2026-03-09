@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl gc-heading">Instructor Dashboard</h2>
            <p class="text-slate-600 text-sm">Welcome, {{ auth()->user()->name ?? 'User' }}</p>
        </div>
        <div class="gc-panel px-4 py-2 text-sm text-slate-600">
            Classes: {{ $classes->count() }}
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="gc-panel p-4">
            <h3 class="text-lg font-semibold mb-3">Your Classes</h3>
            @if($classes->isEmpty())
                <p class="text-slate-600">No classes assigned yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($classes as $class)
                        <div class="border border-slate-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold">{{ $class->name }}</h4>
                                    <p class="text-sm text-slate-600">
                                        Level: {{ strtoupper($class->level) }} - Mode: {{ ucfirst($class->chess_mode) }}
                                    </p>
                                </div>
                                <div class="text-sm text-slate-600">Students: {{ $class->students_count ?? 0 }}</div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('instructor.attendance.index', $class->id) }}" class="gc-btn-primary text-xs px-3 py-1.5">
                                    Take Attendance
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="gc-panel p-4">
            <h3 class="text-lg font-semibold mb-3">Quick Links</h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('instructor.timetable.index') }}" class="gc-btn-secondary">Timetable</a>
                <a href="{{ route('instructor.lesson-plans.index') }}" class="gc-btn-secondary">Lesson Plans</a>
                <a href="{{ route('instructor.scheme.index') }}" class="gc-btn-secondary">Scheme of Work</a>
                <a href="{{ route('instructor.training.index') }}" class="gc-btn-secondary">Training</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="gc-panel p-4">
            <h3 class="text-lg font-semibold mb-3">Upcoming Lessons</h3>
            @if($upcomingLessons->isEmpty())
                <p class="text-slate-600 text-sm">No upcoming lessons.</p>
            @else
                <ul class="text-sm text-slate-700 space-y-1">
                    @foreach($upcomingLessons as $lesson)
                        <li>{{ $lesson->lesson_date?->format('Y-m-d') ?? '-' }} - {{ $lesson->topic }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="gc-panel p-4">
            <h3 class="text-lg font-semibold mb-3">Attendance Summary (Last 7 Days)</h3>
            <div class="text-sm text-slate-700">
                Present: {{ $attendanceSummary['present'] ?? 0 }}<br>
                Absent: {{ $attendanceSummary['absent'] ?? 0 }}
            </div>
        </div>
    </div>
</div>
@endsection
