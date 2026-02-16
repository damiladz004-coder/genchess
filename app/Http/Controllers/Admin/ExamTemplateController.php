<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamTemplate;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionOption;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class ExamTemplateController extends Controller
{
    public function index()
    {
        $templates = ExamTemplate::query()
            ->withCount('questions')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.exams.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.exams.templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:5|max:240',
        ]);

        ExamTemplate::create($request->only(['title', 'description', 'duration_minutes']));

        return redirect()->route('admin.exams.templates.index')
            ->with('success', 'Exam template created.');
    }

    public function show(ExamTemplate $template)
    {
        $template->load(['questions.options']);

        return view('admin.exams.templates.show', compact('template'));
    }

    public function storeQuestion(Request $request, ExamTemplate $template)
    {
        $request->validate([
            'question_text' => 'required|string',
            'marks' => 'required|integer|min:1|max:20',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:a,b,c,d',
        ]);

        $position = $template->questions()->max('position') ?? 0;
        $position++;

        $question = ExamQuestion::create([
            'exam_template_id' => $template->id,
            'question_text' => $request->question_text,
            'marks' => $request->marks,
            'position' => $position,
        ]);

        $options = [
            ['key' => 'a', 'text' => $request->option_a, 'pos' => 1],
            ['key' => 'b', 'text' => $request->option_b, 'pos' => 2],
            ['key' => 'c', 'text' => $request->option_c, 'pos' => 3],
            ['key' => 'd', 'text' => $request->option_d, 'pos' => 4],
        ];

        foreach ($options as $opt) {
            ExamQuestionOption::create([
                'exam_question_id' => $question->id,
                'option_text' => $opt['text'],
                'position' => $opt['pos'],
                'is_correct' => $request->correct_option === $opt['key'],
            ]);
        }

        return redirect()->route('admin.exams.templates.show', $template)
            ->with('success', 'Question added.');
    }

    public function exportTemplate(ExamTemplate $template)
    {
        $template->load(['questions.options']);

        $headers = [
            'question',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'correct_option',
            'marks',
        ];

        $rows = $template->questions->map(function ($q) {
            $opts = $q->options->sortBy('position')->values();
            $correct = $opts->firstWhere('is_correct', true);
            $correctKey = $correct ? chr(96 + $correct->position) : 'a';

            return [
                'question' => $q->question_text,
                'option_a' => $opts[0]->option_text ?? '',
                'option_b' => $opts[1]->option_text ?? '',
                'option_c' => $opts[2]->option_text ?? '',
                'option_d' => $opts[3]->option_text ?? '',
                'correct_option' => $correctKey,
                'marks' => $q->marks,
            ];
        })->toArray();

        $filename = 'exam_template_' . $template->id . '.csv';

        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename);
    }

    public function downloadImportTemplate()
    {
        $headers = [
            'question',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'correct_option',
            'marks',
        ];

        $example = [
            'What is checkmate?',
            'A draw offer',
            'A check that cannot be escaped',
            'A pawn promotion',
            'A stalemate',
            'b',
            '1',
        ];

        return response()->streamDownload(function () use ($headers, $example) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            fputcsv($out, $example);
            fclose($out);
        }, 'exam_questions_template.csv');
    }

    public function importQuestions(Request $request, ExamTemplate $template)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            return redirect()->back()->with('error', 'Unable to read CSV file.');
        }

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) === 0) {
                continue;
            }
            $rows[] = $data;
        }
        fclose($handle);

        if (count($rows) === 0) {
            return redirect()->back()->with('error', 'CSV file is empty.');
        }

        $header = array_map('strtolower', $rows[0]);
        $hasHeader = in_array('question', $header, true);
        if ($hasHeader) {
            array_shift($rows);
        }

        $created = 0;
        $skipped = 0;
        $errors = [];
        $position = $template->questions()->max('position') ?? 0;

        foreach ($rows as $idx => $row) {
            $row = array_pad($row, 7, '');
            $questionText = trim($row[0] ?? '');
            $optA = trim($row[1] ?? '');
            $optB = trim($row[2] ?? '');
            $optC = trim($row[3] ?? '');
            $optD = trim($row[4] ?? '');
            $correct = strtolower(trim($row[5] ?? 'a'));
            $marks = (int) ($row[6] ?? 1);

            if ($questionText === '' || $optA === '' || $optB === '' || $optC === '' || $optD === '') {
                $skipped++;
                $errors[] = 'Row ' . ($idx + 1 + ($hasHeader ? 1 : 0)) . ': missing question/options.';
                continue;
            }

            if (!in_array($correct, ['a', 'b', 'c', 'd'], true)) {
                $correct = 'a';
            }

            if ($marks <= 0) {
                $marks = 1;
            }

            $position++;

            $question = ExamQuestion::create([
                'exam_template_id' => $template->id,
                'question_text' => $questionText,
                'marks' => $marks,
                'position' => $position,
            ]);

            $options = [
                ['key' => 'a', 'text' => $optA, 'pos' => 1],
                ['key' => 'b', 'text' => $optB, 'pos' => 2],
                ['key' => 'c', 'text' => $optC, 'pos' => 3],
                ['key' => 'd', 'text' => $optD, 'pos' => 4],
            ];

            foreach ($options as $opt) {
                ExamQuestionOption::create([
                    'exam_question_id' => $question->id,
                    'option_text' => $opt['text'],
                    'position' => $opt['pos'],
                    'is_correct' => $correct === $opt['key'],
                ]);
            }

            $created++;
        }

        return redirect()->route('admin.exams.templates.show', $template)
            ->with('success', "Imported {$created} questions. Skipped {$skipped}.")
            ->with('import_errors', $errors);
    }

    public function resetAttempt(\App\Models\ExamAttempt $attempt)
    {
        $assignment = $attempt->assignment()->with(['school', 'classroom'])->first();
        $student = $attempt->student;

        $attempt->answers()->delete();
        $attempt->delete();

        return redirect()->route('admin.exams.attempts.index', [
            'school_id' => $assignment?->school_id,
            'class_id' => $assignment?->class_id,
        ])->with('success', 'Attempt reset. Student can retake the exam.');
    }

    public function attemptsIndex()
    {
        $query = ExamAttempt::query()
            ->with(['assignment.template', 'assignment.classroom', 'assignment.school', 'student'])
            ->orderBy('created_at', 'desc');

        if (request('school_id')) {
            $query->whereHas('assignment', function ($q) {
                $q->where('school_id', request('school_id'));
            });
        }

        if (request('class_id')) {
            $query->whereHas('assignment', function ($q) {
                $q->where('class_id', request('class_id'));
            });
        }

        if (request('q')) {
            $q = request('q');
            $query->whereHas('student', function ($sq) use ($q) {
                $sq->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('admission_number', 'like', "%{$q}%");
            });
        }

        $attempts = $query->get();
        $schools = \App\Models\School::orderBy('school_name')->get(['id', 'school_name']);
        $classes = \App\Models\Classroom::orderBy('name')->get(['id', 'name']);

        return view('admin.exams.attempts.index', compact('attempts', 'schools', 'classes'));
    }
}
