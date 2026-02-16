<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exam Sheet - {{ $assignment->template->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #111; }
        .container { max-width: 900px; margin: 0 auto; padding: 24px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .meta { font-size: 12px; color: #444; }
        .title { font-size: 20px; font-weight: bold; }
        .box { border: 1px solid #ccc; padding: 10px; margin-bottom: 12px; }
        .question { margin-bottom: 12px; }
        .option { margin-left: 14px; }
        .muted { color: #666; }
        .line { border-bottom: 1px solid #ccc; margin-top: 6px; height: 14px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <div class="title">{{ $assignment->template->title }}</div>
                <div class="meta">
                    Class: {{ $assignment->classroom->name ?? 'N/A' }} · Term: {{ $assignment->term }} · Session: {{ $assignment->session }}
                </div>
            </div>
            <div class="meta">
                Date: {{ $assignment->exam_date ?? '__________' }}
            </div>
        </div>

        <div class="box">
            <strong>Student Name:</strong> ____________________________
            &nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Admission #:</strong> ____________________________
        </div>

        <div class="box">
            <strong>Instructions:</strong>
            <div class="muted">Answer all questions. Choose the best option.</div>
        </div>

        @foreach($assignment->template->questions as $q)
            <div class="question">
                <div>
                    <strong>{{ $loop->iteration }}.</strong>
                    {{ $q->question_text }}
                    <span class="muted">({{ $q->marks }} marks)</span>
                </div>
                @foreach($q->options as $opt)
                    <div class="option">
                        {{ chr(64 + $opt->position) }}. {{ $opt->option_text }}
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="no-print" style="margin-top:20px;">
            <button onclick="window.print()">Print</button>
        </div>
    </div>
</body>
</html>
