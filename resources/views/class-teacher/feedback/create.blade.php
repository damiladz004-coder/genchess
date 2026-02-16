@extends('layouts.app')

@section('content')
<div class="max-w-4xl space-y-6">
    <h2 class="text-3xl gc-heading">Submit Feedback</h2>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('class-teacher.feedback.store') }}" class="gc-panel p-5 space-y-4">
        @csrf
        <div>
            <label class="block font-medium mb-1 text-slate-600">Class</label>
            <select name="class_id" required>
                <option value="">Select class</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-medium mb-1 text-slate-600">Instructor</label>
            <select name="instructor_id">
                <option value="">Select instructor</option>
                @foreach($classrooms as $classroom)
                    @foreach($classroom->instructors as $instructor)
                        <option value="{{ $instructor->id }}">{{ $instructor->name }} ({{ $classroom->name }})</option>
                    @endforeach
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-medium mb-1 text-slate-600">Rating (1-5)</label>
            <select name="rating">
                <option value="">Select rating</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1 text-slate-600">Term (Optional)</label>
                <input type="text" name="term" placeholder="e.g. 1st Term">
            </div>
            <div>
                <label class="block font-medium mb-1 text-slate-600">Academic Year (Optional)</label>
                <input type="text" name="academic_year" placeholder="e.g. 2025/2026">
            </div>
        </div>
        <div>
            <label class="block font-medium mb-1 text-slate-600">Comments</label>
            <textarea name="comments" rows="4" required></textarea>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="gc-btn-primary">Submit</button>
            <a href="{{ route('class-teacher.dashboard') }}" class="gc-btn-secondary">Cancel</a>
        </div>
    </form>

    <div class="gc-panel p-4">
        <h3 class="text-lg font-semibold mb-2">Past Feedback</h3>
        @if($feedback->isEmpty())
            <p class="text-slate-600">No feedback submitted yet.</p>
        @else
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Class</th>
                            <th>Instructor</th>
                            <th>Rating</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feedback as $item)
                            <tr>
                                <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                <td>{{ $item->classroom->name ?? 'N/A' }}</td>
                                <td>{{ $item->instructor->name ?? 'N/A' }}</td>
                                <td>{{ $item->rating ?? '-' }}</td>
                                <td>{{ $item->comments }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
