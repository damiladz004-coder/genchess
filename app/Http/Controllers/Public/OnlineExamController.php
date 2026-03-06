<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ExamAssignment;
use App\Models\ExamAttempt;
use App\Models\ExamAttemptAnswer;
use App\Models\Student;
use Illuminate\Http\Request;

class OnlineExamController extends Controller
{
    public function showCodeForm()
    {
        return view('public.exams.code');
    }

    public function showByCode(Request $request)
    {
        $request->validate([
            'exam_code' => 'required|string|max:30',
        ]);

        $examCode = strtoupper(trim($request->exam_code));
        $assignment = $this->findActiveOnlineAssignment($examCode);
        if (!$assignment) {
            return redirect()->route('public.exams.code')->withErrors([
                'exam_code' => 'Invalid exam code or exam is not active.',
            ])->withInput();
        }

        $assignment->load(['template.questions.options', 'classroom']);
        $students = Student::where('class_id', $assignment->class_id)->orderBy('first_name')->get();

        return view('public.exams.take', compact('assignment', 'students'));
    }

    public function showExam(string $examCode)
    {
        $assignment = $this->findActiveOnlineAssignment($examCode);
        if (!$assignment) {
            return redirect()->route('public.exams.code')->withErrors([
                'exam_code' => 'Invalid exam code or exam is not active.',
            ]);
        }

        $assignment->load(['template.questions.options', 'classroom']);
        $students = Student::where('class_id', $assignment->class_id)->orderBy('first_name')->get();

        return view('public.exams.take', compact('assignment', 'students'));
    }

    public function submit(Request $request, string $examCode)
    {
        $assignment = $this->findActiveOnlineAssignment($examCode);
        if (!$assignment) {
            abort(404);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'answers' => 'required|array',
        ]);

        $student = Student::where('id', $request->student_id)
            ->where('school_id', $assignment->school_id)
            ->where('class_id', $assignment->class_id)
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Selected student is not in this exam class.');
        }

        $existingAttempt = ExamAttempt::where('exam_assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->whereNotNull('submitted_at')
            ->first();

        if ($existingAttempt) {
            return redirect()->route('public.exams.result', [$assignment->exam_code, $existingAttempt->id]);
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

        return redirect()->route('public.exams.result', [$assignment->exam_code, $attempt->id]);
    }

    public function result(string $examCode, ExamAttempt $attempt)
    {
        $assignment = $this->findActiveOnlineAssignment($examCode);
        if (!$assignment || $attempt->exam_assignment_id !== $assignment->id) {
            abort(404);
        }

        $attempt->load('student');

        return view('public.exams.result', compact('assignment', 'attempt'));
    }

    private function findActiveOnlineAssignment(string $examCode): ?ExamAssignment
    {
        return ExamAssignment::where('exam_code', strtoupper(trim($examCode)))
            ->where('mode', 'online')
            ->where('status', 'active')
            ->first();
    }
}
