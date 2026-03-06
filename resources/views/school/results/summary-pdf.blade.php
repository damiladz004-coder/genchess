<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Class Result Summary</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; margin: 20px; }
        h1 { font-size: 20px; margin: 0 0 8px 0; }
        .meta { font-size: 11px; color: #444; margin-bottom: 12px; }
        .stats { margin-bottom: 14px; }
        .stat { display: inline-block; margin-right: 20px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Genchess Class Result Summary</h1>
    <div class="meta">
        Filters:
        Class ID: {{ $filters['class_id'] ?: 'All' }} |
        Term: {{ $filters['term'] ?: 'All' }} |
        Session: {{ $filters['academic_session'] ?: 'All' }}
    </div>

    <div class="stats">
        <span class="stat"><strong>Records:</strong> {{ $analytics['count'] }}</span>
        <span class="stat"><strong>Average:</strong> {{ $analytics['average'] }}%</span>
        <span class="stat"><strong>A Grades:</strong> {{ $analytics['a_count'] }}</span>
        <span class="stat"><strong>F Grades:</strong> {{ $analytics['f_count'] }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Term</th>
                <th>Session</th>
                <th>Test</th>
                <th>Practical</th>
                <th>Exam</th>
                <th>Final %</th>
                <th>Grade</th>
                <th>Graded By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $result)
                <tr>
                    <td>{{ ($result->student->first_name ?? '') . ' ' . ($result->student->last_name ?? '') }}</td>
                    <td>{{ $result->classroom->name ?? '-' }}</td>
                    <td>{{ $result->term }}</td>
                    <td>{{ $result->academic_session }}</td>
                    <td>{{ $result->test_score }}/{{ $result->test_max }}</td>
                    <td>{{ $result->practical_score }}/{{ $result->practical_max }}</td>
                    <td>{{ $result->exam_score }}/{{ $result->exam_max }}</td>
                    <td>{{ $result->final_percentage }}</td>
                    <td>{{ $result->grade }}</td>
                    <td>{{ $result->grader->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No result records found for selected filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
