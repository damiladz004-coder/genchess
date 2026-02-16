@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Add Timetable Entry</h2>
        <a href="{{ route('school.timetables.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    <form method="POST" action="{{ route('school.timetables.store') }}" class="gc-panel p-5 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
            <select name="class_id" required>
                <option value="">Select class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Day of Week</label>
            <select name="day_of_week" required>
                @foreach($days as $day)
                    <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Start Time</label>
                <input type="time" name="start_time">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">End Time</label>
                <input type="time" name="end_time">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Location</label>
            <input type="text" name="location">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Notes</label>
            <textarea name="notes" rows="3"></textarea>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="gc-btn-primary">Save</button>
            <a href="{{ route('school.timetables.index') }}" class="gc-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
