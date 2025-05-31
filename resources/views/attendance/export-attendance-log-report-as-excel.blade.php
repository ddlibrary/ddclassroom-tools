<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table>
        <tr>
            <th style="text-align: right;border:1px solid #000 !important">نام</th>
            <th style="text-align: right;border:1px solid #000 !important">نام پدر </th>
            <th style="text-align: left;border:1px solid #000 !important">Name</th>
            <th style="text-align: left;border:1px solid #000 !important">Father Name</th>
            <th style="text-align: left;border:1px solid #000 !important">Email</th>
            <th style="text-align: center;border:1px solid #000 !important">Moodle ID</th>
            <th style="text-align: center;border:1px solid #000 !important">Grade</th>
            <th style="text-align: center;border:1px solid #000 !important">Phone</th>
            <th style="text-align: center;border:1px solid #000 !important">Current Location</th>
            <th style="text-align: center;border:1px solid #000 !important">Year</th>
            <th style="text-align: center;border:1px solid #000 !important">Month</th>
            <th style="text-align: center;border:1px solid #000 !important">Total Hours</th>
            <th style="text-align: center;border:1px solid #000 !important">Present</th>
            <th style="text-align: center;border:1px solid #000 !important">Absent</th>
            <th style="text-align: center;border:1px solid #000 !important">Late</th>
            <th style="text-align: center;border:1px solid #000 !important">Excused</th>
            <th style="text-align: center;border:1px solid #000 !important">Eligible for Top up?</th>
            <th style="text-align: center;border:1px solid #000 !important">Absent %</th>
        </tr>
        @foreach ($students as $student)
            <tr>
                <td style="text-align: right;border:1px solid #000 !important">{{ $student->fa_name }}</td>
                <td style="text-align: right;border:1px solid #000 !important">{{ $student->fa_father_name }}</td>
                <td style="text-align: left;border:1px solid #000 !important">{{ $student->name }}</td>
                <td style="text-align: left;border:1px solid #000 !important">{{ $student->father_name }}</td>
                <td style="text-align: left;border:1px solid #000 !important">{{ $student->email }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $student->id_number }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $student->subGrade->full_name }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $student->phone }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $student->province }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $year }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $month?->name }}</td>
                <td  style="text-align: center;border:1px solid #000 !important;color:{{ $student->attendance_logs_count <= 1 ? 'red' :'black'}};">{{ $student->attendance_logs_count }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $student->present_count }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $student->absent_count }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $student->late_count }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $student->excused_count }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">
                <?php
                    $absent = $$student->attendance_logs_count == 0 ? 100 : (isset($student->attendance_logs_count) && ($student->attendance_logs_count > 0)) ? ($student->absent_count * 100 / $student->attendance_logs_count) : 0;
                ?>
                {{ $absent >= 20 ? 'No' : 'Yes'}}
                </td>
                <td style="text-align: center;border:1px solid #000 !important;color:{{ $absent>= 20 ? 'red' :'black'}};">{{ ceil($absent) }}%</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
