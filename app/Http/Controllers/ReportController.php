<?php

namespace App\Http\Controllers;

use App\Exports\ExportAllStudentsSubjectScores;
use App\Exports\ExportGrade9Report;
use App\Exports\ExportStudentResultsReport;
use App\Exports\ExportSubjectScoresReport;
use App\Exports\ExportSubjectStatisticsReport;
use App\Models\Country;
use App\Models\Grade;
use App\Models\GradeSubject;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\SubGrade;
use App\Models\SubGradeSubjectSemester;
use App\Models\Year;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function studentResults(Request $request)
    {
        $grades = Grade::where('is_active', true)->get(['id', 'name']);
        $subGrades = SubGrade::whereIsActive(true)->get(['id', 'full_name', 'grade_id']);
        $years = Year::all(['id', 'name']);
        $countries = Country::where('is_active', true)->get(['id', 'name']);

        if (!$request->has('search')) {
            $paginatedResults = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                350,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return inertia('Report/StudentResults', [
                'results' => $paginatedResults,
                'grades' => $grades,
                'subGrades' => $subGrades,
                'years' => $years,
                'countries' => $countries,
                'kamyabCount' => 0,
                'nakamCount' => 0,
                'mashrootCount' => 0,
                'totalCount' => 0,
            ]);
        }

        $query = StudentResult::query()->with([
            'student:id,name,father_name,country_id,fa_name,fa_father_name,uuid,id_number,email',
            'middleResult:id,name',
            'subGrade:id,full_name,grade_id',
            'subGrade.grade:id,name',
            'teacher:id,name'
        ])->whereHas('student', function ($q) {
            $q->where('is_active', true);
        });

        $this->applyStudentResultFilters($query, $request);
        $results = $query->orderByDesc('id')->paginate(350)->appends($request->query());

        $kamyabCount = $this->getKamyabCount($request);
        $nakamCount = $this->getNakamCount($request);
        $mashrootCount = $this->getMashrootCount($request);
        $totalCount = $this->getTotalStudentResultCount($request);

        return inertia('Report/StudentResults', [
            'results' => $results,
            'grades' => $grades,
            'subGrades' => $subGrades,
            'years' => $years,
            'countries' => $countries,
            'kamyabCount' => $kamyabCount,
            'nakamCount' => $nakamCount,
            'mashrootCount' => $mashrootCount,
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * Apply filters to StudentResult query
     */
    private function applyStudentResultFilters($query, Request $request)
    {
        if ($request->student_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('id_number', 'like', "%{$request->student_id}%");
            });
        }

        if ($request->email) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->email}%");
            });
        }

        if ($request->country_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }

        if ($request->grade_id) {
            $gradeIds = is_array($request->grade_id) ? $request->grade_id : [$request->grade_id];
            $gradeIds = array_filter($gradeIds);
            if (!empty($gradeIds)) {
                $query->whereHas('subGrade', function ($q) use ($gradeIds) {
                    $q->whereIn('grade_id', $gradeIds);
                });
            }
        }

        if ($request->sub_grade_id) {
            $subGradeIds = is_array($request->sub_grade_id) ? $request->sub_grade_id : [$request->sub_grade_id];
            $subGradeIds = array_filter($subGradeIds);
            if (!empty($subGradeIds)) {
                $query->whereIn('sub_grade_id', $subGradeIds);
            }
        }


        if ($request->year) {
            $query->where('year', $request->year);
        }

        if ($request->exam_type === 'midterm') {
            $query->whereNotNull('middle')->where('middle', '>', 0);
        } elseif ($request->exam_type === 'final') {
            $query->whereNotNull('final')->where('final', '>', 0);
        }
    }

    /**
     * Get count of successful students (کامیاب)
     */
    private function getKamyabCount(Request $request)
    {
        $query = StudentResult::query()->whereHas('student', function ($q) {
            $q->where('is_active', true);
        });
        $this->applyStudentResultFilters($query, $request);
        $query->where('result_name', 'کامیاب');
        return $query->count();
    }

    /**
     * Get count of failed students (ناکام)
     */
    private function getNakamCount(Request $request)
    {
        $query = StudentResult::query()->whereHas('student', function ($q) {
            $q->where('is_active', true);
        });
        $this->applyStudentResultFilters($query, $request);
        $query->where(function($q) {
            $q->where('result_name', 'تکرار صنف')
              ->orWhere('result_name', 'ناکام');
        });
        return $query->count();
    }

    /**
     * Get count of conditional students (مشروط)
     */
    private function getMashrootCount(Request $request)
    {
        $query = StudentResult::query()->whereHas('student', function ($q) {
            $q->where('is_active', true);
        });
        $this->applyStudentResultFilters($query, $request);
        $query->where(function($q) {
            $q->where('result_name', 'تکرار بیشتر')
              ->orWhere('result_name', 'مشروط');
        });
        return $query->count();
    }

    /**
     * Get total count of student results (before pagination)
     */
    private function getTotalStudentResultCount(Request $request)
    {
        $query = StudentResult::query()->whereHas('student', function ($q) {
            $q->where('is_active', true);
        });
        $this->applyStudentResultFilters($query, $request);
        return $query->count();
    }

    /**
     * Subject Scores Report (when subject is selected)
     */
    public function subjectScores(Request $request)
    {
        $grades = Grade::where('is_active', true)->get(['id', 'name']);
        $subGrades = SubGrade::whereIsActive(true)->get(['id', 'full_name', 'grade_id']);
        $years = Year::all(['id', 'name']);
        $countries = Country::where('is_active', true)->get(['id', 'name']);
        $subjects = Subject::all(['id', 'name']);

        if (!$request->has('search')) {
            return inertia('Report/SubjectScores', [
                'results' => new \Illuminate\Pagination\LengthAwarePaginator(
                    collect(),
                    0,
                    350,
                    1,
                    ['path' => $request->url(), 'query' => $request->query()]
                ),
                'grades' => $grades,
                'subGrades' => $subGrades,
                'years' => $years,
                'countries' => $countries,
                'subjects' => $subjects,
                'passedCount' => 0,
                'failedCount' => 0,
            ]);
        }

        $query = Score::query()
            ->select([
                'scores.id',
                'scores.written',
                'scores.verbal',
                'scores.attendance',
                'scores.activity',
                'scores.homework',
                'scores.evaluation',
                'scores.total',
                'scores.type',
                'scores.is_passed',
                'scores.year',
                'scores.student_id',
                'scores.sub_grade_id',
                'scores.subject_id',
                'scores.teacher_id',
                'scores.user_id',
                'scores.created_at',
                'scores.updated_at'
            ])
            ->with([
                'student:id,name,father_name,country_id,fa_name,fa_father_name,uuid,id_number,email',
                'subject:id,name',
                'subGrade:id,full_name,grade_id',
                'subGrade.grade:id,name',
                'teacher:id,name'
            ])
            ->whereHas('student', function ($q) {
                $q->where('is_active', true);
            });

        $query->where('subject_id', $request->subject_id);

        if ($request->student_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('id_number', 'like', "%{$request->student_id}%");
            });
        }

        if ($request->email) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->email}%");
            });
        }

        if ($request->country_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }

        if ($request->grade_id) {
            $gradeIds = is_array($request->grade_id) ? $request->grade_id : [$request->grade_id];
            $gradeIds = array_filter($gradeIds);
            if (!empty($gradeIds)) {
                $query->whereHas('subGrade', function ($q) use ($gradeIds) {
                    $q->whereIn('grade_id', $gradeIds);
                });
            }
        }

        if ($request->sub_grade_id) {
            $subGradeIds = is_array($request->sub_grade_id) ? $request->sub_grade_id : [$request->sub_grade_id];
            $subGradeIds = array_filter($subGradeIds);
            if (!empty($subGradeIds)) {
                $query->whereIn('sub_grade_id', $subGradeIds);
            }
        }


        if ($request->year) {
            $query->where('year', $request->year);
        }

        // Filter by exam type:
        // type=1 => midterm
        // type=2 => final exam
        // type=3 => final result (combination of midterm and final exam)
        if ($request->exam_type === 'midterm') {
            $query->where('type', 1); // Midterm
        } elseif ($request->exam_type === 'final') {
            $query->where('type', 2); // Final exam
        } elseif ($request->exam_type === 'result') {
            $query->where('type', 3); // Final result (combination of midterm and final exam)
        } else {
            $query->where('type', 3);
        }

        // Filter by status (passed/failed)
        if ($request->status === 'passed') {
            $query->where('is_passed', true);
        } elseif ($request->status === 'failed') {
            $query->where('is_passed', false);
        }

        $results = $query->orderByDesc('id')->paginate(350)->appends($request->query());

        $totalPassed = Score::query()
            ->where('subject_id', $request->subject_id)
            ->where('is_passed', true)
            ->whereHas('student', function ($q) {
                $q->where('is_active', true);
            });

        $totalFailed = Score::query()
            ->where('subject_id', $request->subject_id)
            ->where('is_passed', false)
            ->whereHas('student', function ($q) {
                $q->where('is_active', true);
            });

        if ($request->student_id) {
            $totalPassed->whereHas('student', function ($q) use ($request) {
                $q->where('id_number', 'like', "%{$request->student_id}%");
            });
            $totalFailed->whereHas('student', function ($q) use ($request) {
                $q->where('id_number', 'like', "%{$request->student_id}%");
            });
        }
        if ($request->email) {
            $totalPassed->whereHas('student', function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->email}%");
            });
            $totalFailed->whereHas('student', function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->email}%");
            });
        }
        if ($request->country_id) {
            $totalPassed->whereHas('student', function ($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
            $totalFailed->whereHas('student', function ($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }
        if ($request->grade_id) {
            $gradeIds = is_array($request->grade_id) ? $request->grade_id : [$request->grade_id];
            $gradeIds = array_filter($gradeIds);
            if (!empty($gradeIds)) {
                $totalPassed->whereHas('subGrade', function ($q) use ($gradeIds) {
                    $q->whereIn('grade_id', $gradeIds);
                });
                $totalFailed->whereHas('subGrade', function ($q) use ($gradeIds) {
                    $q->whereIn('grade_id', $gradeIds);
                });
            }
        }
        if ($request->sub_grade_id) {
            $subGradeIds = is_array($request->sub_grade_id) ? $request->sub_grade_id : [$request->sub_grade_id];
            $subGradeIds = array_filter($subGradeIds);
            if (!empty($subGradeIds)) {
                $totalPassed->whereIn('sub_grade_id', $subGradeIds);
                $totalFailed->whereIn('sub_grade_id', $subGradeIds);
            }
        }
        if ($request->year) {
            $totalPassed->where('year', $request->year);
            $totalFailed->where('year', $request->year);
        }
        if ($request->exam_type === 'midterm') {
            $totalPassed->where('type', 1);
            $totalFailed->where('type', 1);
        } elseif ($request->exam_type === 'final') {
            $totalPassed->where('type', 2);
            $totalFailed->where('type', 2);
        } elseif ($request->exam_type === 'result') {
            $totalPassed->where('type', 3);
            $totalFailed->where('type', 3);
        } else {
            $totalPassed->where('type', 3);
            $totalFailed->where('type', 3);
        }

        $passedCount = $totalPassed->count();
        $failedCount = $totalFailed->count();

        return inertia('Report/SubjectScores', [
            'results' => $results,
            'grades' => $grades,
            'subGrades' => $subGrades,
            'years' => $years,
            'countries' => $countries,
            'subjects' => $subjects,
            'passedCount' => $passedCount,
            'failedCount' => $failedCount,
        ]);
    }

    /**
     * Subject Statistics Report - Total success and failed counts per subject
     */
    public function subjectStatistics(Request $request)
    {
        $grades = Grade::where('is_active', true)->get(['id', 'name']);
        $subGrades = SubGrade::whereIsActive(true)->get(['id', 'full_name', 'grade_id']);
        $years = Year::all(['id', 'name']);
        $countries = Country::where('is_active', true)->get(['id', 'name']);
        $subjects = Subject::all(['id', 'name']);

        if (!$request->has('search')) {
            return inertia('Report/SubjectStatistics', [
                'results' => collect(),
                'grades' => $grades,
                'subGrades' => $subGrades,
                'years' => $years,
                'countries' => $countries,
                'subjects' => $subjects,
                'totalPassed' => 0,
                'totalFailed' => 0,
                'totalStudents' => 0,
            ]);
        }

        $query = Score::query()
            ->select('scores.subject_id', 'subjects.name as subject_name')
            ->selectRaw('COUNT(CASE WHEN scores.is_passed = 1 THEN 1 END) as passed_count')
            ->selectRaw('COUNT(CASE WHEN scores.is_passed = 0 THEN 1 END) as failed_count')
            ->selectRaw('COUNT(*) as total_count')
            ->join('subjects', 'scores.subject_id', '=', 'subjects.id')
            ->join('students', 'scores.student_id', '=', 'students.id')
            ->where('students.is_active', true)
            ->groupBy('scores.subject_id', 'subjects.name');

        if ($request->student_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('id_number', 'like', "%{$request->student_id}%");
            });
        }

        if ($request->email) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->email}%");
            });
        }

        if ($request->country_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }

        if ($request->grade_id) {
            $gradeIds = is_array($request->grade_id) ? $request->grade_id : [$request->grade_id];
            $gradeIds = array_filter($gradeIds);
            if (!empty($gradeIds)) {
                $query->whereHas('subGrade', function ($q) use ($gradeIds) {
                    $q->whereIn('grade_id', $gradeIds);
                });
            }
        }

        if ($request->sub_grade_id) {
            $subGradeIds = is_array($request->sub_grade_id) ? $request->sub_grade_id : [$request->sub_grade_id];
            $subGradeIds = array_filter($subGradeIds);
            if (!empty($subGradeIds)) {
                $query->whereIn('scores.sub_grade_id', $subGradeIds);
            }
        }

        if ($request->year) {
            $query->where('scores.year', $request->year);
        }

        if ($request->exam_type === 'midterm') {
            $query->where('scores.type', 1);
        } elseif ($request->exam_type === 'final') {
            $query->where('scores.type', 2);
        } elseif ($request->exam_type === 'result') {
            $query->where('scores.type', 3);
        } else {
            $query->where('scores.type', 3);
        }

        $results = $query->orderBy('subjects.name')->get();

        $totalPassed = $results->sum('passed_count');
        $totalFailed = $results->sum('failed_count');
        $totalStudents = $results->sum('total_count');

        return inertia('Report/SubjectStatistics', [
            'results' => $results,
            'grades' => $grades,
            'subGrades' => $subGrades,
            'years' => $years,
            'countries' => $countries,
            'subjects' => $subjects,
            'totalPassed' => $totalPassed,
            'totalFailed' => $totalFailed,
            'totalStudents' => $totalStudents,
        ]);
    }


    public function grade9Report(Request $request)
    {
        $subGrades = SubGrade::whereHas('grade', function ($q) {
            $q->where('id', 9)->where('is_semester_based', true);
        })->whereIsActive(true)->get(['id', 'full_name', 'grade_id']);

        $years = Year::all(['id', 'name']);
        $countries = Country::where('is_active', true)->get(['id', 'name']);

        if (!$request->has('search')) {
            $paginatedResults = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                350,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return inertia('Report/Grade9Report', [
                'results' => $paginatedResults,
                'subGrades' => $subGrades,
                'years' => $years,
                'countries' => $countries,
                'totalKamyab' => 0,
                'totalNakam' => 0,
                'totalStudents' => 0,
            ]);
        }

        $query = StudentResult::query()
            ->with([
                'student:id,name,father_name,country_id,fa_name,fa_father_name,uuid,id_number,email',
                'subGrade:id,full_name,grade_id',
                'subGrade.grade:id,name'
            ])
            ->whereHas('subGrade.grade', function ($q) {
                $q->where('id', 9)->where('is_semester_based', true);
            })
            ->whereHas('student', function ($q) {
                $q->where('is_active', true);
            });

        if ($request->student_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('id_number', 'like', "%{$request->student_id}%");
            });
        }

        if ($request->email) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->email}%");
            });
        }

        if ($request->country_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }

        if ($request->sub_grade_id) {
            $subGradeIds = is_array($request->sub_grade_id) ? $request->sub_grade_id : [$request->sub_grade_id];
            $subGradeIds = array_filter($subGradeIds);
            if (!empty($subGradeIds)) {
                $query->whereIn('sub_grade_id', $subGradeIds);
            }
        }

        if ($request->year) {
            $query->where('year', $request->year);
        }

        $studentResults = $query->orderByDesc('id')->get();

        $results = [];
        $totalKamyab = 0;
        $totalNakam = 0;

        foreach ($studentResults as $studentResult) {
            $year = $studentResult->year;
            $subGrade = $studentResult->subGrade;
            $student = $studentResult->student;

            $semester1Subjects = SubGradeSubjectSemester::where('sub_grade_id', $subGrade->id)
                ->where('semester', 1)
                ->where('year', $year)
                ->with('subject:id,name')
                ->get();

            $semester2Subjects = SubGradeSubjectSemester::where('sub_grade_id', $subGrade->id)
                ->where('semester', 2)
                ->where('year', $year)
                ->with('subject:id,name')
                ->get();

            $allSubjectIds = $semester1Subjects->pluck('subject_id')
                ->merge($semester2Subjects->pluck('subject_id'))
                ->unique()
                ->toArray();

            $semester1Scores = Score::where('student_id', $student->id)
                ->where('sub_grade_id', $subGrade->id)
                ->where('year', $year)
                ->where('type', 1) // Midterm for Semester 1
                ->whereIn('subject_id', $semester1Subjects->pluck('subject_id')->toArray())
                ->with('subject:id,name')
                ->get()
                ->keyBy('subject_id');

            $semester2Scores = Score::where('student_id', $student->id)
                ->where('sub_grade_id', $subGrade->id)
                ->where('year', $year)
                ->where('type', 2) // Final for Semester 2
                ->whereIn('subject_id', $semester2Subjects->pluck('subject_id')->toArray())
                ->with('subject:id,name')
                ->get()
                ->keyBy('subject_id');

            $semester1Data = [];
            $semester1Passed = 0;
            $semester1Failed = 0;

            foreach ($semester1Subjects as $semesterSubject) {
                $score = $semester1Scores->get($semesterSubject->subject_id);
                $totalScore = $score ? min($score->total, 100) : 0; // Cap at 100
                $isPassed = $totalScore >= 50;

                $semester1Data[] = [
                    'subject_id' => $semesterSubject->subject_id,
                    'subject_name' => $semesterSubject->subject->name,
                    'score' => $totalScore,
                    'is_passed' => $isPassed,
                ];

                if ($isPassed) {
                    $semester1Passed++;
                } else {
                    $semester1Failed++;
                }
            }

            $semester2Data = [];
            $semester2Passed = 0;
            $semester2Failed = 0;

            foreach ($semester2Subjects as $semesterSubject) {
                $score = $semester2Scores->get($semesterSubject->subject_id);
                $totalScore = $score ? min($score->total, 100) : 0; // Cap at 100
                $isPassed = $totalScore >= 50;

                $semester2Data[] = [
                    'subject_id' => $semesterSubject->subject_id,
                    'subject_name' => $semesterSubject->subject->name,
                    'score' => $totalScore,
                    'is_passed' => $isPassed,
                ];

                if ($isPassed) {
                    $semester2Passed++;
                } else {
                    $semester2Failed++;
                }
            }

            $totalSubjects = count($allSubjectIds);
            $totalPassed = $semester1Passed + $semester2Passed;
            $isFinalPassed = ($totalPassed === $totalSubjects && $totalSubjects === 11);

            if ($isFinalPassed) {
                $totalKamyab++;
            } else {
                $totalNakam++;
            }

            $results[] = [
                'student_result_id' => $studentResult->id,
                'student' => $student,
                'sub_grade' => $subGrade,
                'year' => $year,
                'semester1' => [
                    'subjects' => $semester1Data,
                    'passed_count' => $semester1Passed,
                    'failed_count' => $semester1Failed,
                    'total_subjects' => count($semester1Data),
                ],
                'semester2' => [
                    'subjects' => $semester2Data,
                    'passed_count' => $semester2Passed,
                    'failed_count' => $semester2Failed,
                    'total_subjects' => count($semester2Data),
                ],
                'final_result' => [
                    'is_passed' => $isFinalPassed,
                    'result_name' => $isFinalPassed ? 'کامیاب' : 'ناکام',
                    'total_subjects' => $totalSubjects,
                    'passed_subjects' => $totalPassed,
                    'failed_subjects' => $totalSubjects - $totalPassed,
                ],
            ];
        }

        $currentPage = $request->get('page', 1);
        $perPage = 350;
        $total = count($results);
        $items = array_slice($results, ($currentPage - 1) * $perPage, $perPage);

        $paginatedResults = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return inertia('Report/Grade9Report', [
            'results' => $paginatedResults,
            'subGrades' => $subGrades,
            'years' => $years,
            'countries' => $countries,
            'totalKamyab' => $totalKamyab,
            'totalNakam' => $totalNakam,
            'totalStudents' => $total,
        ]);
    }

    /**
     * Export Student Results Report to Excel
     */
    public function exportStudentResults(Request $request)
    {
        return Excel::download(
            new ExportStudentResultsReport($request->all()),
            'student-results-report.xlsx'
        );
    }

    /**
     * Export Subject Scores Report to Excel
     */
    public function exportSubjectScores(Request $request)
    {
        return Excel::download(
            new ExportSubjectScoresReport($request->all()),
            'subject-scores-report.xlsx'
        );
    }

    /**
     * Export Subject Statistics Report to Excel
     */
    public function exportSubjectStatistics(Request $request)
    {
        return Excel::download(
            new ExportSubjectStatisticsReport($request->all()),
            'subject-statistics-report.xlsx'
        );
    }

    /**
     * Export Grade 9 Report to Excel
     */
    public function exportGrade9(Request $request)
    {
        return Excel::download(
            new ExportGrade9Report($request->all()),
            'grade-9-report.xlsx'
        );
    }

    public function allStudentsSubjectScores(Request $request)
    {
        $grades = Grade::where('is_active', true)->get(['id', 'name']);
        $subGrades = SubGrade::whereIsActive(true)->get(['id', 'full_name', 'grade_id']);
        $years = Year::all(['id', 'name']);
        $countries = Country::where('is_active', true)->get(['id', 'name']);

        $query = Student::query()
            ->where('is_active', true)
            ->with([
                'subGrade:id,full_name,grade_id',
                'subGrade.grade:id,name',
                'country:id,name'
            ])
            ->select(['id', 'name', 'father_name', 'fa_name', 'fa_father_name', 'id_number', 'email', 'sub_grade_id', 'country_id']);

        if ($request->student_id) {
            $query->where('id_number', 'like', "%{$request->student_id}%");
        }

        if ($request->email) {
            $query->where('email', 'like', "%{$request->email}%");
        }

        if ($request->country_id) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->grade_id) {
            $gradeIds = is_array($request->grade_id) ? $request->grade_id : [$request->grade_id];
            $gradeIds = array_filter($gradeIds);
            if (!empty($gradeIds)) {
                $query->whereHas('subGrade', function ($q) use ($gradeIds) {
                    $q->whereIn('grade_id', $gradeIds);
                });
            }
        }

        if ($request->sub_grade_id) {
            $subGradeIds = is_array($request->sub_grade_id) ? $request->sub_grade_id : [$request->sub_grade_id];
            $subGradeIds = array_filter($subGradeIds);
            if (!empty($subGradeIds)) {
                $query->whereIn('sub_grade_id', $subGradeIds);
            }
        }

        $hasExplicitSearch = $request->filled('student_id') ||
                            $request->filled('email') ||
                            $request->filled('country_id') ||
                            $request->filled('grade_id') ||
                            $request->filled('sub_grade_id') ||
                            ($request->has('year') && $request->year !== '') ||
                            ($request->has('exam_type') && $request->exam_type !== '');

        if (!$hasExplicitSearch) {
            $paginatedResults = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                35,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return inertia('Report/AllStudentsSubjectScores', [
                'results' => $paginatedResults,
                'grades' => $grades,
                'subGrades' => $subGrades,
                'years' => $years,
                'countries' => $countries,
                'subjects' => collect(),
                'examType' => 'result',
            ]);
        }

        $students = $query->orderBy('name')->get();

        $results = [];
        $year = $request->year ?: date('Y');
        $examType = $request->exam_type ?: 'result'; // Default to result

        $scoreRelationship = match($examType) {
            'midterm' => 'middle',
            'final' => 'final',
            'result' => 'finalResult',
            default => 'finalResult',
        };

        $studentsByGrade = $students->groupBy(function ($student) {
            return $student->subGrade->grade_id;
        });

        foreach ($studentsByGrade as $gradeId => $gradeStudents) {
            $gradeSubjects = GradeSubject::with(['grade:id,name', 'subject:id,name,en_name'])
                ->where('grade_id', $gradeId)
                ->get();

            $allSubjects = $gradeSubjects->pluck('subject')->unique('id')->values();

            $studentIds = $gradeStudents->pluck('id')->toArray();

            $studentResults = StudentResult::whereIn('student_id', $studentIds)
                ->where('year', $year)
                ->get()
                ->keyBy('student_id');

            foreach ($gradeStudents as $student) {
                $where = [
                    'year' => $year,
                    'student_id' => $student->id,
                    'sub_grade_id' => $student->sub_grade_id,
                ];

                $subjectsWithScores = Subject::whereIn('id', $allSubjects->pluck('id'))
                    ->with([$scoreRelationship => function ($query) use ($where) {
                        $query->where($where);
                    }])
                    ->get();

                $studentScores = [];

                foreach ($allSubjects as $subject) {
                    $subjectWithScore = $subjectsWithScores->find($subject->id);
                    $score = $subjectWithScore ? $subjectWithScore->{$scoreRelationship} : null;

                    $studentScores[] = [
                        'subject_id' => $subject->id,
                        'subject_name' => $subject->en_name ?? $subject->name,
                        'score' => $score ? [
                            'total' => $score->total,
                            'is_passed' => $score->is_passed,
                        ] : null,
                    ];
                }

                $studentResult = $studentResults->get($student->id);
                $resultStatus = $studentResult ? $studentResult->result_name : null;

                $results[] = [
                    'student' => $student,
                    'subjects' => $studentScores,
                    'result_status' => $resultStatus,
                ];
            }
        }

        $currentPage = $request->get('page', 1);
        $perPage = 350;
        $total = count($results);
        $items = array_slice($results, ($currentPage - 1) * $perPage, $perPage);

        $paginatedResults = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $totalKamyab = 0;
        $totalMashroot = 0;
        $totalNakam = 0;

        foreach ($results as $result) {
            $status = $result['result_status'] ?? '';
            if (str_contains($status, 'کامیاب')) {
                $totalKamyab++;
            } elseif (str_contains($status, 'مشروط')) {
                $totalMashroot++;
            } elseif (str_contains($status, 'ناکام') || str_contains($status, 'تکرار')) {
                $totalNakam++;
            }
        }

        return inertia('Report/AllStudentsSubjectScores', [
            'results' => $paginatedResults,
            'grades' => $grades,
            'subGrades' => $subGrades,
            'years' => $years,
            'countries' => $countries,
            'subjects' => $allSubjects,
            'examType' => $examType,
            'totalKamyab' => $totalKamyab,
            'totalMashroot' => $totalMashroot,
            'totalNakam' => $totalNakam,
        ]);
    }

    public function exportAllStudentsSubjectScores(Request $request)
    {
        return Excel::download(
            new ExportAllStudentsSubjectScores($request->all()),
            'all-students-subject-scores-report.xlsx'
        );
    }
}

