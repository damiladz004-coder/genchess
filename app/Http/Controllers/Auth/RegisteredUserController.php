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
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, TrainingReferralService $trainingReferralService): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'intent' => ['nullable', 'string', 'in:training'],
            'referral_code' => ['nullable', 'string', 'max:64'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => ($data['intent'] ?? null) === 'training' ? 'instructor' : 'school_admin',
        ]);

        if (($data['intent'] ?? null) === 'training') {
            $trainingReferralService->attachReferral($user, $data['referral_code'] ?? null);
        }

        event(new Registered($user));

        Auth::login($user);

        if (($data['intent'] ?? null) === 'training') {
            return redirect()->route('training.checkout');
        }

        return redirect(route('dashboard', absolute: false));
    }
}
