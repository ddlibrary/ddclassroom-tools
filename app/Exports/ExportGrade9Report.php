<?php

namespace App\Exports;

use App\Models\Score;
use App\Models\StudentResult;
use App\Models\SubGrade;
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

        $grade9SubGradeIds = SubGrade::whereHas('grade', function ($q) {
            $q->where('id', 9)->where('is_semester_based', true);
        })
            ->whereIsActive(true)
            ->pluck('id')
            ->toArray();

        $query = StudentResult::query()
            ->with(['student:id,name,father_name,country_id,id_number,email', 'subGrade:id,full_name'])
            ->whereIn('sub_grade_id', $grade9SubGradeIds)
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
            $subGradeIds = array_filter((array) $request->sub_grade_id);
            if (!empty($subGradeIds)) {
                $query->whereIn('sub_grade_id', $subGradeIds);
            }
        }
        if ($request->year) {
            $query->where('year', $request->year);
        }

        $studentResults = $query->orderByDesc('id')->get();

        if ($studentResults->isEmpty()) {
            return view('exports.grade-9-report', ['results' => [], 'subjects' => $this->getGrade9Subjects($grade9SubGradeIds, $request->year)]);
        }

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

            foreach ($semester1Subjects as $semesterSubject) {
                $score = $semester1Scores->get($semesterSubject->subject_id);
                $totalScore = $score ? min($score->total, 100) : 0;
                $isPassed = $totalScore >= 50;
                if ($isPassed) {
                    $semester1Passed++;
                }
                $semester1Data[] = [
                    'subject_id' => $semesterSubject->subject_id,
                    'subject_name' => $semesterSubject->subject->name,
                    'score' => $totalScore,
                    'is_passed' => $isPassed,
                ];
            }

            $semester2Data = [];
            $semester2Passed = 0;

            foreach ($semester2Subjects as $semesterSubject) {
                $score = $semester2Scores->get($semesterSubject->subject_id);
                $totalScore = $score ? min($score->total, 100) : 0;
                $isPassed = $totalScore >= 50;
                if ($isPassed) {
                    $semester2Passed++;
                }
                $semester2Data[] = [
                    'subject_id' => $semesterSubject->subject_id,
                    'subject_name' => $semesterSubject->subject->name,
                    'score' => $totalScore,
                    'is_passed' => $isPassed,
                ];
            }

            $allSubjectsMap = [];
            foreach (array_merge($semester1Data, $semester2Data) as $subjectData) {
                $allSubjectsMap[$subjectData['subject_id']] = $subjectData;
            }

            $totalSubjects = count($allSubjectsMap);
            $totalPassed = $semester1Passed + $semester2Passed;
            $isFinalPassed = $totalPassed === $totalSubjects && $totalSubjects === 11;

            $results[] = [
                'student' => $student,
                'sub_grade' => $subGrade,
                'year' => $year,
                'all_subjects' => $allSubjectsMap,
                'final_result' => [
                    'is_passed' => $isFinalPassed,
                    'result_name' => $isFinalPassed ? 'کامیاب' : 'ناکام',
                    'total_subjects' => $totalSubjects,
                    'passed_subjects' => $totalPassed,
                    'failed_subjects' => $totalSubjects - $totalPassed,
                ],
            ];
        }

        if ($request->subject_id) {
            $results = array_values(array_filter($results, fn ($r) => isset($r['all_subjects'][$request->subject_id])));
        }
        if ($request->result_status === 'passed') {
            $results = array_values(array_filter($results, fn ($r) => $r['final_result']['is_passed']));
        } elseif ($request->result_status === 'failed') {
            $results = array_values(array_filter($results, fn ($r) => !$r['final_result']['is_passed']));
        }

        return view('exports.grade-9-report', [
            'results' => $results,
            'subjects' => $this->getGrade9Subjects($grade9SubGradeIds, $request->year),
        ]);
    }

    private function getGrade9Subjects(array $grade9SubGradeIds, ?string $year): array
    {
        if (empty($grade9SubGradeIds)) {
            return [];
        }
        $yearForSubjects = $year ?: (string) now()->year;
        $subjects = SubGradeSubjectSemester::whereIn('sub_grade_id', $grade9SubGradeIds)
            ->where('year', $yearForSubjects)
            ->whereIn('semester', [1, 2])
            ->with('subject:id,name')
            ->orderBy('semester')
            ->orderBy('subject_id')
            ->get()
            ->unique('subject_id')
            ->values()
            ->map(fn ($s) => ['id' => $s->subject_id, 'name' => $s->subject->name])
            ->toArray();

        return $subjects;
    }
}
