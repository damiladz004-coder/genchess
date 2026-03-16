<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CommunityConsultationScheduledMail;
use App\Models\CommunityConsultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class CommunityConsultationAdminController extends Controller
{
    public function index()
    {
        $consultations = CommunityConsultation::query()
            ->latest()
            ->paginate(20);

        return view('admin.community-consultations.index', [
            'consultations' => $consultations,
            'applicantTypeLabels' => CommunityConsultation::applicantTypeLabels(),
            'purposeLabels' => CommunityConsultation::purposeLabels(),
            'meetingTypeLabels' => CommunityConsultation::meetingTypeLabels(),
        ]);
    }

    public function show(CommunityConsultation $communityConsultation)
    {
        return view('admin.community-consultations.show', [
            'communityConsultation' => $communityConsultation,
            'applicantTypeLabels' => CommunityConsultation::applicantTypeLabels(),
            'purposeLabels' => CommunityConsultation::purposeLabels(),
            'meetingTypeLabels' => CommunityConsultation::meetingTypeLabels(),
        ]);
    }

    public function schedule(Request $request, CommunityConsultation $communityConsultation)
    {
        $data = $request->validate([
            'meeting_type' => ['required', Rule::in(CommunityConsultation::MEETING_TYPES)],
            'meeting_link' => ['nullable', 'url', 'max:1000'],
            'meeting_id' => ['nullable', 'string', 'max:100'],
            'meeting_passcode' => ['nullable', 'string', 'max:100'],
            'meeting_location' => ['nullable', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date'],
            'status' => ['nullable', Rule::in([
                CommunityConsultation::STATUS_PENDING,
                CommunityConsultation::STATUS_SCHEDULED,
                CommunityConsultation::STATUS_COMPLETED,
            ])],
        ]);

        if (in_array($data['meeting_type'], ['zoom', 'google_meet'], true)) {
            $request->validate([
                'meeting_link' => ['required', 'url', 'max:1000'],
            ]);
            $data['meeting_location'] = null;
        }

        if ($data['meeting_type'] === 'physical') {
            $request->validate([
                'meeting_location' => ['required', 'string', 'max:255'],
            ]);
            $data['meeting_link'] = null;
            $data['meeting_id'] = null;
            $data['meeting_passcode'] = null;
        }

        $data['status'] = $data['status'] ?? CommunityConsultation::STATUS_SCHEDULED;

        $communityConsultation->update($data);

        try {
            Mail::to($communityConsultation->email)->send(
                new CommunityConsultationScheduledMail($communityConsultation->fresh())
            );

            $communityConsultation->forceFill([
                'invitation_sent_at' => now(),
                'status' => $data['status'],
            ])->saveQuietly();
        } catch (\Throwable $e) {
            Log::error('Failed to send community consultation invitation email.', [
                'community_consultation_id' => $communityConsultation->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('warning', 'Meeting saved, but the invitation email could not be sent right now.');
        }

        return redirect()
            ->back()
            ->with('success', 'Meeting scheduled and invitation email sent successfully.');
    }

    public function updateStatus(Request $request, CommunityConsultation $communityConsultation)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                CommunityConsultation::STATUS_PENDING,
                CommunityConsultation::STATUS_SCHEDULED,
                CommunityConsultation::STATUS_COMPLETED,
            ])],
        ]);

        $communityConsultation->update($data);

        return redirect()
            ->back()
            ->with('success', 'Consultation status updated successfully.');
    }
}
