<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CommunityConsultationScheduledMail;
use App\Mail\CommunityHomeRequestApproved;
use App\Mail\SchoolPortalAccessMail;
use App\Models\Payment;
use App\Models\School;
use App\Models\SchoolRequest;
use App\Services\ClassGenerator;
use App\Services\WhatsAppMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SchoolRequestAdminController extends Controller
{
    public function __construct(private readonly WhatsAppMessageService $whatsApp)
    {
    }

    public function index()
    {
        $requests = SchoolRequest::latest()->paginate(20);

        return view('admin.enrollments.index', compact('requests'));
    }

    public function show(SchoolRequest $schoolRequest)
    {
        return view('admin.enrollments.show', compact('schoolRequest'));
    }

    public function approve(SchoolRequest $schoolRequest)
    {
        $paymentPurpose = $schoolRequest->program_type === 'school'
            ? Payment::PURPOSE_SCHOOL
            : null;

        $requiredAmount = $paymentPurpose ? (int) config("paystack.fees.{$paymentPurpose}", 0) : 0;
        if ($paymentPurpose && $requiredAmount > 0) {
            $isPaid = Payment::query()
                ->where('purpose', $paymentPurpose)
                ->where('status', 'paid')
                ->where('metadata->school_request_id', $schoolRequest->id)
                ->exists();

            if (!$isPaid) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'payment' => 'This request requires payment confirmation before approval.',
                    ]);
            }
        }

        $programType = strtolower((string) $schoolRequest->program_type);
        if (in_array($programType, ['community', 'home'], true)) {
            $schoolRequest->update([
                'status' => 'approved',
                'school_id' => null,
            ]);

            try {
                Mail::to($schoolRequest->email)->send(new CommunityHomeRequestApproved($schoolRequest));
            } catch (\Throwable $e) {
                Log::error('Failed to send community/home approval email.', [
                    'school_request_id' => $schoolRequest->id,
                    'error' => $e->getMessage(),
                ]);
            }

            $this->whatsApp->send(
                $schoolRequest->phone,
                "Your Genchess {$programType} request has been approved. Our team will contact you with consultation details.",
                ['school_request_id' => $schoolRequest->id, 'type' => 'community_approval']
            );

            return redirect()->route('admin.enrollments.index')
                ->with('success', 'Request approved. Consultation can now be scheduled from the request details page.');
        }

        $school = School::firstOrCreate(
            ['email' => $schoolRequest->email],
            [
                'school_name' => $schoolRequest->school_name,
                'school_type' => $schoolRequest->school_type ?? 'private',
                'class_system' => $schoolRequest->class_system ?? 'primary_jss_ss',
                'address_line' => $schoolRequest->address_line,
                'city' => $schoolRequest->city ?? 'Lagos',
                'state' => $schoolRequest->state ?? 'Lagos',
                'contact_person' => $schoolRequest->contact_person,
                'phone' => $schoolRequest->phone,
                'status' => 'active',
            ]
        );

        ClassGenerator::generateForSchool($school);

        $schoolRequest->update([
            'status' => 'approved',
            'school_id' => $school->id,
        ]);

        $onboardingUrl = URL::signedRoute('school.portal.onboarding.create', ['schoolRequest' => $schoolRequest->id]);

        try {
            Mail::to($schoolRequest->email)->send(new SchoolPortalAccessMail($schoolRequest->fresh(), $onboardingUrl));
            $schoolRequest->forceFill(['portal_link_sent_at' => now()])->saveQuietly();
        } catch (\Throwable $e) {
            Log::error('Failed to send school portal onboarding email.', [
                'school_request_id' => $schoolRequest->id,
                'error' => $e->getMessage(),
            ]);
        }

        if ($this->whatsApp->send(
            $schoolRequest->phone,
            "Your Genchess school portal access link is ready: {$onboardingUrl}",
            ['school_request_id' => $schoolRequest->id, 'type' => 'school_portal']
        )) {
            $schoolRequest->forceFill(['portal_whatsapp_sent_at' => now()])->saveQuietly();
        }

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'School approved. Portal access link sent to email and WhatsApp.');
    }

    public function scheduleConsultation(Request $request, SchoolRequest $schoolRequest)
    {
        $data = $request->validate([
            'meeting_type' => ['required', 'string', 'max:50'],
            'meeting_date' => ['required', 'date'],
            'meeting_time' => ['required', 'date_format:H:i'],
            'consultation_link' => ['nullable', 'url', 'max:1000'],
            'consultation_meeting_id' => ['nullable', 'string', 'max:100'],
            'consultation_passcode' => ['nullable', 'string', 'max:100'],
        ]);

        $schoolRequest->update($data);

        try {
            Mail::to($schoolRequest->email)->send(new CommunityConsultationScheduledMail($schoolRequest->fresh()));
            $schoolRequest->forceFill(['consultation_invitation_sent_at' => now()])->saveQuietly();
        } catch (\Throwable $e) {
            Log::error('Failed to send consultation schedule email.', [
                'school_request_id' => $schoolRequest->id,
                'error' => $e->getMessage(),
            ]);
        }

        $message = sprintf(
            'Genchess consultation scheduled for %s at %s. Link: %s. Meeting ID: %s. Passcode: %s',
            $schoolRequest->meeting_date?->format('Y-m-d'),
            $schoolRequest->meeting_time?->format('H:i'),
            $schoolRequest->consultation_link ?: 'N/A',
            $schoolRequest->consultation_meeting_id ?: 'N/A',
            $schoolRequest->consultation_passcode ?: 'N/A'
        );

        if ($this->whatsApp->send($schoolRequest->phone, $message, [
            'school_request_id' => $schoolRequest->id,
            'type' => 'consultation_schedule',
        ])) {
            $schoolRequest->forceFill(['consultation_whatsapp_sent_at' => now()])->saveQuietly();
        }

        return redirect()->back()->with('success', 'Consultation scheduled and invitation sent.');
    }
}
