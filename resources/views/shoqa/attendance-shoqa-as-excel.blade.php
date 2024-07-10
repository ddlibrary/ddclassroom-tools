<!DOCTYPE html>
<html lang="en" dir="rtl">

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


    <div style="width: 8.5in !important;direction: rtl !important;">
        <table style="width: 8.5in !important;direction: rtl !important;">
            <tr>
                <td style="width: 100% !important; vertical-align:middle !important; height:100px !important" colspan="9">
                    <div style="display: flex">
                        <div>
                            <img src="{{ public_path('images/logo.webp')}}"  height="70px;">
                        </div>
                        <div>
                            <h4 height="20px">
                                امتحان: (
                                    @if($attendanceDetail && $attendanceDetail->type == 1)
                                        چهارنیم ماهه
                                    @elseif ($attendanceDetail && $attendanceDetail->type == 2)
                                        سالانه
                                    @endif
                                 )
                            </h4>
                            <h4 height="20px">
                                نام معلم: ({{ $attendanceDetail?->teacher?->name}})
                            </h4>
                            <h4 height="20px">
                                صنف: ({{ $attendanceDetail?->subGrade?->name}})
                            </h4>
                            <h4 height="20px">
                                مضمون: ({{ $subject->name }} )
                            </h4>
                            <h4 height="20px">
                                تاریخ: ({{ $attendanceDetail?->created_at}})
                            </h4>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <table style="width: 8.5in !important;direction: rtl !important;">
            <tr>
                <th style="text-align: left;border:1px solid #000 !important;text-weight:bold !important;">ملاحضات
                </th>
                <th style="text-align: left;border:1px solid #000 !important;text-weight:bold !important;">مریضی
                </th>
                <th style="text-align: left;border:1px solid #000 !important;text-weight:bold !important;">رخصتی</th>
                <th style="text-align: left;border:1px solid #000 !important;text-weight:bold !important;">غیر حاضر</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold !important;">حاضر
                </th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold !important;">مجموع ساعات درسی
                </th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold !important;">نام پدر
                </th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold !important;">نام</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold !important;">نمبر اساس</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold !important;">شماره</th>
            </tr>
            @foreach ($enrollments as $enrollment)
                <tr>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->attendanceDetail?->note }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->attendanceDetail?->patient }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->attendanceDetail?->permission }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->attendanceDetail?->absent }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->attendanceDetail?->present }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->attendanceDetail?->total_class_hours }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student->father_name }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student->name }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student->id_number }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $loop->iteration }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</body>

</html>
