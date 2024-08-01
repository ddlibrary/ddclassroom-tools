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
            <th style="text-align: left;border:1px solid #000 !important">Name</th>
            <th style="text-align: left;border:1px solid #000 !important">Father Name</th>
            <th style="text-align: left;border:1px solid #000 !important">Email</th>
            <th style="text-align: center;border:1px solid #000 !important">Moodle ID</th>
            <th style="text-align: center;border:1px solid #000 !important">Subject</th>
            <th style="text-align: center;border:1px solid #000 !important">Grade</th>
            <th style="text-align: center;border:1px solid #000 !important">Year</th>
            <th style="text-align: center;border:1px solid #000 !important">Month</th>
            <th style="text-align: center;border:1px solid #000 !important">Status</th>
            <th style="text-align: center;border:1px solid #000 !important">Date</th>
        </tr>
        @foreach ($logs as $log)
            <tr>
                <td style="text-align: left;border:1px solid #000 !important">{{ $log->student->fa_name }}</td>
                <td style="text-align: left;border:1px solid #000 !important">{{ $log->student->fa_father_name }}</td>
                <td style="text-align: left;border:1px solid #000 !important">{{ $log->student->email }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $log->student->id_number }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $log->subject->name }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $log->subGrade->full_name }}</td>

                <td  style="text-align: center;border:1px solid #000 !important">{{ $year }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $log->month?->name }}</td>
                <td  style="text-align: center;border:1px solid #000 !important;color:{{ $log->status == 'P' ? 'green' :'red'}};">{{ $log->status }}</td>
                <td  style="text-align: center;border:1px solid #000 !important">{{ $log->date }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
