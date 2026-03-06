<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\ExamAssignment;
use App\Models\ExamAttempt;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\StudentResultAudit;
use App\Services\SchoolGradingService;
use Illuminate\Http\Request;

class StudentResultController extends Controller
{
    public function index(Request $request)
    {
        $instructor = auth()->user();
        $classIds = $instructor->teachingClasses()->pluck('classes.id');

        $results = StudentResult::with('student', 'classroom')
            ->where('school_id', $instructor->school_id)
            ->whereIn('class_id', $classIds)
            ->when($request->class_id, fn ($query) => $query->where('class_id', $request->class_id))
            ->when($request->term, fn ($query) => $query->where('term', $request->term))
            ->when($request->academic_session, fn ($query) => $query->where('academic_session', $request->academic_session))
            ->orderByDesc('updated_at')
            ->paginate(30);

        $classes = $instructor->teachingClasses()->orderBy('name')->get(['classes.id', 'classes.name']);

        return view('instructor.results.index', compact('results', 'classes'));
    }

    public function create()
    {
        $instructor = auth()->user();
        $classes = $instructor->teachingClasses()->orderBy('name')->get(['classes.id', 'classes.name']);
        $students = Student::whereIn('class_id', $classes->pluck('id'))
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'class_id']);

        return view('instructor.results.create', compact('classes', 'students'));
    }

    public function store(Request $request, SchoolGradingService $gradingService)
    {
        $validated = $this->validateResultInput($request);
        $instructor = auth()->user();

        $student = Student::where('id', $validated['student_id'])
            ->where('school_id', $instructor->school_id)
            ->where('class_id', $validated['class_id'])
            ->firstOrFail();

        $this->assertInstructorClassAccess((int) $validated['class_id']);
        $this->validateScoreBoundaries($validated);

        if ($validated['exam_mode'] === 'automatic') {
            [$examScore, $examMax] = $this->resolveAutomaticExamScore($student->id, $validated['class_id'], $validated['term'], $validated['academic_session']);
            $validated['exam_score'] = $examScore;
            $validated['exam_max'] = $examMax;
        }

        $calc = $this->calculateResult($validated, $gradingService, $instructor->school_id);

        $result = StudentResult::updateOrCreate(
            [
                'student_id' => $student->id,
                'term' => $validated['term'],
                'academic_session' => $validated['academic_session'],
            ],
            [
                'school_id' => $instructor->school_id,
                'class_id' => $validated['class_id'],
                'test_score' => $validated['test_score'],
                'test_max' => $validated['test_max'],
                'practical_score' => $validated['practical_score'],
                'practical_max' => $validated['practical_max'],
                'exam_score' => $validated['exam_score'],
                'exam_max' => $validated['exam_max'],
                'exam_mode' => $validated['exam_mode'],
                'final_percentage' => $calc['final_percentage'],
                'grade' => $calc['grade'],
                'instructor_comment' => $validated['instructor_comment'] ?? null,
                'system_feedback' => $calc['feedback'],
                'graded_by' => $instructor->id,
                'approved_at' => now(),
            ]
        );

        StudentResultAudit::create([
            'student_result_id' => $result->id,
            'action' => $result->wasRecentlyCreated ? 'graded' : 'updated',
            'changed_by' => $instructor->id,
            'before_values' => null,
            'after_values' => $result->toArray(),
            'changed_at' => now(),
        ]);

        return redirect()->route('instructor.results.index')->with('success', 'Student result saved.');
    }

    public function edit(StudentResult $result)
    {
        $this->authorizeResultAccess($result);
        $instructor = auth()->user();
        $classes = $instructor->teachingClasses()->orderBy('name')->get(['classes.id', 'classes.name']);
        $students = Student::whereIn('class_id', $classes->pluck('id'))
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'class_id']);

        return view('instructor.results.edit', compact('result', 'classes', 'students'));
    }

    public function update(Request $request, StudentResult $result, SchoolGradingService $gradingService)
    {
        $this->authorizeResultAccess($result);
        $validated = $this->validateResultInput($request);
        $this->assertInstructorClassAccess((int) $validated['class_id']);
        $this->validateScoreBoundaries($validated);

        $student = Student::where('id', $validated['student_id'])
            ->where('school_id', auth()->user()->school_id)
            ->where('class_id', $validated['class_id'])
            ->firstOrFail();

        if ($validated['exam_mode'] === 'automatic') {
            [$examScore, $examMax] = $this->resolveAutomaticExamScore($student->id, $validated['class_id'], $validated['term'], $validated['academic_session']);
            $validated['exam_score'] = $examScore;
            $validated['exam_max'] = $examMax;
        }

        $calc = $this->calculateResult($validated, $gradingService, auth()->user()->school_id);
        $before = $result->toArray();

        $result->update([
            'student_id' => $student->id,
            'class_id' => $validated['class_id'],
            'term' => $validated['term'],
            'academic_session' => $validated['academic_session'],
            'test_score' => $validated['test_score'],
            'test_max' => $validated['test_max'],
            'practical_score' => $validated['practical_score'],
            'practical_max' => $validated['practical_max'],
            'exam_score' => $validated['exam_score'],
            'exam_max' => $validated['exam_max'],
            'exam_mode' => $validated['exam_mode'],
            'final_percentage' => $calc['final_percentage'],
            'grade' => $calc['grade'],
            'instructor_comment' => $validated['instructor_comment'] ?? null,
            'system_feedback' => $calc['feedback'],
            'graded_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        StudentResultAudit::create([
            'student_result_id' => $result->id,
            'action' => 'modified',
            'changed_by' => auth()->id(),
            'before_values' => $before,
            'after_values' => $result->fresh()->toArray(),
            'changed_at' => now(),
        ]);

        return redirect()->route('instructor.results.index')->with('success', 'Student result updated.');
    }

    private function validateResultInput(Request $request): array
    {
        return $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'term' => ['required', 'string', 'max:30'],
            'academic_session' => ['required', 'string', 'max:20'],
            'test_score' => ['nullable', 'numeric', 'min:0'],
            'test_max' => ['nullable', 'numeric', 'min:0.01'],
            'practical_score' => ['nullable', 'numeric', 'min:0'],
            'practical_max' => ['nullable', 'numeric', 'min:0.01'],
            'exam_score' => ['nullable', 'numeric', 'min:0'],
            'exam_max' => ['nullable', 'numeric', 'min:0.01'],
            'exam_mode' => ['required', 'in:manual,automatic'],
            'instructor_comment' => ['nullable', 'string'],
        ]);
    }

    private function validateScoreBoundaries(array $validated): void
    {
        foreach (['test', 'practical', 'exam'] as $component) {
            $score = $validated[$component . '_score'] ?? null;
            $max = $validated[$component . '_max'] ?? null;
            if ($score !== null && $max !== null && (float) $score > (float) $max) {
                abort(422, ucfirst($component) . ' score cannot exceed max score.');
            }
        }
    }

    private function calculateResult(array $validated, SchoolGradingService $gradingService, ?int $schoolId): array
    {
        $testPct = $gradingService->calculateComponentPercentage($validated['test_score'] ?? 0, $validated['test_max'] ?? 0);
        $practicalPct = $gradingService->calculateComponentPercentage($validated['practical_score'] ?? 0, $validated['practical_max'] ?? 0);
        $examPct = $gradingService->calculateComponentPercentage($validated['exam_score'] ?? 0, $validated['exam_max'] ?? 0);

        $weights = $gradingService->defaultWeightsForSchool($schoolId);
        $final = $gradingService->calculateWeightedTotal([
            ['percentage' => $testPct, 'weight' => $weights['test'] ?? 25],
            ['percentage' => $practicalPct, 'weight' => $weights['practical'] ?? 25],
            ['percentage' => $examPct, 'weight' => $weights['exam'] ?? 50],
        ]);

        return [
            'final_percentage' => $final,
            'grade' => $gradingService->assignLetterGrade($final),
            'feedback' => $gradingService->generateFeedback([
                'test' => $testPct,
                'practical' => $practicalPct,
                'exam' => $examPct,
            ]),
        ];
    }

    private function resolveAutomaticExamScore(int $studentId, int $classId, string $term, string $session): array
    {
        $attempt = ExamAttempt::query()
            ->where('student_id', $studentId)
            ->whereHas('assignment', function ($query) use ($classId, $term, $session) {
                $query->where('class_id', $classId)
                    ->where('term', $term)
                    ->where('session', $session)
                    ->where('mode', 'online');
            })
            ->orderByDesc('submitted_at')
            ->first();

        return [$attempt?->score ?? 0, max((int) ($attempt?->total_marks ?? 0), 1)];
    }

    private function assertInstructorClassAccess(int $classId): void
    {
        $hasAccess = auth()->user()->teachingClasses()->where('classes.id', $classId)->exists();
        if (!$hasAccess) {
            abort(403, 'Only assigned instructors can grade this class.');
        }
    }

    private function authorizeResultAccess(StudentResult $result): void
    {
        if ((int) $result->school_id !== (int) auth()->user()->school_id) {
            abort(403);
        }

        $this->assertInstructorClassAccess((int) $result->class_id);
    }
}
