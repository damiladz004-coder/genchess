<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Result Sheet</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; padding: 24px; }
        .box { border: 1px solid #ccc; padding: 10px; margin-bottom: 12px; }
        .label { color: #555; font-size: 12px; }
        .value { font-size: 15px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body onload="window.print()">
    <h2>Genchess Student Result Sheet</h2>
    <div class="box">
        <div class="label">Student</div>
        <div class="value">{{ $result->student->first_name }} {{ $result->student->last_name }}</div>
    </div>
    <div class="box">
        <div class="label">Class / Term / Session</div>
        <div class="value">{{ $result->classroom->name ?? '-' }} / {{ $result->term }} / {{ $result->academic_session }}</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Component</th>
                <th>Score</th>
                <th>Max</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Test</td><td>{{ $result->test_score }}</td><td>{{ $result->test_max }}</td></tr>
            <tr><td>Practical</td><td>{{ $result->practical_score }}</td><td>{{ $result->practical_max }}</td></tr>
            <tr><td>Exam</td><td>{{ $result->exam_score }}</td><td>{{ $result->exam_max }}</td></tr>
        </tbody>
    </table>
    <div class="box">
        <div class="label">Final Percentage / Grade</div>
        <div class="value">{{ $result->final_percentage }}% / {{ $result->grade }}</div>
    </div>
    <div class="box">
        <div class="label">Instructor Comment</div>
        <div>{{ $result->instructor_comment ?: '-' }}</div>
    </div>
    <div class="box">
        <div class="label">System Feedback</div>
        <div>{{ $result->system_feedback ?: '-' }}</div>
    </div>
</body>
</html>
