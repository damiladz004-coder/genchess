<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Mail\InstructorInvite;
use App\Models\InstructorProfile;
use App\Models\InstructorScreening;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InstructorOnboardingController extends Controller
{
    public function create(InstructorScreening $screening)
    {
        $this->ensureScreeningCanOnboard($screening);

        session()->put('instructor_screening.result_id', $screening->id);

        return view('instructor.onboarding.biodata', compact('screening'));
    }

    public function store(Request $request, InstructorScreening $screening)
    {
        $this->ensureScreeningCanOnboard($screening);

        $data = $request->validate([
            'passport_photo' => 'required|image|max:4096',
            'full_name' => 'required|string|max:255',
            'address' => 'required|string|max:2000',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'whatsapp_phone' => 'required|string|max:30',
            'short_biography' => 'required|string|max:2000',
            'areas_of_specialization' => 'required|string|max:1000',
        ]);

        $passportPath = $request->file('passport_photo')->store('instructor-passports', 'public');
        $temporaryPassword = null;
        $createdNewUser = false;
        $warning = null;

        DB::transaction(function () use (
            $screening,
            $data,
            $passportPath,
            &$temporaryPassword,
            &$createdNewUser
        ): void {
            $user = User::where('email', $data['email'])->lockForUpdate()->first();

            if ($user && !in_array($user->role, ['instructor', 'training_student'], true)) {
                throw ValidationException::withMessages([
                    'email' => 'This email belongs to a non-instructor account. Use another email.',
                ]);
            }

            if (!$user) {
                $temporaryPassword = Str::random(12);
                $user = User::create([
                    'name' => $data['full_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => Hash::make($temporaryPassword),
                    'role' => 'instructor',
                    'status' => 'active',
                    'must_change_password' => true,
                ]);
                $createdNewUser = true;
            } else {
                $user->update([
                    'name' => $data['full_name'],
                    'phone' => $data['phone'],
                    'role' => 'instructor',
                    'status' => $user->status ?: 'active',
                ]);
            }

            $existingProfile = InstructorProfile::query()
                ->where('screening_id', $screening->id)
                ->orWhere('user_id', $user->id)
                ->first();

            if ($existingProfile && $existingProfile->passport_photo_path !== $passportPath) {
                Storage::disk('public')->delete($existingProfile->passport_photo_path);
            }

            $profilePayload = [
                'screening_id' => $screening->id,
                'user_id' => $user->id,
                'passport_photo_path' => $passportPath,
                'full_name' => $data['full_name'],
                'address' => $data['address'],
                'location' => $data['location'],
                'city' => $data['city'],
                'state' => $data['state'],
                'country' => $data['country'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'whatsapp_phone' => $data['whatsapp_phone'],
                'short_biography' => $data['short_biography'],
                'areas_of_specialization' => $data['areas_of_specialization'],
            ];

            if ($existingProfile) {
                $existingProfile->update($profilePayload);
            } else {
                InstructorProfile::create($profilePayload);
            }

            $screening->update([
                'user_id' => $user->id,
                'final_status' => 'approved',
                'certified_at' => $screening->certified_at ?: now(),
                'approved_at' => $screening->approved_at ?: now(),
                'onboarded_at' => now(),
            ]);
        });

        if ($createdNewUser) {
            $user = User::where('email', $data['email'])->first();
            if ($user) {
                try {
                    Mail::to($user->email)->send(new InstructorInvite($user, (string) $temporaryPassword));
                } catch (\Throwable $e) {
                    Log::error('Failed to send onboarding instructor invite email.', [
                        'screening_id' => $screening->id,
                        'email' => $user->email,
                        'error' => $e->getMessage(),
                    ]);
                    $warning = 'Profile saved, but login invite email could not be sent right now.';
                }

                if (!$user->hasVerifiedEmail()) {
                    try {
                        $user->sendEmailVerificationNotification();
                    } catch (\Throwable $e) {
                        Log::error('Failed to send onboarding verification email.', [
                            'screening_id' => $screening->id,
                            'email' => $user->email,
                            'error' => $e->getMessage(),
                        ]);
                        $warning = trim(($warning ? $warning.' ' : '').'Verification email could not be sent.');
                    }
                }
            }
        }

        session()->put('instructor_screening.result_id', $screening->id);
        $redirect = redirect()
            ->route('instructor.screening.result')
            ->with('success', 'Biodata submitted successfully. Your instructor profile has been created.');

        if ($warning) {
            $redirect->with('warning', $warning);
        }

        return $redirect;
    }

    private function ensureScreeningCanOnboard(InstructorScreening $screening): void
    {
        if (!$screening->passed || $screening->final_status !== 'approved') {
            abort(403, 'This screening is not yet approved for instructor onboarding.');
        }
    }
}
