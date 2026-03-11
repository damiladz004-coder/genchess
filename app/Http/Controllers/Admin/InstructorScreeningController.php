<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorScreening;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $screening->load('instructorProfile');
        $answers = collect($screening->answers_json ?? [])->values();

        return view('admin.instructor-screenings.show', compact('screening', 'answers'));
    }

    public function updateWorkflow(Request $request, InstructorScreening $screening)
    {
        $data = $request->validate([
            'stage_two_status' => ['required', Rule::in(['pending', 'passed', 'failed'])],
            'stage_three_status' => ['required', Rule::in(['pending', 'passed', 'failed'])],
            'final_status' => ['required', Rule::in(['pending', 'approved', 'recommended_training', 'rejected'])],
            'stage_two_notes' => ['nullable', 'string', 'max:2000'],
            'stage_three_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if (!$screening->passed) {
            $data['final_status'] = 'recommended_training';
        }

        if ($data['final_status'] === 'approved' && (!$screening->passed
                || $data['stage_two_status'] !== 'passed'
                || $data['stage_three_status'] !== 'passed')) {
            return redirect()
                ->back()
                ->withErrors(['final_status' => 'Both interview stages must be marked as passed before final approval.']);
        }

        $data['stage_two_interviewed_at'] = $data['stage_two_status'] === 'pending' ? null : now();
        $data['stage_three_interviewed_at'] = $data['stage_three_status'] === 'pending' ? null : now();

        if ($data['final_status'] === 'approved') {
            $data['approved_at'] = $screening->approved_at ?: now();
            $data['rejected_at'] = null;
            $data['training_recommended_at'] = null;
            $data['certified_at'] = $screening->certified_at ?: now();
        } elseif ($data['final_status'] === 'recommended_training') {
            $data['approved_at'] = null;
            $data['rejected_at'] = null;
            $data['training_recommended_at'] = $screening->training_recommended_at ?: now();
            $data['certified_at'] = null;
        } elseif ($data['final_status'] === 'rejected') {
            $data['approved_at'] = null;
            $data['rejected_at'] = $screening->rejected_at ?: now();
            $data['training_recommended_at'] = null;
            $data['certified_at'] = null;
        } else {
            $data['approved_at'] = null;
            $data['rejected_at'] = null;
            $data['training_recommended_at'] = null;
            $data['certified_at'] = null;
        }

        $screening->update($data);

        return redirect()->back()->with('success', 'Screening workflow updated.');
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
                'stage_two_status',
                'stage_three_status',
                'final_status',
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
                    $row->stage_two_status,
                    $row->stage_three_status,
                    $row->final_status,
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
