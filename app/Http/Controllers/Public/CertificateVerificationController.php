<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    public function index()
    {
        return view('public.certificate-verify');
    }

    public function show(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($request->code));

        $certification = Certification::where('certificate_code', $code)
            ->with('enrollment.user', 'enrollment.cohort.course')
            ->first();

        return view('public.certificate-verify', compact('certification', 'code'));
    }
}
