<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\ExamAssignment;
use App\Models\ExamTemplate;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $classIds = $classes->pluck('id');
        $templates = ExamTemplate::whereIn('class_id', $classIds)
            ->with('classroom')
            ->orderBy('title')
            ->get();
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
            'status' => 'required|in:draft,active,closed',
        ]);

        $school = auth()->user()->school;
        if (!$school->classes()->where('id', $request->class_id)->exists()) {
            return redirect()->back()->with('error', 'Selected class does not belong to your school.');
        }
        $template = ExamTemplate::findOrFail($request->exam_template_id);
        if ((int) $template->class_id !== (int) $request->class_id) {
            return redirect()->back()->withInput()->with('error', 'Selected template does not match the selected class.');
        }

        ExamAssignment::create([
            'exam_template_id' => $request->exam_template_id,
            'school_id' => $school->id,
            'class_id' => $request->class_id,
            'term' => $request->term,
            'session' => $request->session,
            'mode' => $request->mode,
            'exam_date' => $request->exam_date,
            'status' => $request->status,
            'exam_code' => $request->mode === 'online' ? $this->generateExamCode() : null,
            'result_comment' => $template->result_comment,
        ]);

        return redirect()->route('school.exams.assignments.index')
            ->with('success', 'Exam assigned to class.');
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

    public function updateStatus(Request $request, ExamAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        $request->validate([
            'status' => 'required|in:draft,active,closed',
        ]);

        $assignment->update([
            'status' => $request->status,
            'exam_code' => $assignment->mode === 'online'
                ? ($assignment->exam_code ?: $this->generateExamCode())
                : null,
        ]);

        return redirect()->back()->with('success', 'Assignment status updated.');
    }

    public function destroy(ExamAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        $hasAttempts = ExamAttempt::where('exam_assignment_id', $assignment->id)->exists();
        if ($hasAttempts) {
            return redirect()->back()->with('error', 'Cannot delete assignment because students have submitted attempts.');
        }

        $assignment->delete();

        return redirect()->route('school.exams.assignments.index')
            ->with('success', 'Exam assignment deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'assignment_ids' => 'required|array|min:1',
            'assignment_ids.*' => 'integer',
        ]);

        $schoolId = auth()->user()->school_id;
        $assignmentIds = array_values(array_unique($request->assignment_ids));

        $assignments = ExamAssignment::where('school_id', $schoolId)
            ->whereIn('id', $assignmentIds)
            ->get();

        if ($assignments->isEmpty()) {
            return redirect()->back()->with('error', 'No valid exam assignments selected.');
        }

        $blockedIds = ExamAttempt::whereIn('exam_assignment_id', $assignments->pluck('id'))
            ->distinct()
            ->pluck('exam_assignment_id')
            ->all();

        $deletable = $assignments->reject(function (ExamAssignment $assignment) use ($blockedIds) {
            return in_array($assignment->id, $blockedIds, true);
        });

        $deletedCount = 0;
        if ($deletable->isNotEmpty()) {
            $deletedCount = ExamAssignment::where('school_id', $schoolId)
                ->whereIn('id', $deletable->pluck('id'))
                ->delete();
        }

        $blockedCount = count($blockedIds);
        if ($deletedCount > 0 && $blockedCount > 0) {
            return redirect()->back()->with('success', "Deleted {$deletedCount} assignments. Skipped {$blockedCount} with submitted attempts.");
        }

        if ($deletedCount > 0) {
            return redirect()->back()->with('success', "Deleted {$deletedCount} assignments.");
        }

        return redirect()->back()->with('error', 'Selected assignments have submitted attempts and cannot be deleted.');
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

    private function generateExamCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (ExamAssignment::where('exam_code', $code)->exists());

        return $code;
    }
}
