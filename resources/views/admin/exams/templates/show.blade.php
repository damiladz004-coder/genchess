@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="text-3xl gc-heading">{{ $template->title }}</h2>
            <p class="text-sm text-slate-600">{{ $template->description ?? 'No description' }}</p>
            <p class="text-sm text-slate-500">
                Class: {{ $template->classroom->name ?? 'N/A' }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.exams.templates.edit', $template) }}" class="gc-btn-primary">Edit Template</a>
            <form method="POST" action="{{ route('admin.exams.templates.destroy', $template) }}" onsubmit="return confirm('Delete this exam template? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="gc-btn-secondary text-rose-700">Delete</button>
            </form>
            <a href="{{ route('admin.exams.templates.index') }}" class="gc-btn-secondary">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-6">
        <div class="gc-panel p-4">
            <h3 class="font-semibold mb-3">Add Question (MCQ)</h3>
            <form method="POST" action="{{ route('admin.exams.templates.questions.store', $template) }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Question</label>
                    <textarea name="question_text" rows="3" class="w-full" required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Question Image (optional)</label>
                    <input type="file" name="question_image" accept=".jpg,.jpeg,.png,.webp" class="w-full">
                    <p class="text-xs text-slate-500 mt-1">Use this for chess board diagrams and puzzle images.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Option A</label>
                        <input type="text" name="option_a" class="w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Option B</label>
                        <input type="text" name="option_b" class="w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Option C</label>
                        <input type="text" name="option_c" class="w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Option D</label>
                        <input type="text" name="option_d" class="w-full" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Marks</label>
                        <input type="number" name="marks" class="w-full" min="1" max="20" value="1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Correct Option</label>
                        <select name="correct_option" class="w-full" required>
                            <option value="">Select</option>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="gc-btn-primary">Add Question</button>
            </form>
        </div>

        <div class="gc-panel p-4">
            <h3 class="font-semibold mb-3">Questions</h3>
            <div class="flex items-center gap-3 mb-3 flex-wrap">
                <a href="{{ route('admin.exams.templates.import.template') }}" class="gc-btn-secondary text-sm">Download CSV Template</a>
                <a href="{{ route('admin.exams.templates.export', $template) }}" class="gc-btn-primary text-sm">Export CSV</a>
                <form method="POST" action="{{ route('admin.exams.templates.import', $template) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center gap-2">
                        <input type="file" name="csv_file" accept=".csv,text/csv" class="text-sm" required>
                        <button type="submit" class="gc-btn-secondary text-sm">Import CSV</button>
                    </div>
                </form>
            </div>
            @if($template->questions->isEmpty())
                <p>No questions yet.</p>
            @else
                @if(session('import_errors') && count(session('import_errors')))
                    <div class="mb-3 gc-panel p-3 border-amber-200 bg-amber-50 text-amber-800 text-sm">
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
                            @if($q->question_image_path)
                                <div class="mt-2">
                                    <img src="{{ $q->question_image_path }}" alt="Question diagram" class="max-h-56 rounded border">
                                </div>
                            @endif
                            <div class="text-sm text-slate-600">
                                @foreach($q->options as $opt)
                                    <div>
                                        {{ chr(64 + $opt->position) }}. {{ $opt->option_text }}
                                        @if($opt->is_correct)
                                            <span class="text-emerald-700">(Correct)</span>
                                        @endif
                                    </div>
                                @endforeach
                                <div class="text-xs text-slate-500 mt-1">Marks: {{ $q->marks }}</div>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>
</div>
@endsection
