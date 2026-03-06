<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\ExamAssignment;
use App\Models\ExamAttempt;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamAssignmentController extends Controller
{
    public function index()
    {
        $instructor = auth()->user();
        $classIds = $instructor->teachingClasses()->pluck('classes.id');

        $assignments = ExamAssignment::where('school_id', $instructor->school_id)
            ->whereIn('class_id', $classIds)
            ->whereIn('mode', ['manual', 'offline'])
            ->with(['template', 'classroom'])
            ->orderByDesc('created_at')
            ->get();

        return view('instructor.exams.assignments.index', compact('assignments'));
    }

    public function grade(ExamAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        $students = Student::where('class_id', $assignment->class_id)->orderBy('first_name')->get();
        $attempts = ExamAttempt::where('exam_assignment_id', $assignment->id)->get()->keyBy('student_id');
        $totalMarks = (int) $assignment->template()->withSum('questions as total_marks', 'marks')->first()?->total_marks;

        return view('instructor.exams.assignments.grade', compact('assignment', 'students', 'attempts', 'totalMarks'));
    }

    public function storeGrades(Request $request, ExamAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        $request->validate([
            'scores' => 'required|array',
        ]);

        $totalMarks = (int) $assignment->template()->withSum('questions as total_marks', 'marks')->first()?->total_marks;
        $totalMarks = max($totalMarks, 1);

        foreach ($request->scores as $studentId => $score) {
            if ($score === null || $score === '') {
                continue;
            }

            $scoreValue = max(0, min((int) $score, $totalMarks));

            ExamAttempt::updateOrCreate(
                [
                    'exam_assignment_id' => $assignment->id,
                    'student_id' => $studentId,
                ],
                [
                    'score' => $scoreValue,
                    'total_marks' => $totalMarks,
                    'submitted_at' => now(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Manual scores saved.');
    }

    private function authorizeAssignment(ExamAssignment $assignment): void
    {
        $instructor = auth()->user();
        $allowed = $instructor->teachingClasses()->where('classes.id', $assignment->class_id)->exists();

        if ($assignment->school_id !== $instructor->school_id || !$allowed || !in_array($assignment->mode, ['manual', 'offline'], true)) {
            abort(403);
        }
    }
}
