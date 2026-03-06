<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\StudentResult;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StudentResultController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $query = StudentResult::with('student', 'classroom', 'grader')
            ->where('school_id', $schoolId)
            ->when($request->class_id, fn ($q) => $q->where('class_id', $request->class_id))
            ->when($request->term, fn ($q) => $q->where('term', $request->term))
            ->when($request->academic_session, fn ($q) => $q->where('academic_session', $request->academic_session))
            ->orderByDesc('updated_at');

        $results = $query->paginate(30);
        $classes = auth()->user()->school->classes()->orderBy('name')->get(['id', 'name']);

        $analytics = [
            'count' => (clone $query)->count(),
            'average' => round((float) ((clone $query)->avg('final_percentage') ?? 0), 2),
            'a_count' => (clone $query)->where('grade', 'A')->count(),
            'f_count' => (clone $query)->where('grade', 'F')->count(),
        ];

        return view('school.results.index', compact('results', 'classes', 'analytics'));
    }

    public function show(StudentResult $result)
    {
        $this->authorizeSchoolResult($result);
        $result->load('student', 'classroom', 'grader', 'audits');

        return view('school.results.show', compact('result'));
    }

    public function print(StudentResult $result)
    {
        $this->authorizeSchoolResult($result);
        $result->load('student', 'classroom', 'grader');

        return view('school.results.print', compact('result'));
    }

    public function export(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $results = $this->filteredResultsQuery($request, $schoolId)
            ->orderBy('class_id')
            ->orderBy('student_id')
            ->get();

        $filename = 'school_results_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($results) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'student_name',
                'class',
                'term',
                'academic_session',
                'test_score',
                'test_max',
                'practical_score',
                'practical_max',
                'exam_score',
                'exam_max',
                'exam_mode',
                'final_percentage',
                'grade',
                'instructor_comment',
                'system_feedback',
                'graded_by',
                'approved_at',
            ]);

            foreach ($results as $result) {
                fputcsv($out, [
                    trim(($result->student->first_name ?? '') . ' ' . ($result->student->last_name ?? '')),
                    $result->classroom->name ?? '',
                    $result->term,
                    $result->academic_session,
                    $result->test_score,
                    $result->test_max,
                    $result->practical_score,
                    $result->practical_max,
                    $result->exam_score,
                    $result->exam_max,
                    $result->exam_mode,
                    $result->final_percentage,
                    $result->grade,
                    $result->instructor_comment,
                    $result->system_feedback,
                    $result->grader->name ?? '',
                    optional($result->approved_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($out);
        }, $filename);
    }

    public function summaryPdf(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $results = $this->filteredResultsQuery($request, $schoolId)
            ->orderBy('class_id')
            ->orderBy('student_id')
            ->get();

        $analytics = [
            'count' => $results->count(),
            'average' => round((float) ($results->avg('final_percentage') ?? 0), 2),
            'a_count' => $results->where('grade', 'A')->count(),
            'f_count' => $results->where('grade', 'F')->count(),
        ];

        $pdf = Pdf::loadView('school.results.summary-pdf', [
            'results' => $results,
            'analytics' => $analytics,
            'filters' => [
                'class_id' => $request->class_id,
                'term' => $request->term,
                'academic_session' => $request->academic_session,
            ],
        ])->setPaper('a4', 'landscape');

        $filename = 'result_summary_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    private function authorizeSchoolResult(StudentResult $result): void
    {
        if ((int) $result->school_id !== (int) auth()->user()->school_id) {
            abort(403);
        }
    }

    private function filteredResultsQuery(Request $request, int $schoolId)
    {
        return StudentResult::with('student', 'classroom', 'grader')
            ->where('school_id', $schoolId)
            ->when($request->class_id, fn ($q) => $q->where('class_id', $request->class_id))
            ->when($request->term, fn ($q) => $q->where('term', $request->term))
            ->when($request->academic_session, fn ($q) => $q->where('academic_session', $request->academic_session));
    }
}
