<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $intended = $request->session()->pull('url.intended');
        if (is_string($intended) && str_starts_with($intended, '/')) {
            return redirect($intended);
        }

        return redirect($this->roleRedirectUrl($request));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function roleRedirectUrl(Request $request): string
    {
        $user = $request->user();
        $baseHost = parse_url(config('app.url'), PHP_URL_HOST) ?: 'genchess.ng';
        $scheme = $request->getScheme();

        if (!$user) {
            return route('dashboard', absolute: false);
        }

        if ($user->role === 'super_admin') {
            return $scheme.'://admin.'.$baseHost.'/dashboard';
        }

        if ($user->role === 'school_admin') {
            return $scheme.'://school.'.$baseHost.'/dashboard';
        }

        if ($user->role === 'instructor') {
            return $scheme.'://instructor.'.$baseHost.'/dashboard';
        }

        if ($user->role === 'training_student') {
            return $scheme.'://training.'.$baseHost.'/dashboard';
        }

        return route('dashboard', absolute: false);
    }
}
