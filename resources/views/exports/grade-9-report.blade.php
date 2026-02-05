<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Grade 9 Report</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        td.subject-score { text-align: center; }
    </style>
</head>
<body>
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
                @foreach($subjects ?? [] as $subject)
                    <th>{{ $subject['name'] }}</th>
                @endforeach
                <th>Final Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $result)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $result['student']->name ?? '-' }}</td>
                <td>{{ $result['student']->father_name ?? '-' }}</td>
                <td>{{ $result['student']->id_number ?? '-' }}</td>
                <td>{{ $result['student']->email ?? '-' }}</td>
                <td>{{ $result['sub_grade']->full_name ?? '-' }}</td>
                <td>{{ $result['year'] }}</td>
                @foreach($subjects ?? [] as $subject)
                    <td class="subject-score" @if(isset($result['all_subjects'][$subject['id']]) && ($result['all_subjects'][$subject['id']]['score'] ?? 0) < 50) style="color: red;" @endif>
                        @if(isset($result['all_subjects'][$subject['id']]))
                            {{ $result['all_subjects'][$subject['id']]['score'] ?? 0 }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
                <td>{{ $result['final_result']['result_name'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
