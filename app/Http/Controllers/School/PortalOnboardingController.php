<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PortalOnboardingController extends Controller
{
    public function create(SchoolRequest $schoolRequest)
    {
        abort_unless($schoolRequest->status === 'approved', 403);

        return view('school.onboarding.register', compact('schoolRequest'));
    }

    public function store(Request $request, SchoolRequest $schoolRequest)
    {
        abort_unless($schoolRequest->status === 'approved', 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($schoolRequest, $data): void {
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

            $user = User::where('email', $data['email'])->lockForUpdate()->first();

            if ($user && $user->role !== 'school_admin') {
                throw ValidationException::withMessages([
                    'email' => 'This email already belongs to another account.',
                ]);
            }

            if ($user) {
                $user->update([
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'password' => Hash::make($data['password']),
                    'school_id' => $school->id,
                    'role' => 'school_admin',
                    'must_change_password' => false,
                    'status' => 'active',
                ]);
            } else {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => Hash::make($data['password']),
                    'school_id' => $school->id,
                    'role' => 'school_admin',
                    'must_change_password' => false,
                    'status' => 'active',
                ]);
            }

            $school->update([
                'contact_person' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => 'active',
            ]);

            $schoolRequest->update([
                'school_id' => $school->id,
                'portal_onboarded_at' => now(),
            ]);
        });

        return redirect()
            ->route('login')
            ->with('success', 'School portal account created. You can now sign in.');
    }
}
