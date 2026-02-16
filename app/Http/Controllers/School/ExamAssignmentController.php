<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\ExamAssignment;
use App\Models\ExamTemplate;
use App\Models\ExamAttempt;
use App\Models\ExamAttemptAnswer;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamAssignmentController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        $assignments = ExamAssignment::where('school_id', $schoolId)
            ->with(['template', 'classroom'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('school.exams.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $school = auth()->user()->school;
        $classes = $school->classes()->orderBy('name')->get();
        $templates = ExamTemplate::orderBy('title')->get();
        $terms = $this->terms();
        $sessions = $this->sessions();

        return view('school.exams.assignments.create', compact('classes', 'templates', 'terms', 'sessions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_template_id' => 'required|exists:exam_templates,id',
            'class_id' => 'required|exists:classes,id',
            'term' => 'required|in:' . implode(',', $this->terms()),
            'session' => 'required|in:' . implode(',', $this->sessions()),
            'mode' => 'required|in:online,offline,manual',
            'exam_date' => 'nullable|date',
        ]);

        $school = auth()->user()->school;
        if (!$school->classes()->where('id', $request->class_id)->exists()) {
            return redirect()->back()->with('error', 'Selected class does not belong to your school.');
        }

        ExamAssignment::create([
            'exam_template_id' => $request->exam_template_id,
            'school_id' => $school->id,
            'class_id' => $request->class_id,
            'term' => $request->term,
            'session' => $request->session,
            'mode' => $request->mode,
            'exam_date' => $request->exam_date,
            'status' => 'active',
        ]);

        return redirect()->route('school.exams.assignments.index')
            ->with('success', 'Exam assigned to class.');
    }

    public function take(ExamAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        if ($assignment->mode !== 'online') {
            return redirect()->back()->with('error', 'This assignment is not set to online mode.');
        }

        $students = Student::where('class_id', $assignment->class_id)
            ->orderBy('first_name')
            ->get();

        $assignment->load(['template.questions.options']);

        return view('school.exams.assignments.take', compact('assignment', 'students'));
    }

    public function results(ExamAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        $assignment->load(['template.questions', 'classroom']);

        $attempts = ExamAttempt::where('exam_assignment_id', $assignment->id)
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalMarks = $assignment->template->questions->sum('marks');
        $scores = $attempts->pluck('score');

        $summary = [
            'attempts' => $attempts->count(),
            'average' => $scores->count() ? round($scores->avg(), 2) : 0,
            'highest' => $scores->count() ? $scores->max() : 0,
            'lowest' => $scores->count() ? $scores->min() : 0,
            'total_marks' => $totalMarks,
        ];

        return view('school.exams.assignments.results', compact('assignment', 'attempts', 'summary'));
    }

    public function print(ExamAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        if ($assignment->mode === 'online') {
            return redirect()->back()->with('error', 'Printable sheet is only for offline/manual assignments.');
        }

        $assignment->load(['template.questions.options', 'classroom']);

        return view('school.exams.assignments.print', compact('assignment'));
    }

    public function submit(Request $request, ExamAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        if ($assignment->mode !== 'online') {
            return redirect()->back()->with('error', 'This assignment is not set to online mode.');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'answers' => 'required|array',
        ]);

        $student = Student::where('id', $request->student_id)
            ->where('school_id', auth()->user()->school_id)
            ->where('class_id', $assignment->class_id)
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Selected student is not in this class.');
        }

        $existingAttempt = ExamAttempt::where('exam_assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->whereNotNull('submitted_at')
            ->first();

        if ($existingAttempt) {
            return redirect()->back()->with('error', 'This student has already submitted this exam.');
        }

        $assignment->load(['template.questions.options']);

        $totalMarks = 0;
        $score = 0;

        $attempt = ExamAttempt::create([
            'exam_assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'score' => 0,
            'total_marks' => 0,
            'submitted_at' => now(),
        ]);

        foreach ($assignment->template->questions as $question) {
            $totalMarks += $question->marks;
            $selectedOptionId = $request->answers[$question->id] ?? null;
            $selectedOption = $question->options->firstWhere('id', $selectedOptionId);

            $isCorrect = $selectedOption ? (bool) $selectedOption->is_correct : false;
            $marksAwarded = $isCorrect ? $question->marks : 0;
            $score += $marksAwarded;

            ExamAttemptAnswer::create([
                'exam_attempt_id' => $attempt->id,
                'exam_question_id' => $question->id,
                'exam_question_option_id' => $selectedOption?->id,
                'is_correct' => $isCorrect,
                'marks_awarded' => $marksAwarded,
            ]);
        }

        $attempt->update([
            'score' => $score,
            'total_marks' => $totalMarks,
        ]);

        return redirect()->route('school.exams.assignments.index')
            ->with('success', 'Exam submitted successfully.');
    }

    private function authorizeAssignment(ExamAssignment $assignment): void
    {
        if ($assignment->school_id !== auth()->user()->school_id) {
            abort(403);
        }
    }

    private function terms(): array
    {
        return ['Term 1', 'Term 2', 'Term 3'];
    }

    private function sessions(): array
    {
        $now = now();
        $startYear = $now->month >= 9 ? $now->year : $now->year - 1;

        $sessions = [];
        for ($i = 0; $i < 4; $i++) {
            $sessions[] = ($startYear + $i) . '/' . ($startYear + $i + 1);
        }

        return $sessions;
    }
}
