@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-xl font-bold">{{ $template->title }}</h2>
            <p class="text-sm text-gray-600">
                {{ $template->description ?? 'No description' }}
            </p>
        </div>
        <a href="{{ route('admin.exams.templates.index') }}" class="text-gray-700 underline">Back</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white border rounded p-4">
            <h3 class="font-semibold mb-3">Add Question (MCQ)</h3>
            <form method="POST" action="{{ route('admin.exams.templates.questions.store', $template) }}" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Question</label>
                    <textarea name="question_text" rows="3" class="w-full border rounded px-3 py-2" required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Option A</label>
                        <input type="text" name="option_a" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Option B</label>
                        <input type="text" name="option_b" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Option C</label>
                        <input type="text" name="option_c" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Option D</label>
                        <input type="text" name="option_d" class="w-full border rounded px-3 py-2" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Marks</label>
                        <input type="number" name="marks" class="w-full border rounded px-3 py-2" min="1" max="20" value="1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Correct Option</label>
                        <select name="correct_option" class="w-full border rounded px-3 py-2" required>
                            <option value="">Select</option>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">Add Question</button>
            </form>
        </div>

        <div class="bg-white border rounded p-4">
            <h3 class="font-semibold mb-3">Questions</h3>
            <div class="flex items-center gap-3 mb-3">
                <a href="{{ route('admin.exams.templates.import.template') }}"
                   class="bg-gray-200 text-gray-900 px-3 py-2 rounded text-sm">
                    Download CSV Template
                </a>
                <a href="{{ route('admin.exams.templates.export', $template) }}"
                   class="bg-gray-900 text-white px-3 py-2 rounded text-sm">
                    Export CSV
                </a>
                <form method="POST" action="{{ route('admin.exams.templates.import', $template) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center gap-2">
                        <input type="file" name="csv_file" accept=".csv,text/csv" class="text-sm" required>
                        <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded text-sm">
                            Import CSV
                        </button>
                    </div>
                </form>
            </div>
            @if($template->questions->isEmpty())
                <p>No questions yet.</p>
            @else
                @if(session('import_errors') && count(session('import_errors')))
                    <div class="mb-3 bg-yellow-50 border border-yellow-200 p-3 rounded text-sm">
                        <div class="font-medium mb-1">Import Warnings</div>
                        <ul class="list-disc pl-5">
                            @foreach(session('import_errors') as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <ol class="list-decimal list-inside space-y-3">
                    @foreach($template->questions as $q)
                        <li>
                            <div class="font-medium">{{ $q->question_text }}</div>
                            <div class="text-sm text-gray-600">
                                @foreach($q->options as $opt)
                                    <div>
                                        {{ chr(64 + $opt->position) }}. {{ $opt->option_text }}
                                        @if($opt->is_correct)
                                            <span class="text-green-700">(Correct)</span>
                                        @endif
                                    </div>
                                @endforeach
                                <div class="text-xs text-gray-500 mt-1">Marks: {{ $q->marks }}</div>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>
</div>
@endsection
