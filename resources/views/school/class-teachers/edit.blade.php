@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Edit Class Teacher</h2>
        <a href="{{ route('school.class-teachers.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    <form method="POST" action="{{ route('school.class-teachers.update', $classTeacher) }}" class="gc-panel p-5 space-y-4">
        @csrf
        @method('PATCH')
        <div>
            <label class="block font-medium mb-1 text-slate-600">Teacher Name</label>
            <input type="text" name="name" value="{{ old('name', $classTeacher->name) }}" required>
        </div>
        <div>
            <label class="block font-medium mb-1 text-slate-600">Assigned Class</label>
            <select name="class_id" required>
                <option value="">Select class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected(old('class_id', $classTeacher->class_id) == $class->id)>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-medium mb-1 text-slate-600">Email (Optional)</label>
            <input type="email" name="email" value="{{ old('email', $classTeacher->email) }}">
        </div>
        <div>
            <label class="block font-medium mb-1 text-slate-600">Phone (Optional)</label>
            <input type="text" name="phone" value="{{ old('phone', $classTeacher->phone) }}">
        </div>
        <div>
            <label class="block font-medium mb-1 text-slate-600">Status</label>
            <select name="status" required>
                <option value="active" @selected(old('status', $classTeacher->status) === 'active')>Active</option>
                <option value="inactive" @selected(old('status', $classTeacher->status) === 'inactive')>Inactive</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="gc-btn-primary">Update</button>
            <a href="{{ route('school.class-teachers.index') }}" class="gc-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
