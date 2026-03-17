<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolRequest;
use App\Models\User;
use App\Services\SchoolOnboardingLinkService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PortalOnboardingController extends Controller
{
    public function __construct(private readonly SchoolOnboardingLinkService $onboardingLinkService)
    {
    }

    public function create(Request $request, SchoolRequest $schoolRequest, ?string $token = null)
    {
        abort_unless($schoolRequest->status === 'approved', 403);
        abort_unless($this->onboardingLinkService->isValid($schoolRequest, $token), 403);

        if ($schoolRequest->portal_onboarded_at) {
            return redirect()->route('login')->with('info', 'School portal account has already been created.');
        }

        return view('school.onboarding.register', compact('schoolRequest'));
    }

    public function store(Request $request, SchoolRequest $schoolRequest, ?string $token = null)
    {
        abort_unless($schoolRequest->status === 'approved', 403);
        abort_unless($this->onboardingLinkService->isValid($schoolRequest, $token), 403);

        $existingUser = User::where('email', $schoolRequest->email)->first();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($existingUser?->id),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($schoolRequest, $data): User {
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

            $user = User::where('email', $schoolRequest->email)->lockForUpdate()->first();

            if ($user && $user->role !== 'school_admin') {
                throw ValidationException::withMessages([
                    'username' => 'This email already belongs to another account type.',
                ]);
            }

            $payload = [
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $schoolRequest->email,
                'phone' => $schoolRequest->phone,
                'password' => Hash::make($data['password']),
                'school_id' => $school->id,
                'role' => 'school_admin',
                'must_change_password' => false,
                'status' => 'active',
                'email_verified_at' => now(),
            ];

            if ($user) {
                $user->update($payload);
            } else {
                $user = User::create($payload);
            }

            $school->update([
                'contact_person' => $data['name'],
                'email' => $schoolRequest->email,
                'phone' => $schoolRequest->phone,
                'status' => 'active',
            ]);

            $schoolRequest->update([
                'school_id' => $school->id,
                'portal_onboarded_at' => now(),
            ]);

            $this->onboardingLinkService->consume($schoolRequest);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->away($this->schoolDashboardUrl($request));
    }

    private function schoolDashboardUrl(Request $request): string
    {
        $baseHost = parse_url(config('app.url'), PHP_URL_HOST) ?: 'genchess.ng';
        $schoolHost = str_starts_with($baseHost, 'school.') ? $baseHost : 'school.'.$baseHost;

        return $request->getScheme().'://'.$schoolHost.'/dashboard';
    }
}
