@extends('layouts.app')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl gc-heading">Bulk Upload Students</h2>
        <a href="{{ route('school.students.index') }}" class="gc-btn-secondary">Back to Students</a>
    </div>

    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="gc-panel p-4">
        <p class="text-sm text-slate-700 mb-2">CSV columns (in exact order):</p>
        <code class="block text-sm bg-slate-50 border border-slate-200 rounded-lg p-2">
            first_name,last_name,gender,admission_number,class_id
        </code>
        <p class="text-sm text-slate-600 mt-2">
            Gender: <code>male</code> or <code>female</code>. Class ID must belong to your school.
        </p>
    </div>

    <form method="POST" action="{{ route('school.students.bulk.store') }}" enctype="multipart/form-data" class="gc-panel p-4 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">CSV File</label>
            <input type="file" name="csv_file" required>
        </div>
        <button type="submit" class="gc-btn-primary">Upload</button>
    </form>
</div>
@endsection
