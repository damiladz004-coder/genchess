<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        $exams = Exam::where('school_id', $schoolId)
            ->with('classroom')
            ->orderBy('exam_date', 'desc')
            ->get();

        return view('school.exams.index', compact('exams'));
    }

    public function create()
    {
        $school = auth()->user()->school;
        $classes = $school->classes()->orderBy('name')->get();
        $terms = $this->terms();
        $sessions = $this->sessions();

        return view('school.exams.create', compact('classes', 'terms', 'sessions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'title' => 'required|string|max:255',
            'term' => 'required|in:' . implode(',', $this->terms()),
            'session' => 'required|in:' . implode(',', $this->sessions()),
            'exam_date' => 'nullable|date',
            'total_marks' => 'required|integer|min:1|max:1000',
        ]);

        $classroom = Classroom::where('id', $request->class_id)
            ->where('school_id', auth()->user()->school_id)
            ->firstOrFail();

        Exam::create([
            'school_id' => auth()->user()->school_id,
            'class_id' => $classroom->id,
            'title' => $request->title,
            'term' => $request->term,
            'session' => $request->session,
            'exam_date' => $request->exam_date,
            'total_marks' => $request->total_marks,
        ]);

        return redirect()
            ->route('school.exams.index')
            ->with('success', 'Exam created successfully.');
    }

    public function show(Exam $exam)
    {
        $this->authorizeExam($exam);

        $students = Student::where('class_id', $exam->class_id)
            ->orderBy('first_name')
            ->get();

        $results = ExamResult::where('exam_id', $exam->id)
            ->get()
            ->keyBy('student_id');

        return view('school.exams.show', compact('exam', 'students', 'results'));
    }

    public function destroy(Exam $exam)
    {
        $this->authorizeExam($exam);

        $exam->delete();

        return redirect()
            ->route('school.exams.index')
            ->with('success', 'Exam deleted successfully.');
    }

    public function storeResults(Request $request, Exam $exam)
    {
        $this->authorizeExam($exam);

        $request->validate([
            'scores' => 'required|array',
        ]);

        foreach ($request->scores as $studentId => $score) {
            $scoreValue = $score !== null && $score !== '' ? (int) $score : null;
            $gradeValue = $request->grades[$studentId] ?? null;
            $remarksValue = $request->remarks[$studentId] ?? null;

            if ($scoreValue !== null && ($gradeValue === null || $gradeValue === '')) {
                $gradeValue = $this->gradeForScore($scoreValue, $exam->total_marks);
            }

            ExamResult::updateOrCreate(
                [
                    'exam_id' => $exam->id,
                    'student_id' => $studentId,
                ],
                [
                    'score' => $scoreValue,
                    'grade' => $gradeValue,
                    'remarks' => $remarksValue,
                ]
            );
        }

        return redirect()
            ->back()
            ->with('success', 'Results saved successfully.');
    }

    private function authorizeExam(Exam $exam): void
    {
        if ($exam->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized');
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

    private function gradeForScore(int $score, int $totalMarks): string
    {
        $percentage = $totalMarks > 0 ? ($score / $totalMarks) * 100 : 0;

        if ($percentage >= 70) {
            return 'A';
        }
        if ($percentage >= 60) {
            return 'B';
        }
        if ($percentage >= 50) {
            return 'C';
        }
        if ($percentage >= 45) {
            return 'D';
        }

        return 'F';
    }
}
