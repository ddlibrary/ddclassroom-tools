<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $image = asset('images/logo.png'); ?>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <title>اطلاعنامه {{ $student->name }} {{ $student->father_name }} - {{ $student->subGrade->name }}</title>
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
            right: 15%;
            left: 15%;
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
                right: 15%;
                left: 15%;
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
        <button onclick="printDiv('container')" id="print" class="btn btn-success my-2">Print</button>
        <a href="{{ url('email-handbook/' . $student->uuid) }}" style="float:left">
        </a>

        <div>

            <div style="direction:ltr;position: relative;" id="container">
                <div style="background-image:url('{{ $image }}') !important;position: absolute;
                top: 20%;
                bottom: 20%;
                right: 15%;
                left: 15%;
                background-repeat: no-repeat;
                background-position: center;
                opacity: 0.15;"
                    id="logo"></div>
                <div style=";padding:25px;border:1px solid black;border-radius:12px;">

                    <div class="text-center">
                        <img src="{{ asset('images/logo.png') }}" style="width: 200px;">
                    </div>
                    <h3 class="text-center">Result Card</h3>
                    <table class="table table-bordered border-dark">
                        <tr>
                            <th class="text-center" style="background: #ffa800 !important;" colspan="2"> Student
                                Information
                            </th>
                        </tr>
                        <tr>
                            <th>ID#</th>
                            <th class="text-start">{{ $student->id_number ? $student->id_number : $student->id }}</th>
                        </tr>
                        <tr>
                            <th class="text-right" style="width: 150px;">Student Name</th>
                            <th class="text-start">{{ $student->name ? $student->name : $student->name }}
                                {{ $student->last_name ? $student->last_name : $student->last_name }}</th>
                        </tr>
                        <tr>
                            <th>Father Name</th>
                            <th class="text-start">
                                {{ $student->father_name ? $student->father_name : $student->father_name }}</th>
                        </tr>
                        <tr>
                            <th>Course</th>
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
                                        <th class="text-start w-25">Subject</th>
                                        <th class="text-center w-25">Midterm</th>
                                        <th class="text-center w-25">Final</th>
                                        <th class="text-center w-25">Total</th>
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
                                            <td class="text-start">{{ $subject->subject->en_name }}</td>
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
                                            class="result-bg text-danger text-start">Result</th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ getResultName($studentResult->middle_result_name) }}
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $final > 0 ? getResultName($studentResult->final_result_name) : '' }}
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $final > 0 ? getResultName($studentResult->result_name) : '' }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #ffa80054 !important"
                                            class="text-start text-danger">
                                            Total</th>
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
                                            class="result-bg text-danger text-start">Percentage</th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ round($student->studentResult->middle_percentage, 2) }}%
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $final > 0 ? round($student->studentResult->final_percentage, 2) . '%' : '' }}
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $final > 0 ? round($student->studentResult->percentage, 2) . '%' : '' }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-start">Grade</th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $student->studentResult->middleResult->name }}
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $final > 0 ? $student->studentResult->finalResult->name : '' }}
                                        </th>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $final > 0 ? $student->studentResult->result->name : '' }}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="table table-bordered border-dark">
                                    <tr>
                                        <th class="text-center w-75" style="background: #ffa800 !important;">
                                            Categorized Students based on
                                            Percentage
                                        </th>
                                        <th class="text-center w-25" style="background: #ffa800 !important;">Result</th>
                                    </tr>
                                    @foreach ($results as $result)
                                        <tr>
                                            <td class="text-center"> From {{ $result->from }} to {{ $result->to }}
                                                @if ($result->result == 'تکرار صنف')
                                                    (repeat course)
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $result->name }}</td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>
                        <div class="col-5" style="padding-left:80px !important;">
                            <div>
                                <table class="table table-bordered border-dark">
                                    <tr>
                                        <th class="text-center" colspan="2" style="background: #ffa800 !important;">
                                            Attendance</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:60%">Total Study Hours</th>
                                        <th class="text-start">
                                            {{ $student->middleAttendance?->total_class_hours + $student->finalAttendance?->total_class_hours }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Present</th>
                                        <th class="text-start">
                                            {{ $student->middleAttendance?->present + $student->finalAttendance?->present }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Absent</th>
                                        <th class="text-start">
                                            {{ $student->middleAttendance?->absent + $student->finalAttendance?->absent }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Vacation</th>
                                        <th class="text-start">
                                            {{ $student->middleAttendance?->permission + $student->finalAttendance?->permission }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Sickness</th>
                                        <th class="text-start">
                                            {{ $student->middleAttendance?->patien + $student->finalAttendance?->patien }}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                            <div class="text-center" style="margin-top:120px;">
                                Signature of Course Representative
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
                                Signature of School Principal
                                <table class="table table-bordered border-dark">
                                    <tr>
                                        <th class="text-center" style="height: 60px !important">
                                            <img src="{{ asset('images/boss.png') }}" style="height: 70px;">
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('assets/js/jquery-min.js') }}"></script>
        <script src="{{ asset('assets/js/print-this.js') }}"></script>
        <script src="{{ asset('assets/js/myjs.js') }}"></script>
</body>

</html>
