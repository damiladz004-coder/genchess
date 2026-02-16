@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Create Class</h2>
        <a href="{{ route('school.classes.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    <form method="POST" action="{{ route('school.classes.store') }}" class="gc-panel p-5 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Class Name</label>
            <input type="text" name="name" placeholder="Primary 1" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Level</label>
            <select name="level" required>
                <option value="">Select Level</option>
                <option value="primary">Primary</option>
                <option value="jss">JSS</option>
                <option value="ss">SS</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Chess Mode</label>
            <select name="chess_mode" required>
                <option value="">Select Mode</option>
                <option value="subject">Subject</option>
                <option value="club">Club</option>
            </select>
        </div>
        <button type="submit" class="gc-btn-primary">Create Class</button>
    </form>
</div>
@endsection
