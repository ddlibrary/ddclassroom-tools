<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Grade 9 Report</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Grade 9 Report</h1>
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Student Name</th>
                <th>Father Name</th>
                <th>Moodle ID</th>
                <th>Email</th>
                <th>Sub Grade</th>
                <th>Year</th>
                <th>Semester 1 Subjects</th>
                <th>Semester 1 Passed</th>
                <th>Semester 1 Failed</th>
                <th>Semester 2 Subjects</th>
                <th>Semester 2 Passed</th>
                <th>Semester 2 Failed</th>
                <th>Final Result</th>
                <th>Total Passed</th>
                <th>Total Failed</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $result)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $result['student']->fa_name ?? $result['student']->name ?? '-' }}</td>
                <td>{{ $result['student']->fa_father_name ?? $result['student']->father_name ?? '-' }}</td>
                <td>{{ $result['student']->id_number ?? '-' }}</td>
                <td>{{ $result['student']->email ?? '-' }}</td>
                <td>{{ $result['sub_grade']->full_name ?? '-' }}</td>
                <td>{{ $result['year'] }}</td>
                <td>
                    @foreach($result['semester1']['subjects'] as $subject)
                        {{ $subject['subject_name'] }}: {{ $subject['score'] }} {{ $subject['is_passed'] ? '✓' : '✗' }}<br>
                    @endforeach
                </td>
                <td>{{ $result['semester1']['passed_count'] }}</td>
                <td>{{ $result['semester1']['failed_count'] }}</td>
                <td>
                    @foreach($result['semester2']['subjects'] as $subject)
                        {{ $subject['subject_name'] }}: {{ $subject['score'] }} {{ $subject['is_passed'] ? '✓' : '✗' }}<br>
                    @endforeach
                </td>
                <td>{{ $result['semester2']['passed_count'] }}</td>
                <td>{{ $result['semester2']['failed_count'] }}</td>
                <td>{{ $result['final_result']['result_name'] }}</td>
                <td>{{ $result['final_result']['passed_subjects'] }}</td>
                <td>{{ $result['final_result']['failed_subjects'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>


