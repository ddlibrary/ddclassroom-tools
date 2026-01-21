<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Subject Scores Report</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Subject Scores Report</h1>
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Name</th>
                <th>Father Name</th>
                <th>Moodle ID</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Year</th>
                <th>Type</th>
                <th>Written</th>
                <th>Verbal</th>
                <th>Attendance</th>
                <th>Activity</th>
                <th>Homework</th>
                <th>Evaluation</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $score)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $score->student->fa_name ?? $score->student->name ?? '-' }}</td>
                <td>{{ $score->student->fa_father_name ?? $score->student->father_name ?? '-' }}</td>
                <td>{{ $score->student->id_number ?? '-' }}</td>
                <td>{{ $score->student->email ?? '-' }}</td>
                <td>{{ $score->subject->name ?? '-' }}</td>
                <td>{{ $score->subGrade->full_name ?? '-' }}</td>
                <td>{{ $score->year }}</td>
                <td>
                    @if($score->type == 1) Midterm
                    @elseif($score->type == 2) Final
                    @elseif($score->type == 3) Result
                    @else -
                    @endif
                </td>
                <td>{{ $score->written ?? '-' }}</td>
                <td>{{ $score->verbal ?? '-' }}</td>
                <td>{{ $score->attendance ?? '-' }}</td>
                <td>{{ $score->activity ?? '-' }}</td>
                <td>{{ $score->homework ?? '-' }}</td>
                <td>{{ $score->evaluation ?? '-' }}</td>
                <td>{{ $score->total ?? '-' }}</td>
                <td>{{ $score->is_passed ? 'Passed' : 'Failed' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>


