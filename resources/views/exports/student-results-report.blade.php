<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Results Report</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Student Results Report</h1>
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Name</th>
                <th>Father Name</th>
                <th>Moodle ID</th>
                <th>Email</th>
                <th>نام</th>
                <th>نام پدر</th>
                <th>Grade</th>
                <th>Year</th>
                <th>Total Score</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $result)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $result->student->name ?? '-' }}</td>
                <td>{{ $result->student->father_name ?? '-' }}</td>
                <td>{{ $result->student->id_number ?? '-' }}</td>
                <td>{{ $result->student->email ?? '-' }}</td>
                <td>{{ $result->student->fa_name ?? '-' }}</td>
                <td>{{ $result->student->fa_father_name ?? '-' }}</td>
                <td>{{ $result->subGrade->full_name ?? '-' }}</td>
                <td>{{ $result->year }}</td>
                <td>{{ $result->total ?? 0 }}</td>
                <td>{{ $result->result_name ?? $result->middle_result_name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>


