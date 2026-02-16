<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Genchess Certificate</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; margin: 0; }
        .page { padding: 40px; border: 4px solid #111827; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .brand { display: flex; align-items: center; gap: 10px; }
        .logo { width: 40px; height: 40px; border-radius: 999px; background: #111827; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; }
        .title { font-size: 22px; font-weight: 700; }
        .subtitle { font-size: 12px; color: #4b5563; }
        .center { text-align: center; margin-top: 40px; }
        .name { font-size: 28px; font-weight: 700; margin-top: 8px; }
        .course { font-size: 20px; font-weight: 600; margin-top: 8px; }
        .muted { color: #4b5563; font-size: 12px; }
        .footer { display: flex; justify-content: space-between; align-items: center; margin-top: 40px; }
        .seal { width: 70px; height: 70px; border: 4px solid #111827; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; }
        .line { border-top: 1px solid #111827; width: 160px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="brand">
                <div class="logo">G</div>
                <div>
                    <div class="title">Genchess Academy</div>
                    <div class="subtitle">Instructor Certification</div>
                </div>
            </div>
            <div class="subtitle">
                Certificate Code<br>
                <strong>{{ $certification->certificate_code }}</strong>
            </div>
        </div>

        <div class="center">
            <div>This certifies that</div>
            <div class="name">{{ $certification->enrollment->user->name ?? 'Instructor' }}</div>
            <div style="margin-top:12px;">has successfully completed the training program:</div>
            <div class="course">{{ $certification->enrollment->cohort->course->title ?? 'Course' }}</div>
            <div class="muted">Cohort: {{ $certification->enrollment->cohort->name ?? '-' }}</div>
        </div>

        <div class="footer">
            <div class="muted">
                Issued<br>
                <strong>{{ $certification->issued_at?->format('Y-m-d') ?? '-' }}</strong>
            </div>
            <div class="seal">SEAL</div>
            <div class="muted" style="text-align:right;">
                Director
                <div class="line"></div>
                <div>Authorized Signature</div>
            </div>
        </div>
    </div>
</body>
</html>
