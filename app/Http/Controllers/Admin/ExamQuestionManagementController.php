<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamQuestion;
use App\Models\ExamTemplate;
use Illuminate\Http\Request;

class ExamQuestionManagementController extends Controller
{
    public function index()
    {
        $questions = ExamQuestion::with('template.classroom')
            ->orderByDesc('id')
            ->paginate(25);
        $templates = ExamTemplate::with('classroom')->orderBy('title')->get();

        return view('admin.exams.questions.index', compact('questions', 'templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_template_id' => ['required', 'exists:exam_templates,id'],
            'question_text' => ['required', 'string'],
            'type' => ['required', 'in:multiple_choice,practical,theory'],
            'marks' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $position = ExamQuestion::where('exam_template_id', $request->exam_template_id)->max('position') ?? 0;

        ExamQuestion::create([
            'exam_template_id' => $request->exam_template_id,
            'question_text' => $request->question_text,
            'type' => $request->type,
            'marks' => $request->marks,
            'position' => $position + 1,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Official exam question created.');
    }

    public function destroy(ExamQuestion $question)
    {
        $question->delete();

        return redirect()->back()->with('success', 'Exam question deleted.');
    }
}
