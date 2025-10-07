<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $image = asset('images/logo.png'); ?>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    <title>اطلاعنامه {{ $student->fa_name }} {{ $student->fa_father_name }} - {{ $student->subGrade->name }}</title>
    <style>
        table {
            page-break-inside: avoid;
        }

        td {
            page-break-inside: avoid;
        }

        .main-bg {
            background: #ffa800 !important;
        }

        .result-bg {
            background-color: #ffa80054 !important;
        }

        @media print {


            #score>.main-bg {
                background: #ffa800 !important;
            }

            #score>.result-bg {
                background-color: #ffa80054 !important;
            }
        }

        #logo {
            position: absolute;
            top: 20%;
            bottom: 20%;
            right: 5%;
            left: 5%;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.15;
        }

        @media print {
            #logo {
                background-image: url('{{ $image }}') !important;
                position: absolute;
                top: 20%;
                bottom: 20%;
                right: 5%;
                left: 5%;
                background-repeat: no-repeat;
                background-position: center;
                background-size: contain;
                opacity: 0.15;
            }
        }
    </style>
</head>

<body style="padding:20px;">
    <div class="container">
        <div>
            <div style="direction:rtl;position: relative;" id="container">
                <div style="background-image:url('{{ $image }}') !important;position: absolute;
                top: 20%;
                bottom: 20%;
                right: 5%;
                left: 5%;
                background-repeat: no-repeat;
                background-position: center;
                opacity: 0.04;"
                    id="logo"></div>
                <div style=";padding:25px;border:1px solid black;border-radius:12px;">

                    <div class="text-center">
                        <img src="{{ asset('images/logo.png') }}" style="width: 200px;">
                    </div>
                    <h3 class="text-center">اطلاعنامه</h3>
                    <table class="table table-bordered border-dark">
                        <tr>
                            <th class="text-center" style="background: #ffa800 !important;" colspan="2">مشخصات شاگرد
                            </th>
                        </tr>
                        <tr>
                            <th>آی دی</th>
                            <th class="text-start">{{ $student->id_number ? $student->id_number : $student->id }}</th>
                        </tr>
                        <tr>
                            <th class="text-right" style="width: 150px;">اسم شاگرد</th>
                            <th class="text-start">{{ $student->fa_name ? $student->fa_name : $student->name }}
                                {{ $student->fa_last_name ? $student->fa_last_name : $student->last_name }}</th>
                        </tr>
                        <tr>
                            <th>اسم پدر</th>
                            <th class="text-start">
                                {{ $student->fa_father_name ? $student->fa_father_name : $student->father_name }}</th>
                        </tr>
                        <tr>
                            <th>صنف</th>
                            <th class="text-start">{{ $studentResult->subGrade->name }}</th>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-7" id="score">
                            <div>
                                <table class="table table-bordered border-dark">
                                    <tr>
                                        <th class="text-start w-25">مضمون</th>
                                        <th class="text-center w-25">چهارنم ماهه</th>
                                        <th class="text-center w-25">سالانه</th>
                                        <th class="text-center w-25">مجموع</th>
                                    </tr>
                                    <?php
                                    $middle = 0;
                                    $final = 0;
                                    $total = 0;
                                    ?>
                                    @foreach ($subjects as $subject)
                                        <?php
                                        $middleScore = $subject->subject?->middle?->total;
                                        $finalScore = $subject->subject?->final?->total;
                                        $totalScore = $subject->subject?->finalResult?->total;
                                        
                                        $middle += $middleScore;
                                        $final += $finalScore;
                                        $total += $totalScore;
                                        ?>

                                        <tr>
                                            <td class="text-start">{{ $subject->subject->name }}</td>
                                            <td class="text-center" style="{{ $middleScore < 16 ? 'color:red' : '' }}">
                                                {{ floatval($middleScore) }}</td>
                                            <td class="text-center" style="{{ $finalScore < 24 ? 'color:red' : '' }}">
                                                {{ $finalScore ? floatval($finalScore) : '' }}</td>
                                            <td class="text-center" style="{{ $totalScore < 40 ? 'color:red' : '' }}">
                                                {{ $totalScore > $middleScore ? floatval($totalScore) : '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-start">نتیجه</th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $studentResult->middle_result_name }}
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $final > 0 ? $studentResult->final_result_name : '' }}
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $final > 0 ? $studentResult->result_name : '' }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #ffa80054 !important"
                                            class="text-start text-danger">
                                            مجموعه</th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">{{ $middle }}</th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">{{ $final > 0 ? $final : '' }}
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $total > $middle ? $total : '' }}</th>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-start">فیصدی</th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ round($studentResult->middle_percentage, 2) }}%
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $final > 0 ? round($studentResult->final_percentage, 2) . '%' : '' }}
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $final > 0 ? round($studentResult->percentage, 2) . '%' : '' }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-start">درجه</th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $studentResult->middleResult->name }}
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $final > 0 ? $studentResult->finalResult->name : '' }}
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $final > 0 ? $studentResult->result->name : '' }}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="table table-bordered border-dark">
                                    <tr>
                                        <th class="text-center w-75" style="background: #ffa800 !important;">درجه بندی
                                            شاگردان بر اساس فیصدی</th>
                                        <th class="text-center w-25" style="background: #ffa800 !important;">نتیجه</th>
                                    </tr>
                                    @foreach ($results as $result)
                                        <tr>
                                            <td class="text-center"> از {{ $result->from }} الی {{ $result->to }}
                                                @if ($result->result == 'تکرار صنف')
                                                    (تکرار صنف)
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $result->name }}</td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>
                        <div class="col-5" style="padding-right:80px !important;">
                            <div>
                                <table class="table table-bordered border-dark">
                                    <tr>
                                        <th class="text-center" colspan="2" style="background: #ffa800 !important;">
                                            حاضری</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:60%">مجموع ساعات درسی</th>
                                        <th class="text-start">
                                            {{ $student->middleAttendance?->total_class_hours + $student->finalAttendance?->total_class_hours }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>حاضر</th>
                                        <th class="text-start">
                                            {{ $student->middleAttendance?->present + $student->finalAttendance?->present }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>غیر حاضر</th>
                                        <th class="text-start">
                                            {{ $student->middleAttendance?->absent + $student->finalAttendance?->absent }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>رخصتی</th>
                                        <th class="text-start">
                                            {{ $student->middleAttendance?->permission + $student->finalAttendance?->permission }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>مریضی</th>
                                        <th class="text-start">
                                            {{ $student->middleAttendance?->patien + $student->finalAttendance?->patien }}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                            <div class="text-center" style="margin-top:120px;">
                                امضاء استاد نگران
                                <table class="table table-bordered border-dark">
                                    <tr>
                                        <th class="text-center" style="height: 60px !important">
                                            @if ($responsible)
                                                <img src="{{ $responsible->teacher->signature }}"
                                                    style="height: 70px;">
                                            @endif
                                        </th>
                                    </tr>
                                </table>
                            </div>

                            <div class="text-center" style="margin-top:80px;">
                                امضاء مدیر مکتب
                                <table class="table table-bordered border-dark">
                                    <tr>
                                        <th class="text-center" style="height: 60px !important">
                                            <img src="{{ asset('images/boss.png') }}" style="height: 70px;">
                                        </th>
                                    </tr>
                                </table>
                            </div>
                            <div class="text-right" style="margin-top:120px;display:flex;">

                                <div style="flex:1"></div>
                                <div style="226px !important;">
                                    <div id="qrcode"></div>
                                    {{-- <svg id="qrcode" xmlns="http://www.w3.org/2000/svg"></svg> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('assets/js/jquery-min.js') }}"></script>
        <script src="{{ asset('assets/js/qrcode.js') }}"></script>

        <script type="text/javascript">
            var qrcode = new QRCode(document.getElementById("qrcode"), {
                width: 170,
                height: 170,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            function makeCode(url) {
                if (!url) {
                    alert("No URL provided");
                    return;
                }
                qrcode.makeCode(url);
            }

            makeCode("{{ $qrCode }}")
        </script>
</body>

</html>
