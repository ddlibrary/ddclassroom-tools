<?php

namespace App\Exports;

use App\Models\Score;
use App\Models\StudentResult;
use App\Models\SubGradeSubjectSemester;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportGrade9Report implements FromView, ShouldAutoSize
{
    public function __construct(private array $filters) {}

    public function view(): View
    {
        $request = new Request($this->filters);

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
                ->where('type', 1)
                ->whereIn('subject_id', $semester1Subjects->pluck('subject_id')->toArray())
                ->with('subject:id,name')
                ->get()
                ->keyBy('subject_id');

            $semester2Scores = Score::where('student_id', $student->id)
                ->where('sub_grade_id', $subGrade->id)
                ->where('year', $year)
                ->where('type', 2)
                ->whereIn('subject_id', $semester2Subjects->pluck('subject_id')->toArray())
                ->with('subject:id,name')
                ->get()
                ->keyBy('subject_id');

            $semester1Data = [];
            $semester1Passed = 0;
            $semester1Failed = 0;

            foreach ($semester1Subjects as $semesterSubject) {
                $score = $semester1Scores->get($semesterSubject->subject_id);
                $totalScore = $score ? min($score->total, 100) : 0;
                $isPassed = $totalScore >= 50;

                $semester1Data[] = [
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
                $totalScore = $score ? min($score->total, 100) : 0;
                $isPassed = $totalScore >= 50;

                $semester2Data[] = [
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

            $results[] = [
                'student' => $student,
                'sub_grade' => $subGrade,
                'year' => $year,
                'semester1' => [
                    'subjects' => $semester1Data,
                    'passed_count' => $semester1Passed,
                    'failed_count' => $semester1Failed,
                ],
                'semester2' => [
                    'subjects' => $semester2Data,
                    'passed_count' => $semester2Passed,
                    'failed_count' => $semester2Failed,
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

        return view('exports.grade-9-report', [
            'results' => $results,
        ]);
    }
}


