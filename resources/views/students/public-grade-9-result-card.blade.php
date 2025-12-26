<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $image = asset('images/logo.png'); ?>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <title>Grade 9 Report Card - {{ $student->name }} {{ $student->father_name }}</title>
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

        .watermark {
            position: absolute;
            top: 20%;
            bottom: 20%;
            right: 15%;
            left: 15%;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.15;
            pointer-events: none;
        }

        @media print {
            .watermark {
                background-image: url('{{ $image }}') !important;
            }
        }
    </style>
</head>

<body style="padding:20px;">
    <div class="container">
        <button onclick="printDiv('container')" id="print" class="btn btn-success my-2">Print</button>
        <a href="{{ url('student-result-card/' . $student->uuid . '/' . base64_decode($year) . '/' . $studentResult->id) }}"
            style="float:left" class="mx-2">Persian Result Card</a>

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
                <div style="padding:25px;border:1px solid black;border-radius:12px;">


                    <!-- Header Section -->
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <div class="h4 mb-1">Darakht-e Danesh School</div>
                            <div class="h5 mb-1">Grade 9 Report Card</div>
                            <div class="h6">Academic Year: {{ base64_decode($year) }}</div>
                        </div>
                        <div>
                            <img src="{{ asset('images/logo.png') }}" style="width: 200px;">
                        </div>
                    </div>

                    <!-- Student Information and QR Code -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <table class="table table-bordered border-dark">
                                <tr>
                                    <th class="text-center" style="background: #ffa800 !important;" colspan="3">Student
                                        Information</th>
                                </tr>
                                <tr>
                                    <th style="width: 200px;">ID#</th>
                                    <th class="text-start">{{ $student->id_number ? $student->id_number : $student->id }}
                                    </th>
                                    <th style="width: 200px;" rowspan="6">
                                        <div id="qrcode"></div>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <th class="text-start">
                                        {{ $student->name ? $student->name : $student->name }}
                                        {{ $student->last_name ? $student->last_name : $student->last_name }}</th>
                                </tr>
                                <tr>
                                    <th>Father Name</th>
                                    <th class="text-start">
                                        {{ $student->father_name ? $student->father_name : $student->father_name }}</th>
                                </tr>
                                <tr>
                                    <th>Class</th>
                                    <th class="text-start">{{ $studentResult->subGrade->name }}</th>
                                </tr>
                                <tr>
                                    <th>Homeroom Teacher</th>
                                    <th class="text-start">{{ $responsible?->teacher?->en_name ?? 'Test' }}</th>
                                </tr>
                                <tr>
                                    <th>Reporting Date</th>
                                    <th class="text-start">{{ date('Y/m/d') }}</th>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php
                    // Get subjects for both semesters
                    $yearDecoded = base64_decode($year);
                    $allowSubjectsSem1 = [];
                    $allowSubjectsSem2 = [];
                    $totalSubjectsSem1 = 1;
                    $totalSubjectsSem2 = 1;
                    $oldClasses = [2, 10, 9, 5, 7, 8];
                    $newClasses = [1, 3, 4, 6, 11];

                    $totalSubjectSem1 = $student->subGrade->getFirstSemesterSubjectCountForYear($yearDecoded);
                    $totalSubjectSem2 = $student->subGrade->getSecondSemesterSubjectCountForYear($yearDecoded);

                    if ($totalSubjectSem1 > 0) {
                        $allowSubjectsSem1 = $student->subGrade->firstSemesterSubjectsForYear($yearDecoded)->pluck('subject_id')->toArray();
                        $totalSubjectsSem1 = $totalSubjectSem1;
                    } else {
                        if ($student->country_id == 1) {
                            if ($student->sub_grade_id == 13 || $student->sub_grade_id == 14) {
                                $totalSubjectsSem1 = 5;
                                $allowSubjectsSem1 = $newClasses;
                            } elseif ($student->sub_grade_id == 11 || $student->sub_grade_id == 12) {
                                $totalSubjectsSem1 = 6;
                                $allowSubjectsSem1 = $oldClasses;
                            }
                        } elseif ($student->country_id == 2) {
                            $totalSubjectsSem1 = 5;
                            $allowSubjectsSem1 = $newClasses;
                        } elseif ($student->country_id == 3) {
                            $allowSubjectsSem1 = $oldClasses;
                            $totalSubjectsSem1 = 6;
                        }
                    }

                    if ($totalSubjectSem2 > 0) {
                        $allowSubjectsSem2 = $student->subGrade->secondSemesterSubjectsForYear($yearDecoded)->pluck('subject_id')->toArray();
                        $totalSubjectsSem2 = $totalSubjectSem2;
                    } else {
                        if ($student->country_id == 1) {
                            if ($student->sub_grade_id == 13 || $student->sub_grade_id == 14) {
                                $totalSubjectsSem2 = 5;
                                $allowSubjectsSem2 = $newClasses;
                            } elseif ($student->sub_grade_id == 11 || $student->sub_grade_id == 12) {
                                $totalSubjectsSem2 = 6;
                                $allowSubjectsSem2 = $oldClasses;
                            }
                        } elseif ($student->country_id == 2) {
                            $totalSubjectsSem2 = 5;
                            $allowSubjectsSem2 = $newClasses;
                        } elseif ($student->country_id == 3) {
                            $allowSubjectsSem2 = $oldClasses;
                            $totalSubjectsSem2 = 6;
                        }
                    }

                    // Calculate Semester 1 results
                    $sem1Total = 0;
                    $sem1State = 'Passed';
                    $sem1Scores = [];
                    foreach ($subjects->whereIn('subject_id', $allowSubjectsSem1) as $subject) {
                        $score = $subject->subject?->middle?->total ?? 0;
                        $sem1Total += $score;
                        $sem1Scores[] = [
                            'name' => $subject->subject->en_name,
                            'score' => $score
                        ];
                        if ($score < 50) {
                            $sem1State = 'Failed';
                        }
                    }
                    $sem1Average = $totalSubjectsSem1 > 0 ? round($sem1Total / $totalSubjectsSem1, 2) : 0;
                    $sem1PromotionStatus = ($sem1State == 'Passed' && $sem1Total >= ($totalSubjectsSem1 * 100) / 2) ? 'Passed' : 'Failed';

                    // Calculate Semester 2 results
                    $sem2Total = 0;
                    $sem2State = 'Passed';
                    $sem2Scores = [];
                    foreach ($subjects->whereIn('subject_id', $allowSubjectsSem2) as $subject) {
                        $score = $subject->subject?->final?->total ?? 0;
                        $sem2Total += $score;
                        $sem2Scores[] = [
                            'name' => $subject->subject->en_name,
                            'score' => $score
                        ];
                        if ($score < 50) {
                            $sem2State = 'Failed';
                        }
                    }
                    $sem2Average = $totalSubjectsSem2 > 0 ? round($sem2Total / $totalSubjectsSem2, 2) : 0;
                    $sem2PromotionStatus = ($sem2State == 'Passed' && $sem2Total >= ($totalSubjectsSem2 * 100) / 2) ? 'Passed' : 'Failed';

                    // Get all unique subjects from both semesters for display
                    $allSubjectIds = array_unique(array_merge($allowSubjectsSem1, $allowSubjectsSem2));
                    $allSubjectsData = [];
                    foreach ($subjects->whereIn('subject_id', $allSubjectIds) as $subject) {
                        $sem1Score = in_array($subject->subject_id, $allowSubjectsSem1) ? ($subject->subject?->middle?->total ?? 0) : 0;
                        $sem2Score = in_array($subject->subject_id, $allowSubjectsSem2) ? ($subject->subject?->final?->total ?? 0) : 0;
                        $allSubjectsData[] = [
                            'name' => $subject->subject->en_name,
                            'sem1' => $sem1Score,
                            'sem2' => $sem2Score
                        ];
                    }

                    // Cumulative Results
                    $cumulativeTotal = $sem1Total + $sem2Total;
                    $totalSubjects = max($totalSubjectsSem1, $totalSubjectsSem2);
                    // Yearly average: average of both semester averages
                    $yearlyAverage = ($totalSubjectsSem1 > 0 && $totalSubjectsSem2 > 0) ? round(($sem1Average + $sem2Average) / 2, 2) : ($totalSubjectsSem1 > 0 ? $sem1Average : ($totalSubjectsSem2 > 0 ? $sem2Average : 0));
                    $totalPassedSubjects = 0;
                    foreach ($allSubjectsData as $subj) {
                        if ($subj['sem1'] >= 50) {
                            $totalPassedSubjects++;
                        }
                        if ($subj['sem2'] >= 50) {
                            $totalPassedSubjects++;
                        }
                    }
                    $overallResult = ($yearlyAverage >= 50 && $sem1PromotionStatus == 'Passed' && $sem2PromotionStatus == 'Passed') ? 'Passed' : 'Failed';
                    ?>

                    <!-- Semester Results Section -->
                    <div class="row mb-4">
                        <!-- Semester 1 -->
                        <div class="col-6" id="score">
                            <table class="table table-bordered border-dark">
                                <tr>
                                    <th class="text-center" style="background: #ffa800 !important;" colspan="2">Semester-1
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-start" style="width: 60%;">Subject</th>
                                    <th class="text-center">Total</th>
                                </tr>
                                @foreach ($allSubjectsData as $subject)
                                    @if ($subject['sem1'] > 0)
                                        <tr>
                                            <td class="text-start">{{ $subject['name'] }}</td>
                                            <td class="text-center">{{ floatval($subject['sem1']) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <th class="text-start result-bg" style="background-color: #ffa80054 !important;">
                                        Average</th>
                                    <th class="text-center result-bg" style="background-color: #ffa80054 !important;">
                                        {{ floatval($sem1Average) }}</th>
                                </tr>
                                <tr>
                                    <th class="text-start result-bg" style="background-color: #ffa80054 !important;">Total
                                    </th>
                                    <th class="text-center result-bg" style="background-color: #ffa80054 !important;">
                                        {{ floatval($sem1Total) }}</th>
                                </tr>
                                <tr>
                                    <th class="text-start result-bg" style="background-color: #ffa80054 !important;">
                                        Promotion Status</th>
                                    <th class="text-center result-bg" style="background-color: #ffa80054 !important;">
                                        {{ $sem1PromotionStatus }}</th>
                                </tr>
                            </table>
                        </div>

                        <!-- Semester 2 -->
                        <div class="col-6" id="score">
                            <table class="table table-bordered border-dark">
                                <tr>
                                    <th class="text-center" style="background: #ffa800 !important;" colspan="2">Semester-2
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-start" style="width: 60%;">Subject</th>
                                    <th class="text-center">Total</th>
                                </tr>
                                @foreach ($allSubjectsData as $subject)
                                    @if ($subject['sem2'] > 0)
                                        <tr>
                                            <td class="text-start">{{ $subject['name'] }}</td>
                                            <td class="text-center">{{ floatval($subject['sem2']) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <th class="text-start result-bg" style="background-color: #ffa80054 !important;">
                                        Average</th>
                                    <th class="text-center result-bg" style="background-color: #ffa80054 !important;">
                                        {{ floatval($sem2Average) }}</th>
                                </tr>
                                <tr>
                                    <th class="text-start result-bg" style="background-color: #ffa80054 !important;">Total
                                    </th>
                                    <th class="text-center result-bg" style="background-color: #ffa80054 !important;">
                                        {{ floatval($sem2Total) }}</th>
                                </tr>
                                <tr>
                                    <th class="text-start result-bg" style="background-color: #ffa80054 !important;">
                                        Promotion Status</th>
                                    <th class="text-center result-bg" style="background-color: #ffa80054 !important;">
                                        {{ $sem2PromotionStatus }}</th>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Cumulative Result and Attendance Section -->
                    <div class="row mb-4">
                        <!-- Cumulative Result -->
                        <div class="col-6">
                            <table class="table table-bordered border-dark">
                                <tr>
                                    <th class="text-center" style="background: #ffa800 !important;" colspan="2">Cumulative
                                        Result</th>
                                </tr>
                                <tr>
                                    <th class="text-start" style="width: 60%;font-size:14px;">Total Marks Obtained (All Semesters)</th>
                                    <th class="text-center">{{ $cumulativeTotal }}</th>
                                </tr>
                                <tr>
                                    <th class="text-start">Yearly Average (%)</th>
                                    <th class="text-center">{{ $yearlyAverage }}</th>
                                </tr>
                                <tr>
                                    <th class="text-start">Total Passed Subjects</th>
                                    <th class="text-center">{{ $totalPassedSubjects }}</th>
                                </tr>
                                <tr>
                                    <th class="text-start">Overall Result (Pass/Fail)</th>
                                    <th class="text-center">{{ $overallResult }}</th>
                                </tr>
                            </table>
                        </div>

                        <!-- Attendance -->
                        <div class="col-6">
                            <table class="table table-bordered border-dark">
                                <tr>
                                    <th class="text-center" style="background: #ffa800 !important;" colspan="2">Attendance
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-start" style="width: 60%;">Total Study Hours</th>
                                    <th class="text-center">
                                        {{ ($student->middleAttendance?->total_class_hours ?? 0) + ($student->finalAttendance?->total_class_hours ?? 0) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-start">Present</th>
                                    <th class="text-center">
                                        {{ ($student->middleAttendance?->present ?? 0) + ($student->finalAttendance?->present ?? 0) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-start">Absent</th>
                                    <th class="text-center">
                                        {{ ($student->middleAttendance?->absent ?? 0) + ($student->finalAttendance?->absent ?? 0) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-start">Vacation</th>
                                    <th class="text-center">
                                        {{ ($student->middleAttendance?->permission ?? 0) + ($student->finalAttendance?->permission ?? 0) + ($student->middleAttendance?->patien ?? 0) + ($student->finalAttendance?->patien ?? 0) }}
                                    </th>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Signature Section -->
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="text-center">
                                Signature of Course Representative
                                <table class="table table-bordered border-dark mt-2">
                                    <tr>
                                        <th class="text-center" style="height: 80px !important">
                                            @if ($responsible)
                                                <img src="{{ $responsible->teacher->signature }}" style="height: 70px;">
                                            @endif
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                Signature of School Principal
                                <table class="table table-bordered border-dark mt-2">
                                    <tr>
                                        <th class="text-center" style="height: 80px !important">
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
        <script src="{{ asset('assets/js/qrcode.js') }}"></script>
        <script type="text/javascript">
            var qrcode = new QRCode(document.getElementById("qrcode"), {
                width: 230,
                height: 230,
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

