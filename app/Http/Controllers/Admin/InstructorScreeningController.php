<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorScreening;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InstructorScreeningController extends Controller
{
    public function index(Request $request)
    {
        $screenings = $this->filteredQuery($request)->paginate(25)->withQueryString();

        $totals = [
            'all' => InstructorScreening::count(),
            'passed' => InstructorScreening::where('passed', true)->count(),
            'failed' => InstructorScreening::where('passed', false)->count(),
        ];

        return view('admin.instructor-screenings.index', compact('screenings', 'totals'));
    }

    public function show(InstructorScreening $screening)
    {
        $answers = collect($screening->answers_json ?? [])->values();

        return view('admin.instructor-screenings.show', compact('screening', 'answers'));
    }

    public function export(Request $request): StreamedResponse
    {
        $rows = $this->filteredQuery($request)->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'submitted_at',
                'name',
                'email',
                'phone',
                'location',
                'interview_mode',
                'score',
                'total_questions',
                'percentage',
                'result',
                'invitation_sent_at',
            ]);

            foreach ($rows as $row) {
                fputcsv($out, [
                    optional($row->submitted_at)->format('Y-m-d H:i:s'),
                    $row->name,
                    $row->email,
                    $row->phone,
                    $row->location,
                    $row->interview_mode,
                    $row->score,
                    $row->total_questions,
                    $row->percentage,
                    $row->passed ? 'passed' : 'failed',
                    optional($row->invitation_sent_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($out);
        }, 'instructor_screenings.csv');
    }

    private function filteredQuery(Request $request)
    {
        $query = InstructorScreening::query()->orderByDesc('submitted_at');

        if ($request->filled('status')) {
            if ($request->status === 'passed') {
                $query->where('passed', true);
            } elseif ($request->status === 'failed') {
                $query->where('passed', false);
            }
        }

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%");
            });
        }

        return $query;
    }
}
