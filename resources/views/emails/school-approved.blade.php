<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Enrollment Approved</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f9f9f9; padding:20px;">
    <div style="background:#ffffff; padding:20px; max-width:600px; margin:auto; border-radius:6px;">
        <h2>🎉 Enrollment Approved</h2>

        <p>Dear {{ $schoolRequest->contact_person }},</p>

        <p>
            We’re excited to inform you that your school,
            <strong>{{ $schoolRequest->school_name }}</strong>,
            has been successfully approved to join
            <strong>genchess.ng</strong>.
        </p>

        @if(!empty($tempPassword))
            <p>
                Your school admin account has been created. Use the credentials below to log in:
            </p>
            <p>
                <strong>Email:</strong> {{ $schoolRequest->email }}<br>
                <strong>Temporary Password:</strong> {{ $tempPassword }}
            </p>
            <p>
                Please log in and change your password as soon as possible.
            </p>
        @else
            <p>
                Our team will contact you shortly with the next steps,
                including onboarding, class setup, and instructor assignment.
            </p>
        @endif

        <p>
            If you have any questions, feel free to reply to this email.
        </p>

        <p style="margin-top:30px;">
            Warm regards,<br>
            <strong>genchess.ng</strong><br>
            <em>Unlocking the Genius Within ♟️</em>
        </p>
    </div>
</body>
</html>

