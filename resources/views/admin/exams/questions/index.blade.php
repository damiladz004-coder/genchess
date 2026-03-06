@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-3xl gc-heading">Exam Question Management</h2>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.exams.questions.store') }}" class="gc-panel p-4 space-y-4">
        @csrf
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Exam Template</label>
                <select name="exam_template_id" required>
                    <option value="">Select template</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->title }} - {{ $template->classroom->name ?? 'Class' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Question Type</label>
                <select name="type" required>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="practical">Practical</option>
                    <option value="theory">Theory</option>
                </select>
            </div>
        </div>
        <div class="grid md:grid-cols-4 gap-4">
            <div class="md:col-span-3">
                <label class="block text-sm font-medium mb-1">Question Text</label>
                <textarea name="question_text" rows="3" required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Marks</label>
                <input type="number" name="marks" min="1" max="100" value="1" required>
            </div>
        </div>
        <button type="submit" class="gc-btn-primary">Add Question</button>
    </form>

    <div class="gc-panel overflow-x-auto">
        <table class="gc-table min-w-full">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Template</th>
                    <th>Type</th>
                    <th>Marks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $question)
                    <tr>
                        <td>{{ \Illuminate\Support\Str::limit($question->question_text, 80) }}</td>
                        <td>{{ $question->template->title ?? '-' }}</td>
                        <td>{{ ucfirst($question->type ?? 'multiple_choice') }}</td>
                        <td>{{ $question->marks }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.exams.questions.destroy', $question) }}" onsubmit="return confirm('Delete this question?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-rose-700 underline" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $questions->links() }}
</div>
@endsection
