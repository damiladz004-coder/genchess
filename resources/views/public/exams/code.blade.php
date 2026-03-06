@extends('layouts.public')

@section('content')
<div class="max-w-xl mx-auto px-4 py-12 space-y-5">
    <h1 class="text-3xl gc-heading">Online Exam Portal</h1>
    <p class="text-slate-600">Enter your exam code to start your online chess exam.</p>

    <form method="POST" action="{{ route('public.exams.lookup') }}" class="gc-panel p-5 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Exam Code</label>
            <input type="text" name="exam_code" value="{{ old('exam_code') }}" placeholder="Example: AB12CD34" required>
            @error('exam_code')
                <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="gc-btn-primary">Continue</button>
    </form>
</div>
@endsection
