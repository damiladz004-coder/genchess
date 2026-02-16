@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Edit Class</h2>
        <a href="{{ route('school.classes.index') }}" class="gc-btn-secondary">Back</a>
    </div>

    <form method="POST" action="{{ route('school.classes.update', $classroom) }}" class="gc-panel p-5 space-y-4">
        @csrf
        @method('PATCH')
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Class Name</label>
            <input type="text" name="name" value="{{ $classroom->name }}" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Level</label>
            <select name="level" required>
                <option value="primary" @selected($classroom->level === 'primary')>Primary</option>
                <option value="jss" @selected($classroom->level === 'jss')>JSS</option>
                <option value="ss" @selected($classroom->level === 'ss')>SS</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Chess Mode</label>
            <select name="chess_mode" required>
                <option value="subject" @selected($classroom->chess_mode === 'subject')>Subject</option>
                <option value="club" @selected($classroom->chess_mode === 'club')>Club</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="gc-btn-primary">Save</button>
            <a href="{{ route('school.classes.index') }}" class="gc-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
