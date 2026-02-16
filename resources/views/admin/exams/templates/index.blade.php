@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Exam Templates</h2>
        <a href="{{ route('admin.exams.templates.create') }}" class="bg-gray-900 text-white px-4 py-2 rounded">
            New Template
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($templates->isEmpty())
        <p>No templates yet.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-2 border-b">Title</th>
                        <th class="text-left px-4 py-2 border-b">Questions</th>
                        <th class="text-left px-4 py-2 border-b">Duration</th>
                        <th class="text-left px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $template->title }}</td>
                            <td class="px-4 py-2">{{ $template->questions_count }}</td>
                            <td class="px-4 py-2">{{ $template->duration_minutes ? $template->duration_minutes . ' mins' : '-' }}</td>
                            <td class="px-4 py-2">
                                <a class="text-blue-600 underline" href="{{ route('admin.exams.templates.show', $template) }}">Manage Questions</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
