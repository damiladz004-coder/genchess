<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family:Arial, Helvetica, sans-serif; color:#0f172a;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6; padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px; background:#ffffff; border-radius:12px; overflow:hidden;">
                <tr>
                    <td style="background:#111827; padding:24px; text-align:center;">
                        <img src="{{ url('/images/logo/genchess-logo.png') }}" alt="Genchess logo" style="height:52px; width:auto; display:inline-block;">
                        <p style="margin:10px 0 0; color:#e5e7eb; font-size:14px;">Genchess Educational Services Ltd</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px 28px;">
                        <h1 style="margin:0 0 16px; font-size:24px; color:#111827;">Verify Your Email Address</h1>
                        <p style="margin:0 0 16px; font-size:15px;">Hello {{ $user->name }}</p>
                        <p style="margin:0 0 16px; font-size:15px; line-height:1.7;">
                            Welcome to Genchess - Nigeria's platform for structured chess education for schools, homes and communities.
                        </p>
                        <p style="margin:0 0 24px; font-size:15px;">Please click the button below to verify your email address.</p>
                        <p style="margin:0 0 24px;">
                            <a href="{{ $verificationUrl }}" style="display:inline-block; background:#0ea5e9; color:#ffffff; text-decoration:none; padding:12px 22px; border-radius:8px; font-weight:700;">
                                Verify Email Address
                            </a>
                        </p>
                        <p style="margin:0; font-size:13px; color:#64748b; line-height:1.6;">
                            If you did not create an account, no further action is required.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="background:#f8fafc; border-top:1px solid #e2e8f0; padding:18px 28px; text-align:center;">
                        <p style="margin:0; font-size:12px; color:#475569;">Genchess Educational Services Ltd</p>
                        <p style="margin:6px 0 0; font-size:12px; color:#475569;">Chess Education for Schools and Communities</p>
                        <p style="margin:6px 0 0; font-size:12px;">
                            <a href="https://genchess.ng" style="color:#0ea5e9; text-decoration:none;">https://genchess.ng</a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

