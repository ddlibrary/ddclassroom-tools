<?php

namespace App\Http\Controllers;

use App\Enums\ResultCardEnum;
use App\Enums\SubjectMinScoreEnum;
use App\Enums\UserTypeEnum;
use App\Http\Requests\Score\CreateMultipleStudentsScoreRequest;
use App\Http\Requests\Score\CreateStudentsScoreRequest;
use App\Http\Requests\Score\MultipleStudentRequest;
use App\Http\Requests\ScoreRequest;
use App\Imports\StudentScoreImport;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\SubGrade;
use App\Models\Subject;
use App\Models\User;
use App\Models\Year;
use App\Services\SubjectScore;
use App\Traits\ResultNameTrait;
use App\Traits\ResultTrait;
use App\Traits\ResultTypeTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ScoreController extends Controller
{
    use ResultNameTrait, ResultTrait, ResultTypeTrait;

    public function index(Request $request)
    {
        $query = Score::query()->with(['student:id,name,fa_name,father_name,fa_father_name,uuid', 'subject:id,name', 'subGrade:id,name', 'teacher:id,name']);

        if ($request->search) {
            $name = $request->search;
            $query->where(function ($query) use ($name) {
                $query->whereHas('student', function ($query) use ($name) {
                    $query->whereAny(['name', 'username','fa_name','fa_father_name', 'father_name', 'email', 'id_number'], 'like', "%$name%");
                });
            });
        }

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->grade_id) {
            $query->where('sub_grade_id', $request->grade_id);
        }

        $scores = $query->paginate(300);
        $grades = SubGrade::whereIsActive(true)->get();
        $subjects = Subject::all(['id', 'name']);
        $years = Year::all(['id', 'name']);

        return inertia('Score/Index', ['subjects' => $subjects, 'scores' => $scores, 'grades' => $grades, 'years' => $years]);
    }

    public function create()
    {
        $teachers = User::whereUserTypeId(UserTypeEnum::Teacher)->get();
        $grades = SubGrade::whereIsActive(true)->get();
        $subjects = Subject::all(['id', 'name']);
        $years = Year::all(['id', 'name']);
        $types = [['id' => 1, 'name' => 'Midterm Exam'], ['id' => 2, 'name' => 'Final Exam']];

        $midterScores = new SubjectScore(1);
        $finalScores = new SubjectScore(2);

        $midtermSuccessScore = SubjectMinScoreEnum::Middle->value;
        $finalSuccessScore = SubjectMinScoreEnum::Final->value;

        return inertia('Score/Create', [
            'subjects' => $subjects,
            'types' => $types,
            'teachers' => $teachers,
            'grades' => $grades,
            'years' => $years,
            'midtermScores' => $midterScores->getAll(),
            'finalScores' => $finalScores->getAll(),
            'midtermSuccessScore' => $midtermSuccessScore,
            'finalSuccessScore' => $finalSuccessScore,
        ]);
    }

    public function createScores()
    {
        $teachers = User::whereUserTypeId(UserTypeEnum::Teacher)->get();
        $grades = SubGrade::whereIsActive(true)->get();
        $subjects = Subject::all(['id', 'name']);
        $years = Year::all(['id', 'name']);
        $types = [['id' => 1, 'name' => 'Midterm Exam'], ['id' => 2, 'name' => 'Final Exam']];

        $midterScores = new SubjectScore(1);
        $finalScores = new SubjectScore(2);

        $midtermSuccessScore = SubjectMinScoreEnum::Middle->value;
        $finalSuccessScore = SubjectMinScoreEnum::Final->value;

        return inertia('Score/CreateScores', [
            'subjects' => $subjects,
            'types' => $types,
            'teachers' => $teachers,
            'grades' => $grades,
            'years' => $years,
            'midtermScores' => $midterScores->getAll(),
            'finalScores' => $finalScores->getAll(),
            'midtermSuccessScore' => $midtermSuccessScore,
            'finalSuccessScore' => $finalSuccessScore,
        ]);
    }

    public function store(MultipleStudentRequest $request)
    {
        $scores = json_decode($request->studentScores);

        DB::beginTransaction();

        try {
        $teacherId = $request->teacher_id;
        if (is_array($scores) && !empty($scores)) {
            foreach ($scores as $score) {
                $student = Student::whereIdNumber($score->moodle_id)->first();

                $where = [
                    'year' => $request->year,
                    'student_id' => $student->id,
                    'subject_id' => $request->subject_id,
                    'sub_grade_id' => $request->sub_grade_id,
                ];

                if (Score::where($where)->doesntExist()) {
                    for ($i = 1; $i < 4; $i++) {
                        Score::updateOrCreate(
                            [
                                'year' => $request->year,
                                'student_id' => $student->id,
                                'subject_id' => $request->subject_id,
                                'sub_grade_id' => $request->sub_grade_id,
                                'type' => $i,
                            ],
                            [],
                        );
                    }
                }

                $studentResultWhere = [
                    'year' => request()->year,
                    'student_id' => $student->id,
                    'sub_grade_id' => $student->sub_grade_id,
                ];
                if (StudentResult::where($studentResultWhere)->doesntExist()) {
                    StudentResult::create($studentResultWhere);
                }

                $type = $request->type_id;

                Score::where($where)->where('type','<>', $type == 1 ? 2 : 1 )->update([
                    'written' => null,
                    'verbal' => null,
                    'attendance' => null,
                    'activity' => null,
                    'homework' => null,
                    'evaluation' => null,
                    'total' => null,
                    'is_passed' => false,
                    'teacher_id' => $teacherId,
                    'user_id' => auth()->id(),
                ]);


                $minAmount = $type == 1 ? SubjectMinScoreEnum::Middle->value : SubjectMinScoreEnum::Final->value;

                Score::where($where)
                    ->where('type', $type)
                    ->update([
                        'written' => $score->written,
                        'verbal' => $score->oral,
                        'attendance' => $score->attendance ? $score->attendance : null,
                        'activity' => $score->activity,
                        'homework' => $score->homework,
                        'evaluation' => $score->evaluation,
                        'total' => $score->total,
                        'is_passed' => $score->total >= $minAmount ? true : false,
                    ]);

                //Score::where($where)->where('type', $type)->first();
                $secondScore = Score::where($where)
                    ->where('type', $type == 1 ? 2 : 1)
                    ->first();

                // Update final score
                Score::where($where)
                    ->where('type', 3)
                    ->update([
                        'written' => (float) $secondScore->written + $score->written,
                        'verbal' => (float) $secondScore->verbal + $score->oral,
                        'attendance' => (float) $secondScore->attendance + $score->attendance,
                        'activity' => (float) $secondScore->activity + $score->activity,
                        'homework' => (float) $secondScore->homework + $score->homework,
                        'evaluation' => (float) $secondScore->evaluation + $score->evaluation,
                        'total' => (float) $secondScore->total + $score->total,
                        'is_passed' => (float) $secondScore->total + $score->total >= SubjectMinScoreEnum::Success->value ? true : false,
                    ]);

                $studentResult = StudentResult::where($studentResultWhere)->first();

                $firstTerm = Score::where($studentResultWhere)->where('type', 1)->sum('total');
                $secondTerm = Score::where($studentResultWhere)->where('type', 2)->sum('total');
                $final = $firstTerm + $secondTerm;

                $firstTermSubjectPassedCount = Score::where($studentResultWhere)->where(['type' => 1, 'is_passed' => true])->count();
                $secondTermSubjectPassedCount = Score::where($studentResultWhere)->where(['type' => 2, 'is_passed' => true])->count();
                $finalSubjectPassedCount = Score::where($studentResultWhere)->where(['type' => 3, 'is_passed' => true])->count();

                $grade = $student->subGrade->grade;
                $middleMaxScore = $grade->middle_max_number;
                $finalMaxScore = $grade->final_max_number;
                $totalScores = $middleMaxScore + $finalMaxScore;

                $studentResult->update([
                    'middle' => $firstTerm,
                    'final' => $secondTerm,
                    'total' => $final,

                    'middle_percentage' => ($firstTerm * 100) / $middleMaxScore,
                    'final_percentage' => ($secondTerm * 100) / $finalMaxScore,
                    'percentage' => ($final * 100) / $totalScores,

                    'middle_result_id' => $this->result(($firstTerm * 100) / $middleMaxScore),
                    'final_result_id' => $this->result(($secondTerm * 100) / $finalMaxScore),
                    'result_id' => $this->result(($final * 100) / $totalScores),

                    'middle_subject_passed' => $firstTermSubjectPassedCount,
                    'final_subject_passed' => $secondTermSubjectPassedCount,
                    'subject_passed' => $finalSubjectPassedCount,
                ]);

                if ($type == 1) {
                    $studentResult->update([
                        'middle_result_name' => $this->resultStatus($grade, $studentResult, $type),
                    ]);
                } else {
                    $studentResult->update([
                        'final_result_name' => $this->resultStatus($grade, $studentResult, $type),
                        'result_name' => $this->finalResult($grade, $studentResult),
                    ]);
                }


            }

            DB::commit();
        }
        else {
            DB::rollback();
            }
        } catch (Exception $exception) {
            DB::rollback();

            throw $exception;
        }

         return back();
    }

    private function result($percentage)
    {
        if ($percentage >= 90) {
            return 1;
        } elseif ($percentage >= 75) {
            return 2;
        } elseif ($percentage >= 60) {
            return 3;
        } elseif ($percentage >= 50) {
            return 4;
        }

        return 5;
    }

    private function resultStatus($grade, $studentResult, $type)
    {
        if ($type == 2) {
            if ($grade->total_subjects > ($studentResult->final_subject_passed + 3) || $studentResult->final_result_id == 5) {
                return ResultCardEnum::Repeat->value;
            } elseif ($grade->total_subjects > ($studentResult->final_subject_passed) && $studentResult->final_result_id != 5) {
                return ResultCardEnum::TrayAgain->value;
            }
        } else {
            if ($grade->total_subjects > $studentResult->middle_subject_passed || $studentResult->middle_result_id == 5) {
                return 'ناکام';
            }
        }

        return 'کامیاب';
    }

    private function finalResult($grade, $studentResult)
    {
        if ($grade->total_subjects > ($studentResult->subject_passed + 3) || $studentResult->result_id == 5) {
            return ResultCardEnum::Repeat->value;
        } elseif ($grade->total_subjects > ($studentResult->subject_passed) && $studentResult->result_id != 5) {
            return ResultCardEnum::TrayAgain->value;
        }

        return 'کامیاب';
    }

    public function edit($id)
    {
        $score = Score::with(['student:id,name,father_name', 'subGrade:id,name', 'subject'])->find($id);
        $grades = SubGrade::whereIsActive(true)->get();

        return inertia('Score/Edit', ['score' => $score, 'grades' => $grades]);
    }

    public function update(ScoreRequest $request, Score $score)
    {
        DB::beginTransaction();

        try {
            $prevTotalScore = $score->total;
            $diff = $prevTotalScore - $request->total;
            if ($score->type == 1) {
            } elseif ($score->type == 2) {
            }
            $maxAmount = $request->type == 1 ? 16 : 24;

            $grade = $score->subGrade->grade;
            $gradeTotalSubject = $grade->total_subjects;

            $totalMidtermScore = $gradeTotalSubject * 40;
            $totalFinalScore = $gradeTotalSubject * 60;
            $totalScore = $gradeTotalSubject * 100;

            $where = [
                'year' => $score->year,
                'sub_grade_id' => $score->sub_grade_id,
                'student_id' => $score->student_id,
            ];
            // Update Final Score
            $finalScore = Score::where([
                'subject_id' => $score->subject_id,
                'type' => 3,
            ])
                ->where($where)
                ->first();

            $finalScore->update([
                'total' => $finalScore->total - $score->total + $request->total,
                'written' => $finalScore->written - $score->written + $request->written,
                'activity' => $finalScore->activity - $score->activity + $request->activity,
                'verbal' => $finalScore->verbal - $score->verbal + $request->verbal,
                'attendance' => $finalScore->attendance - $score->attendance + $request->attendance,
                'homework' => $finalScore->homework - $score->homework + $request->homework,
                'evaluation' => $finalScore->evaluation - $score->evaluation + $request->evaluation,
                'is_passed' => $finalScore->total - $score->total + $request->total >= $maxAmount ? true : false,
            ]);

            // Update Current Exam
            $examData = [
                'total' => $request->total,
                'written' => $request->written,
                'activity' => $request->activity,
                'verbal' => $request->verbal,
                'attendance' => $request->attendance,
                'homework' => $request->homework,
                'evaluation' => $request->evaluation,
                'is_passed' => $request->total >= $maxAmount ? true : false,
            ];

            $this->updateScore($score, $examData);

            $studentResult = StudentResult::where($where)->first();

            $middle = Score::where($where)->where('type', 1)->sum('total');
            $final = Score::where($where)->where('type', 2)->sum('total');
            $total = Score::where($where)->where('type', 3)->sum('total');

            $middlePassed = Score::where($where)->where('type', 1)->where('total', '>=', 16)->count();
            $finalPassed = Score::where($where)->where('type', 2)->where('total', '>=', 24)->count();
            $totalPassed = Score::where($where)->where('type', 3)->where('total', '>=', 40)->count();

            $middlePercentage = $this->getScorePercentage($middle, $totalMidtermScore);
            $finalPercentage = $this->getScorePercentage($final, $totalFinalScore);
            $percentage = $this->getScorePercentage($total, $totalScore);

            $studentResult->update([
                'middle' => $middle,
                'final' => $final,
                'total' => $total,
                'middle_percentage' => $middlePercentage,
                'final_percentage' => $finalPercentage,
                'percentage' => $percentage,
                'middle_result_id' => $this->result($middlePercentage),
                'final_result_id' => $this->result($finalPercentage),
                'result_id' => $this->result($percentage),
                'middle_subject_passed' => $middlePassed,
                'final_subject_passed' => $finalPassed,
                'subject_passed' => $totalPassed,
            ]);

            $middleResultName = $this->resultStatus($grade, $studentResult, 1);
            $finalResultName = $this->resultStatus($grade, $studentResult, 2);
            $resultName = $this->resultStatus($grade, $studentResult, 3);

            $studentResultName = $middleResultName;
            $studentExam = 'midterm';

            if ($studentResult->middle == $studentResult->final) {
                $studentResultName = $resultName;
                $studentExam = 'final';
            }

            $studentResult->update([
                'middle_result_name' => $middleResultName,
                'final_result_name' => $finalResultName,
                'result_name' => $resultName,
                'result_type_id' => $this->resultType($studentResultName, $studentExam),
            ]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();

            throw $exception;
        }

        return redirect('student-score');
    }

    private function getScorePercentage($achievedScore, $totalScore)
    {
        return ($achievedScore * 100) / $totalScore;
    }

    private function updateScore($score, $data)
    {
        $score->update($data);
    }

    public function createMultipleStudentScores()
    {
        $grades = SubGrade::whereIsActive(true)->get();
        $years = Year::all(['id', 'name']);
        $subjects = Subject::all(['id', 'name']);
        $teachers = User::whereUserTypeId(UserTypeEnum::Teacher)->get();

        return inertia('Score/CreateMultipleStudentsScore', [
            'teachers' => $teachers,
            'subjects' => $subjects,
            'grades' => $grades,
            'years' => $years,
        ]);
    }

    public function storeMultipleStudentsScore(CreateMultipleStudentsScoreRequest $request)
    {
        Excel::import(new StudentScoreImport(), $request->file);
    }
}
