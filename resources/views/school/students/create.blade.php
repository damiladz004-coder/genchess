@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Add Student</h2>
        <a href="{{ route('school.students.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('school.students.store') }}" class="gc-panel p-5 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name') }}" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name') }}" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Gender</label>
            <select name="gender" required>
                <option value="">Select</option>
                <option value="male" @selected(old('gender') === 'male')>Male</option>
                <option value="female" @selected(old('gender') === 'female')>Female</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
            <select name="class_id" required>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Admission Number (optional)</label>
            <input type="text" name="admission_number" value="{{ old('admission_number') }}">
        </div>
        <button type="submit" class="gc-btn-primary">Add Student</button>
    </form>
</div>
@endsection
