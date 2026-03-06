@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-3xl gc-heading">Exam Templates</h2>
        <a href="{{ route('admin.exams.templates.create') }}" class="gc-btn-primary">New Template</a>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">
            {{ session('error') }}
        </div>
    @endif

    @if($templates->isEmpty())
        <div class="gc-panel p-6 text-slate-600">No templates yet.</div>
    @else
        <div class="gc-panel overflow-x-auto">
            <table class="gc-table min-w-full">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Class</th>
                        <th>Questions</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                        <tr>
                            <td>{{ $template->title }}</td>
                            <td>
                                {{ $template->classroom->name ?? 'N/A' }}
                            </td>
                            <td>{{ $template->questions_count }}</td>
                            <td>{{ $template->duration_minutes ? $template->duration_minutes . ' mins' : '-' }}</td>
                            <td>
                                <a class="text-brand-700 underline" href="{{ route('admin.exams.templates.show', $template) }}">Manage Questions</a>
                                <span class="text-slate-400 px-1">|</span>
                                <a class="text-brand-700 underline" href="{{ route('admin.exams.templates.edit', $template) }}">Edit</a>
                                <span class="text-slate-400 px-1">|</span>
                                <form method="POST" action="{{ route('admin.exams.templates.destroy', $template) }}" style="display:inline;" onsubmit="return confirm('Delete this exam template? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-700 underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
