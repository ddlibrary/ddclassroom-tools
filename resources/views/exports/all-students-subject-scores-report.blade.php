<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Students Scores Report</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .subject-header { writing-mode: vertical-rl; text-orientation: mixed; }
        .score-cell { text-align: center; }
    </style>
</head>
<body>
    <h1>{{ ucfirst($examType) }}</h1>

    <div style="margin-bottom: 20px; padding: 15px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
        <h2 style="margin-top: 0; margin-bottom: 15px; font-size: 18px; font-weight: bold;">Result Summary</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px; background-color: #d1fae5; color: #065f46; font-weight: bold; border: 1px solid #10b981; text-align: center;">
                    کامیاب (Passed): {{ $totalKamyab }}
                </td>
                <td style="padding: 8px; background-color: #fef3c7; color: #92400e; font-weight: bold; border: 1px solid #f59e0b; text-align: center;">
                    مشروط (Conditional): {{ $totalMashroot }}
                </td>
                <td style="padding: 8px; background-color: #fee2e2; color: #991b1b; font-weight: bold; border: 1px solid #ef4444; text-align: center;">
                    ناکام (Failed): {{ $totalNakam }}
                </td>
                <td style="padding: 8px; background-color: #dbeafe; color: #1e40af; font-weight: bold; border: 1px solid #3b82f6; text-align: center;">
                    Total: {{ $totalKamyab + $totalMashroot + $totalNakam }}
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Name</th>
                <th>Father Name</th>
                <th>English Name</th>
                <th>English Father Name</th>
                <th>Moodle ID</th>
                <th>Email</th>
                <th>Grade</th>
                <th>Result Status</th>
                @foreach($subjects as $subject)
                <th class="subject-header">
                    {{ $subject->en_name ?? $subject->name }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $result)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $result['student']->fa_name ?? $result['student']->name ?? '-' }}</td>
                <td>{{ $result['student']->fa_father_name ?? $result['student']->father_name ?? '-' }}</td>
                <td>{{ $result['student']->name ?? '-' }}</td>
                <td>{{ $result['student']->father_name ?? '-' }}</td>
                <td>{{ $result['student']->id_number ?? '-' }}</td>
                <td>{{ $result['student']->email ?? '-' }}</td>
                <td>{{ $result['student']->subGrade->full_name ?? '-' }}</td>
                <td>{{ $result['result_status'] ?? '-' }}</td>
                @foreach($result['subjects'] as $subjectScore)
                <td class="score-cell">
                    @if($subjectScore['score'])
                        @php
                            $score = $subjectScore['score']['total'];
                            $formattedScore = is_numeric($score) && floor($score) == $score ? (int)$score : $score;
                        @endphp
                        {{ $formattedScore }}
                    @else
                        -
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

