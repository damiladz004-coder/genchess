@extends('layouts.public')

@section('content')
<div style="max-width: 860px; margin: 0 auto; padding: 42px 18px 58px 18px;">
    <h1 style="margin:0; font-size:30px; color:#10233F;">Certificate Verification</h1>
    <p style="margin-top:12px; color:#475569; font-size:16px;">
        Verification reference: <strong>{{ $certificateNumber }}</strong>
    </p>

    @if($certificate)
        <div style="margin-top:24px; background:#F0FDF4; border:1px solid #BBF7D0; padding:20px;">
            <div style="font-size:20px; color:#166534; font-weight:700;">Valid Certificate</div>
            <table style="width:100%; border-collapse:collapse; margin-top:14px;">
                <tr>
                    <td style="padding:6px 0; width:220px; color:#0F172A; font-weight:700;">Instructor Name</td>
                    <td style="padding:6px 0; color:#334155;">{{ $certificate->instructor_name }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0; width:220px; color:#0F172A; font-weight:700;">Program Name</td>
                    <td style="padding:6px 0; color:#334155;">Genchess Certified Chess Instructor (GCCI)</td>
                </tr>
                <tr>
                    <td style="padding:6px 0; width:220px; color:#0F172A; font-weight:700;">Certificate Number</td>
                    <td style="padding:6px 0; color:#334155;">{{ $certificate->certificate_number }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0; width:220px; color:#0F172A; font-weight:700;">Issued Date</td>
                    <td style="padding:6px 0; color:#334155;">{{ $certificate->issued_at?->format('F j, Y') }}</td>
                </tr>
            </table>
        </div>
    @else
        <div style="margin-top:24px; background:#FEF2F2; border:1px solid #FECACA; padding:20px;">
            <div style="font-size:20px; color:#991B1B; font-weight:700;">Invalid Certificate</div>
            <p style="margin-top:10px; color:#7F1D1D; font-size:15px;">
                No certificate was found for this verification reference.
            </p>
        </div>
    @endif
</div>
@endsection
