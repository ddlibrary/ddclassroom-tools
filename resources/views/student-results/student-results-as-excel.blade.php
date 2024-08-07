<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            padding: 0px;
            margin: 0px;
        }

        .font-small {
            font-size: 11px !important;
        }
    </style>

</head>

<body>

    <div style="width: 8.5in">

        <table style="width: 100%">

            <tr>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">NO</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Name</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Father</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">نام</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">نام پدر</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Moodle ID</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Grade</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Year</th>
                <th style="text-align: left;border:1px solid #000 !important;text-weight:bold;">Midterm Score</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Final score</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Total Score</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Midterm Percentage</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Final Percentage</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Percentage</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Midterm Result</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Final Result</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Result</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Midterm State</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Final State</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">State</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold;">Teacher</th>
            </tr>
            @foreach ($results as $result)
                <tr>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $loop->iteration }}</td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->student->name }}</td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->student->father_name }}
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->student->fa_name }}
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->student->fa_father_name }}
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->student->id_number }}</td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->subGrade->grade_id }} {{ $result
                    ->subGrade->level }}</td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->year }}</td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->middle }}</td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->final }}</td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->total }}</td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->middle_percentage }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->final_percentage }}</td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->percentage }}</td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->middleResult->name }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->finalResult->name }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->result->name }}</td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->middle_result_name }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->final_result_name }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">{{ $result->result_name }}</td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $result->subGrade?->responsible?->teacher?->name }}</td>

                </tr>
            @endforeach
        </table>
        {{-- <table>
            <tr>
                <th>DDC Midterm Exam Result Summary-2024</th>
                <th></th>
            </tr>
            @foreach ($resultCategories as $resultCategory)

            <tr>
                <td>
                    Students who scored between {{$resultCategory->from}}% & {{$resultCategory->to}}% ({{$resultCategory->name}})
                </td>
                <td>{{ $resultCategory->midterm_student_results_count}}</td>

            </tr>
            @endforeach

            <tr>
                <th>Total</th>
                <th>{{ $resultCategories->sum('midterm_student_results_count') }}</th>
            </tr>
        </table> --}}
    </div>
</body>

</html>
