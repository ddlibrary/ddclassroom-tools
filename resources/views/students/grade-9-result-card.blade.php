<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $image = asset('images/logo.png'); ?>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <title>Certificate {{ $student->name }} {{ $student->father_name }} - {{ $student->subGrade->name }}</title>
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
                background-size: contain;
                opacity: 0.15;"
                    id="logo"></div>
                <div style=";padding:25px;border:1px solid black;border-radius:12px;">
                    <div class="d-flex">
                        <div class="flex-fill">
                            <div class="h4">
                                Darakht-e Danesh School
                            </div>
                            <div class="h4">
                                Grade 9 Report Card
                            </div>
                            <div class="h5">
                                Academic Year: {{ date('Y') }} - Semester 1
                            </div>
                        </div>
                        <div class="flex-fill text-end">
                            <img src="{{ asset('images/logo.png') }}" style="width: 250px;">
                        </div>
                    </div>
                    <div class="text-center">
                    </div>
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
                            <th class="text-right" style="width: 250px;">Name</th>
                            <th class="text-start">{{ $student->name ? $student->name : $student->name }}
                                {{ $student->last_name ? $student->last_name : $student->last_name }}</th>
                        </tr>
                        <tr>
                            <th>Father Name</th>
                            <th class="text-start">
                                {{ $student->father_name ? $student->father_name : $student->father_name }}</th>
                        </tr>
                        <tr>
                            <th>Class</th>
                            <th class="text-start">{{ $student->subGrade->name }}</th>
                        </tr>
                        <tr>
                            <th>Homeroom Teacher</th>
                            <th class="text-start">{{ $responsible?->teacher?->name }}</th>
                        </tr>
                        <tr>
                            <th>Reporting Date</th>
                            <th class="text-start">{{ date('Y/m/d') }}</th>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-6" id="score">
                            <div>
                                <table class="table table-bordered border-dark">
                                    <tr>
                                        <th class="text-start w-75">Subject</th>
                                        <th class="text-center w-25">Total</th>
                                    </tr>
                                    <?php
                                    $middle = 0;
                                    $final = 0;
                                    $total = 0;
                                    $allowSubjects = [];
                                    $totalSubjects = 1;
                                    if($student->country_id == 2){
                                        $totalSubjects = 5;
                                        $allowSubjects = [1, 3, 4, 6, 11];
                                    }elseif($student->country_id == 3){

                                        $allowSubjects = [2,10,9,5,7,8];
                                        $totalSubjects = 6;
                                    }
                                    ?>
                                    @foreach ($subjects->whereIn('subject_id', $allowSubjects) as $subject)
                                        <?php
                                        $totalScore = $subject->subject?->finalResult?->total;
                                        
                                        $total += $totalScore;
                                        ?>

                                        <tr>
                                            <td class="text-start">{{ $subject->subject->en_name }}</td>

                                            <td class="text-center">
                                                {{ floatval($totalScore) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-start">Average</th>

                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ floatval($total / $totalSubjects) }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #ffa80054 !important"
                                            class="text-start text-danger">
                                            Total</th>

                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ floatval($total) }}</th>
                                    </tr>

                                    <tr>
                                        <th style="background-color: #ffa80054 !important"
                                            class="text-start text-danger">
                                            Promotion Status</th>

                                        <th style="background-color: #ffa80054 !important;"
                                            class="result-bg text-danger text-center">
                                            {{ $total >= ($totalSubjects * 100 / 2) ? 'Passed' : 'Failed' }}
                                        </th>
                                    </tr>
                                </table>
                            </div>

                        </div>
                        <div class="col-6" style="padding-left:80px !important;">
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
                                            {{ $student->middleAttendance?->permission + $student->finalAttendance?->permission + $student->middleAttendance?->patien + $student->finalAttendance?->patien }}
                                        </th>
                                    </tr>
                                   
                                </table>

                                <div class="text-right" style="margin-top:10px;display:flex;">

                                    <div style="flex:1"></div>
                                    <div>
                                        <div id="qrcode"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div style="gap:50px" class="d-flex">
                            <div class="text-center" style="margin-top:120px;flex:1">
                                Signature of Course Representative
                                <table class="table table-bordered border-dark">
                                    <tr>
                                        <th class="text-center" style="height: 100px !important">
                                            @if ($responsible)
                                                <img src="{{ $responsible->teacher->signature }}"
                                                    style="height: 90px;">
                                            @endif
                                        </th>
                                    </tr>
                                </table>
                            </div>

                            <div class="text-center" style="margin-top:120px;flex:1">
                                Signature of School Principal
                                <table class="table table-bordered border-dark">
                                    <tr>
                                        <th class="text-center" style="height: 100px !important">
                                            <img src="{{ asset('images/boss.png') }}" style="height: 90px;">
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
