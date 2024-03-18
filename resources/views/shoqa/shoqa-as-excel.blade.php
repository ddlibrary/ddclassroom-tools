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
                <td style="width: 50% !important; vertical-align:middle !important; height:100px !important" colspan="5">
                    <img src="{{ public_path('images/logo.webp')}}"  height="70px;">
                </td>
                <td style="width: 50% !important; vertical-align:middle !important; height:100px !important" colspan="5">
                    <h4 height="20px">
                        امتحان: ( {{ $type == 1 ? 'چهارنیم ماه' : 'سالانه'}} )
                    </h4>
                    <h4 height="20px">
                        نام معلم: (..........)
                    </h4>
                    <h4 height="20px">
                        صنف: ( {{$grade->name}} )    
                    </h4>
                    <h4 height="20px">
                        مضمون: ({{ $subject->name }} )
                    </h4>
                    <h4 height="20px">
                        تاریخ: ({{$year}})
                    </h4>
                </td>
            </tr>
        </table>
        <table style="width: 8.5in !important;direction: rtl !important;">
            <tr> 
                <th style="text-align: left;border:1px solid #000 !important;text-weight:bold !important;">مجموع 60</th>
                <th style="text-align: left;border:1px solid #000 !important;text-weight:bold !important;">ارزیابی 10
                </th>
                <th style="text-align: left;border:1px solid #000 !important;text-weight:bold !important;">کارخانگی 5
                </th>
                <th style="text-align: left;border:1px solid #000 !important;text-weight:bold !important;">فعالیت 5</th>
                <th style="text-align: left;border:1px solid #000 !important;text-weight:bold !important;">حاضری 6</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold !important;">تقریری 10
                </th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold !important;">تحریری 24
                </th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold !important;">نام پدر
                </th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold !important;">نام</th>
                <th style="text-align: center;border:1px solid #000 !important;text-weight:bold !important;">شماره</th>
            </tr>
            @foreach ($enrollments as $enrollment)
                <tr>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->score?->total }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->score?->evaluation }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->score?->homework }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->score?->activity }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->score?->attendance }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->score?->verbal }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student?->score?->written }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student->fa_father_name }}
                    </td>
                    <td style="text-align: center;border:1px solid #000 !important">
                        {{ $enrollment->student->fa_name }}
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
