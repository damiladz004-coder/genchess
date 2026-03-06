<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TrainingReferralService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        return view('auth.register', [
            'intent' => $request->query('intent'),
            'referralCode' => $request->query('ref'),
            'couponCode' => $request->query('coupon'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, TrainingReferralService $trainingReferralService): RedirectResponse
    {
        $emailRule = app()->environment('testing') ? 'email:rfc' : 'email:rfc,dns';

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', $emailRule, 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'intent' => ['nullable', 'string', 'in:training'],
            'referral_code' => ['nullable', 'string', 'max:64'],
            'coupon_code' => ['nullable', 'string', 'max:64'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => ($data['intent'] ?? null) === 'training' ? 'instructor' : 'school_admin',
        ]);

        if (($data['intent'] ?? null) === 'training') {
            $trainingReferralService->attachReferral($user, $data['referral_code'] ?? null);
            if (!empty($data['referral_code'])) {
                $request->session()->put('training_referral_code', strtoupper(trim((string) $data['referral_code'])));
            }
            if (!empty($data['coupon_code'])) {
                $request->session()->put('training_coupon_code', strtoupper(trim((string) $data['coupon_code'])));
            }
        }

        event(new Registered($user));
        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error('Failed to send verification email after registration.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
