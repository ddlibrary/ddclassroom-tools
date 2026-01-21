<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Subject Statistics Report</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Subject Statistics Report</h1>
    <div style="margin-bottom: 20px;">
        <p><strong>Total Passed:</strong> {{ $totalPassed }}</p>
        <p><strong>Total Failed:</strong> {{ $totalFailed }}</p>
        <p><strong>Total Students:</strong> {{ $totalStudents }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Subject Name</th>
                <th>Total Students</th>
                <th>Passed</th>
                <th>Failed</th>
                <th>Pass Rate (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $result)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $result->subject_name }}</td>
                <td>{{ $result->total_count }}</td>
                <td>{{ $result->passed_count }}</td>
                <td>{{ $result->failed_count }}</td>
                <td>
                    @if($result->total_count > 0)
                        {{ number_format(($result->passed_count / $result->total_count) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>



