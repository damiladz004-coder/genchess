<?php

namespace App\Http\Controllers;

use App\Mail\SchoolRequestReceived;
use App\Models\Payment;
use App\Models\SchoolRequest;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class SchoolRequestController extends Controller
{
    public function store(Request $request, PaymentService $paymentService)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'program_type' => 'required|in:school,community,home',
            'school_type' => 'required|in:private,public',
            'class_system' => 'required|in:primary_jss_ss,grade_1_12,year_1_12',
            'address_line' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => ['required', 'string', 'max:100', Rule::in(config('nigeria.states', []))],
            'student_count' => 'nullable|integer|min:1',
            'message' => 'nullable|string',
            'applicant_type' => 'nullable|in:parent_home,community_estate,church,ngo,youth_org,school_non_formal',
            'session_type' => 'nullable|in:offline,online,hybrid',
            'physical_location' => 'nullable|string|max:255',
            'children_count' => 'nullable|string|max:10',
            'children_ages' => 'nullable|string|max:255',
            'chess_level' => 'nullable|in:beginner,intermediate,advanced,no_experience',
            'preferred_schedule' => 'nullable|in:weekdays,weekends,flexible',
            'parent_preferred_time' => 'nullable|date_format:H:i',
            'organization_name' => 'nullable|string|max:255',
            'participants_estimate' => 'nullable|in:5-10,10-20,20-50,50+',
            'age_group' => 'nullable|in:children,teenagers,adults,mixed',
            'org_program_type' => 'nullable|in:weekly_classes,weekend_program,holiday_bootcamp,tournament_only,long_term_partnership',
            'consultation_needed' => 'nullable|in:yes,no',
            'meeting_type' => 'nullable|in:physical,virtual',
            'meeting_date' => 'nullable|date',
            'meeting_time' => 'nullable|date_format:H:i',
            'consent' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'school_name',
            'contact_person',
            'email',
            'phone',
            'program_type',
            'student_count',
            'message',
            'school_type',
            'class_system',
            'address_line',
            'city',
            'state',
            'applicant_type',
            'session_type',
            'physical_location',
            'children_count',
            'children_ages',
            'chess_level',
            'preferred_schedule',
            'parent_preferred_time',
            'organization_name',
            'participants_estimate',
            'age_group',
            'org_program_type',
            'meeting_type',
            'meeting_date',
            'meeting_time',
        ]);

        $data['consultation_needed'] = $request->consultation_needed === null
            ? null
            : $request->consultation_needed === 'yes';
        $data['consent'] = (bool) $request->boolean('consent');

        $schoolRequest = SchoolRequest::create($data);

        $warning = null;
        try {
            Mail::to($data['email'])->send(new SchoolRequestReceived($data));
        } catch (\Throwable $e) {
            Log::error('Failed to send school request acknowledgement email.', [
                'email' => $data['email'] ?? null,
                'error' => $e->getMessage(),
            ]);
            $warning = 'Your request was saved, but confirmation email could not be sent right now.';
        }

        $paymentPurpose = $schoolRequest->program_type === 'school'
            ? Payment::PURPOSE_SCHOOL
            : null;

        $amount = $paymentPurpose ? (int) config("paystack.fees.{$paymentPurpose}", 0) : 0;

        if ($paymentPurpose && $amount > 0) {
            $payment = $paymentService->createPendingPayment(
                $request->user(),
                $schoolRequest->email,
                $amount,
                $paymentPurpose,
                [
                    'school_request_id' => $schoolRequest->id,
                    'program_type' => $schoolRequest->program_type,
                ]
            );

            $response = $paymentService->initialize(
                $payment,
                route('payments.callback', ['reference' => $payment->reference])
            );

            if (($response['status'] ?? false) && ($response['data']['authorization_url'] ?? null)) {
                return redirect()->away($response['data']['authorization_url']);
            }

            $paymentService->markFailed($payment, $response);
            Log::warning('School request payment initialization failed.', [
                'school_request_id' => $schoolRequest->id,
                'payment_id' => $payment->id,
                'response' => $response,
            ]);
            $warning = 'Your request was saved, but payment could not be initialized right now.';
        }

        $redirect = redirect()->back()->with('success', 'Your enrollment request has been submitted successfully!');
        if ($warning) {
            $redirect->with('warning', $warning);
        }

        return $redirect;
    }
}
