<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Response;

class CertificateController extends Controller
{
    public function show(Certification $certification)
    {
        $certification->load('enrollment.user', 'enrollment.cohort.course');

        return view('admin.training.certificate', compact('certification'));
    }

    public function download(Certification $certification)
    {
        $certification->load('enrollment.user', 'enrollment.cohort.course');

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return redirect()
                ->back()
                ->withErrors(['pdf' => 'PDF generator is not installed.']);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'admin.training.certificate-pdf',
            compact('certification')
        )->setPaper('a4', 'landscape');

        $filename = 'genchess-certificate-' . $certification->certificate_code . '.pdf';

        return $pdf->download($filename);
    }
}
