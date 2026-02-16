@extends('layouts.app')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">School Profile</h2>
        <a href="{{ route('school.dashboard') }}" class="gc-btn-secondary">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('school.profile.update') }}" class="gc-panel p-5 space-y-4">
        @csrf
        @method('PATCH')

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">School Name</label>
                <input type="text" name="school_name" value="{{ $school->school_name }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Class System</label>
                @if($school->status === 'active')
                    <input type="text" value="{{ str_replace('_', ' ', $school->class_system) }}" class="bg-slate-100" readonly>
                @else
                    <select name="class_system" required>
                        <option value="primary_jss_ss" @selected($school->class_system === 'primary_jss_ss')>Primary + JSS + SS</option>
                        <option value="grade_1_12" @selected($school->class_system === 'grade_1_12')>Grade 1-12</option>
                        <option value="year_1_12" @selected($school->class_system === 'year_1_12')>Year 1-12</option>
                    </select>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Address Line</label>
                <input type="text" name="address_line" value="{{ $school->address_line }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">City</label>
                <input type="text" name="city" value="{{ $school->city }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">State</label>
                <select name="state" required>
                    @foreach($states as $state)
                        <option value="{{ $state }}" @selected($school->state === $state)>{{ $state }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="gc-btn-primary">Save Changes</button>
    </form>
</div>
@endsection
