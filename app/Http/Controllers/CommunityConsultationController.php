<?php

namespace App\Http\Controllers;

use App\Mail\CommunityConsultationConfirmationMail;
use App\Models\CommunityConsultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class CommunityConsultationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:255'],
            'applicant_type' => ['required', Rule::in(CommunityConsultation::APPLICANT_TYPES)],
            'purpose' => ['required', Rule::in(CommunityConsultation::PURPOSE_OPTIONS)],
            'meeting_type' => ['required', Rule::in(CommunityConsultation::MEETING_TYPES)],
            'preferred_date' => ['required', 'date'],
            'preferred_time' => ['required', 'date_format:H:i'],
            'message' => ['nullable', 'string'],
        ]);

        $data['status'] = CommunityConsultation::STATUS_PENDING;

        $consultation = CommunityConsultation::create($data);

        $warning = null;

        try {
            Mail::to($consultation->email)->send(new CommunityConsultationConfirmationMail($consultation));
            $consultation->forceFill(['confirmation_sent_at' => now()])->saveQuietly();
        } catch (\Throwable $e) {
            Log::error('Failed to send community consultation confirmation email.', [
                'community_consultation_id' => $consultation->id,
                'error' => $e->getMessage(),
            ]);

            $warning = 'Your request was saved, but confirmation email could not be sent right now.';
        }

        $redirect = redirect()
            ->back()
            ->with('success', 'Your consultation request has been received successfully.');

        if ($warning) {
            $redirect->with('warning', $warning);
        }

        return $redirect;
    }
}
