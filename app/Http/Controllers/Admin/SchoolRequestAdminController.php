<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CommunityHomeRequestApproved;
use App\Mail\SchoolEnrollmentApproved;
use App\Models\School;
use App\Models\SchoolRequest;
use App\Models\User;
use App\Services\ClassGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SchoolRequestAdminController extends Controller
{
    public function index()
    {
        $requests = SchoolRequest::latest()->get();
        return view('admin.enrollments.index', compact('requests'));
    }

    public function show(SchoolRequest $schoolRequest)
    {
        return view('admin.enrollments.show', compact('schoolRequest'));
    }

    public function approve(SchoolRequest $schoolRequest)
    {
        $missing = [];
        foreach (['school_type', 'class_system', 'city', 'state'] as $field) {
            if (empty($schoolRequest->{$field})) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            return redirect()->back()->with('error', 'Missing required fields: ' . implode(', ', $missing));
        }

        if ($schoolRequest->status === 'approved' && $schoolRequest->school_id) {
            return redirect()->back()->with('info', 'This enrollment request is already approved.');
        }

        $programType = strtolower((string) $schoolRequest->program_type);
        if (in_array($programType, ['community', 'home'], true)) {
            $schoolRequest->update([
                'status' => 'approved',
                'school_id' => null,
            ]);

            $warning = null;
            try {
                Mail::to($schoolRequest->email)->send(new CommunityHomeRequestApproved($schoolRequest));
            } catch (\Throwable $e) {
                Log::error('Failed to send community/home approval email.', [
                    'school_request_id' => $schoolRequest->id,
                    'email' => $schoolRequest->email,
                    'error' => $e->getMessage(),
                ]);
                $warning = 'Request approved, but follow-up email could not be sent.';
            }

            $redirect = redirect()->route('admin.enrollments.index')
                ->with('success', 'Request approved. Management will contact applicant for appointment and onboarding.');

            if ($warning) {
                $redirect->with('warning', $warning);
            }

            return $redirect;
        }

        $password = null;
        $schoolAdminUser = null;

        try {
            DB::transaction(function () use ($schoolRequest, &$password, &$schoolAdminUser) {
                $school = School::where('email', $schoolRequest->email)->first();

                if (!$school) {
                    $school = School::create([
                        'school_name' => $schoolRequest->school_name,
                        'school_type' => $schoolRequest->school_type,
                        'class_system' => $schoolRequest->class_system,
                        'address_line' => $schoolRequest->address_line,
                        'city' => $schoolRequest->city,
                        'state' => $schoolRequest->state,
                        'contact_person' => $schoolRequest->contact_person,
                        'email' => $schoolRequest->email,
                        'phone' => $schoolRequest->phone,
                        'status' => 'active',
                    ]);
                }

                $user = User::where('email', $schoolRequest->email)->first();
                if ($user && $user->role !== 'school_admin') {
                    throw new \RuntimeException('A user with this email already exists with a different role.');
                }

                if (!$user) {
                    $password = Str::random(10);
                    $user = User::create([
                        'name' => $schoolRequest->contact_person,
                        'email' => $schoolRequest->email,
                        'password' => Hash::make($password),
                        'role' => 'school_admin',
                        'school_id' => $school->id,
                        'must_change_password' => true,
                    ]);
                } else {
                    $user->update([
                        'school_id' => $school->id,
                    ]);
                }

                $schoolAdminUser = $user;

                ClassGenerator::generateForSchool($school);

                $schoolRequest->update([
                    'status' => 'approved',
                    'school_id' => $school->id,
                ]);
            });
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $warningMessages = [];
        try {
            Mail::to($schoolRequest->email)
                ->send(new SchoolEnrollmentApproved($schoolRequest, $password));
        } catch (\Throwable $e) {
            Log::error('Failed to send school enrollment approval email.', [
                'school_request_id' => $schoolRequest->id,
                'email' => $schoolRequest->email,
                'error' => $e->getMessage(),
            ]);
            $warningMessages[] = 'Enrollment was approved, but approval email could not be sent.';
        }

        if ($schoolAdminUser && !$schoolAdminUser->hasVerifiedEmail()) {
            try {
                $schoolAdminUser->sendEmailVerificationNotification();
            } catch (\Throwable $e) {
                Log::error('Failed to send school admin verification email.', [
                    'school_request_id' => $schoolRequest->id,
                    'user_id' => $schoolAdminUser->id,
                    'email' => $schoolAdminUser->email,
                    'error' => $e->getMessage(),
                ]);
                $warningMessages[] = 'Approval completed, but verification email could not be sent.';
            }
        }

        $redirect = redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment approved. School and School Admin created.');

        if (!empty($warningMessages)) {
            $redirect->with('warning', implode(' ', $warningMessages));
        }

        return $redirect;
    }
}
