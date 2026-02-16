@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Edit Student</h2>
        <a href="{{ route('school.students.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('school.students.update', $student) }}" class="gc-panel p-5 space-y-4">
        @csrf
        @method('PATCH')
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Gender</label>
            <select name="gender" required>
                <option value="">Select</option>
                <option value="male" @selected(old('gender', $student->gender) === 'male')>Male</option>
                <option value="female" @selected(old('gender', $student->gender) === 'female')>Female</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Class</label>
            <select name="class_id" required>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected(old('class_id', $student->class_id) == $class->id)>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Admission Number (optional)</label>
            <input type="text" name="admission_number" value="{{ old('admission_number', $student->admission_number) }}">
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="gc-btn-primary">Update</button>
            <a href="{{ route('school.students.index') }}" class="gc-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
