<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Instructor Certificate</title>
</head>
@php
    // Toggle this when switching certificate to a dark theme.
    $isDarkBackground = false;
    $logoPath = $isDarkBackground
        ? public_path('images/logo/genchess logo-white.png')
        : public_path('images/certificates/genchess_logo.png');
@endphp
<body style="margin:0; padding:0; font-family: DejaVu Serif, Georgia, Times, serif; background:{{ $isDarkBackground ? '#0F172A' : '#FAF7F2' }};">
    <div style="position: relative; width: 100%; height: 100%; padding: 28px;">
        <img
            src="{{ public_path('images/certificates/certificate_watermark.png') }}"
            alt="Watermark"
            style="position:absolute; top:50%; left:50%; width:360px; margin-left:-180px; margin-top:-180px; opacity:0.06;"
        >

        <table style="width:100%; border:3px solid #183153; border-collapse:collapse; background:#FAF7F2; position:relative; z-index:2;">
            <tr>
                <td style="padding:28px 34px 20px 34px;">
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="width:22%; vertical-align:top;">
                                <img src="{{ $logoPath }}" alt="Genchess Logo" style="width:120px;">
                            </td>
                            <td style="width:56%; text-align:center; vertical-align:top;">
                                <div style="font-size:24px; font-weight:700; letter-spacing:0.7px; color:#10233F;">
                                    GENCHESS EDUCATIONAL SERVICES LTD
                                </div>
                                <div style="margin-top:5px; font-size:13px; color:#3F4E62;">RC: 2011943</div>
                            </td>
                            <td style="width:22%;"></td>
                        </tr>
                    </table>

                    <table style="width:100%; border-collapse:collapse; margin-top:34px;">
                        <tr>
                            <td style="text-align:center;">
                                <div style="font-size:40px; letter-spacing:2px; font-weight:700; color:#10233F;">
                                    CERTIFICATE OF COMPLETION
                                </div>
                                <div style="margin-top:18px; font-size:18px; color:#475569;">
                                    This is to certify that
                                </div>
                                <div style="margin-top:14px; font-size:46px; font-weight:700; color:#0F172A;">
                                    {{ $certificate->instructor_name }}
                                </div>
                                <div style="margin-top:18px; font-size:19px; color:#475569;">
                                    has successfully completed the
                                </div>
                                <div style="margin-top:12px; font-size:30px; font-weight:700; color:#10233F;">
                                    Genchess Certified Chess Instructor (GCCI)
                                </div>
                                <div style="margin-top:8px; font-size:14px; color:#64748B;">
                                    Genchess Certified Chess Instructor Program (GCCIP)
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table style="width:100%; border-collapse:collapse; margin-top:50px;">
                        <tr>
                            <td style="width:33%; vertical-align:bottom; font-size:12px; color:#1E293B;">
                                <div><strong>Certificate No:</strong> {{ $certificate->certificate_number }}</div>
                                @if($qrCodeDataUri)
                                    <div style="margin-top:8px;">
                                        <img src="{{ $qrCodeDataUri }}" alt="Verification QR" style="width:70px; height:70px;">
                                    </div>
                                @endif
                            </td>
                            <td style="width:34%; text-align:center; vertical-align:bottom; font-size:12px; color:#1E293B;">
                                <div><strong>Issued:</strong> {{ $certificate->issued_at?->format('F j, Y') }}</div>
                            </td>
                            <td style="width:33%; text-align:right; vertical-align:bottom;">
                                <img src="{{ public_path('images/certificates/director_signature.png') }}" alt="Director Signature" style="width:180px;">
                                <div style="border-top:1px solid #10233F; margin-top:5px; margin-left:95px;"></div>
                                <div style="font-size:12px; color:#1E293B; margin-top:4px;">Martins Damilola</div>
                                <div style="font-size:11px; color:#334155; margin-top:2px;">Founder/Lead Instructor</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
