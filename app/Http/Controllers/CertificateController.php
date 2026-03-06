<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    private const GCCI_PROGRAM_NAME = 'Genchess Certified Chess Instructor (GCCI)';

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'instructor_name' => ['required', 'string', 'max:255'],
        ]);

        $issuedAt = now();
        $year = $issuedAt->format('Y');
        $certificate = DB::transaction(function () use ($validated, $issuedAt, $year) {
            $certificateNumber = $this->nextCertificateNumber($year);

            return Certificate::create([
                'instructor_name' => $validated['instructor_name'],
                'program_name' => self::GCCI_PROGRAM_NAME,
                'certificate_number' => $certificateNumber,
                'issued_at' => $issuedAt,
                'pdf_path' => '',
            ]);
        });

        $verificationUrl = $this->verificationUrl($certificate->certificate_number);
        $qrCodeDataUri = $this->buildQrCodeDataUri($verificationUrl);

        $pdf = Pdf::loadView('certificates.instructor', [
            'certificate' => $certificate,
            'verificationUrl' => $verificationUrl,
            'qrCodeDataUri' => $qrCodeDataUri,
        ])->setPaper('a4', 'landscape');

        $fileName = 'certificate_' . $certificate->certificate_number . '.pdf';
        $pdfPath = 'certificates/' . $fileName;
        Storage::disk('public')->put($pdfPath, $pdf->output());

        $certificate->update(['pdf_path' => $pdfPath]);

        return Storage::disk('public')->download($pdfPath, $fileName);
    }

    public function verify(string $certificate_number)
    {
        $certificateNumber = strtoupper(trim($certificate_number));
        $certificate = Certificate::where('certificate_number', $certificateNumber)->first();

        return view('certificates.verify', [
            'certificate' => $certificate,
            'certificateNumber' => $certificateNumber,
        ]);
    }

    private function nextCertificateNumber(string $year): string
    {
        $prefix = 'GEN-INS-' . $year . '-';

        $latestNumber = Certificate::where('certificate_number', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderByDesc('certificate_number')
            ->value('certificate_number');

        $nextSequence = 1;
        if ($latestNumber) {
            $parts = explode('-', $latestNumber);
            $lastSequence = (int) end($parts);
            $nextSequence = $lastSequence + 1;
        }

        return $prefix . str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
    }

    private function buildQrCodeDataUri(string $url): ?string
    {
        if (!class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
            return null;
        }

        $qrCodePng = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(100)
            ->margin(1)
            ->generate($url);

        return 'data:image/png;base64,' . base64_encode($qrCodePng);
    }

    private function verificationUrl(string $certificateNumber): string
    {
        $baseUrl = rtrim(config('app.url', 'https://genchess.ng'), '/');

        return $baseUrl . '/verify/' . $certificateNumber;
    }
}
