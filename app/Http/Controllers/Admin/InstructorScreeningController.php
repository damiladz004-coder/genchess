<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InstructorInterviewScheduledMail;
use App\Mail\InstructorOnboardingApprovedMail;
use App\Models\InstructorScreening;
use App\Services\WhatsAppMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InstructorScreeningController extends Controller
{
    public function __construct(private readonly WhatsAppMessageService $whatsApp)
    {
    }

    public function index(Request $request)
    {
        $screenings = $this->filteredQuery($request)->paginate(25)->withQueryString();

        $totals = [
            'all' => InstructorScreening::count(),
            'passed' => InstructorScreening::where('passed', true)->count(),
            'failed' => InstructorScreening::where('passed', false)->count(),
            'approved' => InstructorScreening::where('final_status', 'approved')->count(),
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
            'stage_two_meeting_type' => ['nullable', 'string', 'max:50'],
            'stage_two_meeting_link' => ['nullable', 'url', 'max:1000'],
            'stage_two_meeting_id' => ['nullable', 'string', 'max:100'],
            'stage_two_passcode' => ['nullable', 'string', 'max:100'],
            'stage_two_meeting_date' => ['nullable', 'date'],
            'stage_two_meeting_time' => ['nullable', 'date_format:H:i'],
            'stage_three_meeting_type' => ['nullable', 'string', 'max:50'],
            'stage_three_meeting_link' => ['nullable', 'url', 'max:1000'],
            'stage_three_meeting_id' => ['nullable', 'string', 'max:100'],
            'stage_three_passcode' => ['nullable', 'string', 'max:100'],
            'stage_three_meeting_date' => ['nullable', 'date'],
            'stage_three_meeting_time' => ['nullable', 'date_format:H:i'],
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

        $this->maybeSendInterviewInvite($screening->fresh(), 'stage_two');
        $this->maybeSendInterviewInvite($screening->fresh(), 'stage_three');

        if ($screening->fresh()->final_status === 'approved') {
            $this->sendOnboardingLink($screening->fresh());
        }

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
                'preferred_interview_date',
                'preferred_interview_time',
                'score',
                'total_questions',
                'percentage',
                'result',
                'stage_two_status',
                'stage_three_status',
                'final_status',
                'onboarding_link_sent_at',
            ]);

            foreach ($rows as $row) {
                fputcsv($out, [
                    optional($row->submitted_at)->format('Y-m-d H:i:s'),
                    $row->name,
                    $row->email,
                    $row->phone,
                    $row->location,
                    $row->interview_mode,
                    optional($row->preferred_interview_date)->format('Y-m-d'),
                    $row->preferred_interview_time ? $row->preferred_interview_time->format('H:i') : null,
                    $row->score,
                    $row->total_questions,
                    $row->percentage,
                    $row->passed ? 'passed' : 'failed',
                    $row->stage_two_status,
                    $row->stage_three_status,
                    $row->final_status,
                    optional($row->onboarding_link_sent_at)->format('Y-m-d H:i:s'),
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
            } elseif ($request->status === 'approved') {
                $query->where('final_status', 'approved');
            }
        }

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%");
            });
        }

        return $query;
    }

    private function maybeSendInterviewInvite(InstructorScreening $screening, string $stageKey): void
    {
        $stage = $stageKey === 'stage_two' ? 'Stage 2' : 'Stage 3';
        $dateField = "{$stageKey}_meeting_date";
        $timeField = "{$stageKey}_meeting_time";
        $linkField = "{$stageKey}_meeting_link";
        $idField = "{$stageKey}_meeting_id";
        $sentField = "{$stageKey}_invitation_sent_at";
        $whatsAppField = "{$stageKey}_whatsapp_sent_at";

        $hasSchedule = $screening->{$dateField} && $screening->{$timeField}
            && ($screening->{$linkField} || $screening->{$idField});

        if (!$hasSchedule || $screening->{$sentField}) {
            return;
        }

        try {
            Mail::to($screening->email)->send(new InstructorInterviewScheduledMail($screening, $stage));
            $screening->forceFill([$sentField => now()])->saveQuietly();
        } catch (\Throwable $e) {
            Log::error('Failed to send instructor interview email.', [
                'screening_id' => $screening->id,
                'stage' => $stage,
                'error' => $e->getMessage(),
            ]);
        }

        $message = sprintf(
            'Genchess %s interview: %s %s. Link: %s. Meeting ID: %s. Passcode: %s',
            $stage,
            optional($screening->{$dateField})->format('Y-m-d'),
            $screening->{$timeField}?->format('H:i'),
            $screening->{$linkField} ?: 'N/A',
            $screening->{$idField} ?: 'N/A',
            $screening->{$stageKey.'_passcode'} ?: 'N/A'
        );

        if ($this->whatsApp->send($screening->phone, $message, [
            'screening_id' => $screening->id,
            'stage' => $stage,
        ])) {
            $screening->forceFill([$whatsAppField => now()])->saveQuietly();
        }
    }

    private function sendOnboardingLink(InstructorScreening $screening): void
    {
        if ($screening->onboarding_link_sent_at) {
            return;
        }

        $onboardingUrl = URL::signedRoute('instructor.screening.biodata.create', ['screening' => $screening->id]);

        try {
            Mail::to($screening->email)->send(new InstructorOnboardingApprovedMail($screening, $onboardingUrl));
            $screening->forceFill(['onboarding_link_sent_at' => now()])->saveQuietly();
        } catch (\Throwable $e) {
            Log::error('Failed to send instructor onboarding email.', [
                'screening_id' => $screening->id,
                'error' => $e->getMessage(),
            ]);
        }

        if ($this->whatsApp->send(
            $screening->phone,
            "Congratulations {$screening->name}. Complete your Genchess instructor onboarding here: {$onboardingUrl}",
            ['screening_id' => $screening->id, 'type' => 'instructor_onboarding']
        )) {
            $screening->forceFill(['onboarding_whatsapp_sent_at' => now()])->saveQuietly();
        }
    }
}
