<?php

namespace App\Http\Controllers;

use App\Enums\UserTypeEnum;
use App\Http\Requests\Score\CreateMultipleStudentsScoreRequest;
use App\Http\Requests\Score\CreateScoreRequest;
use App\Http\Requests\ScoreRequest;
use App\Imports\StudentScoreImport;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\SubGrade;
use App\Models\Subject;
use App\Models\User;
use App\Models\Year;
use App\Traits\ResultNameTrait;
use App\Traits\ResultTrait;
use App\Traits\ResultTypeTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ScoreController extends Controller
{
    use ResultTrait, ResultNameTrait, ResultTypeTrait;

    public function index(Request $request)
    {
        $query = Score::query()->with(['student:id,name,fa_name,father_name,fa_father_name,uuid', 'subject:id,name', 'subGrade:id,name', 'teacher:id,name']);

        if ($request->search) {
            $name = $request->search;
            $query->where(function ($query) use ($name) {
                $query->whereHas('student', function ($query) use ($name) {
                    $query
                        ->where('name', 'like', "%$name%")
                        ->orWhere('username', 'like', "%$name%")
                        ->orWhere('father_name', 'like', "%$name%")
                        ->orWhere('email', 'like', "$name%");
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

        return inertia('Score/Create', ['subjects' => $subjects, 'teachers' => $teachers, 'grades' => $grades, 'years' => $years]);
    }

    public function store(Request $request)
    {
        dd($request->all());
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

            $middle = Score::where($where)
                ->where('type', 1)
                ->sum('total');
            $final = Score::where($where)
                ->where('type', 2)
                ->sum('total');
            $total = Score::where($where)
                ->where('type', 3)
                ->sum('total');

            $middlePassed = Score::where($where)
                ->where('type', 1)
                ->where('total', '>=', 16)
                ->count();
            $finalPassed = Score::where($where)
                ->where('type', 2)
                ->where('total', '>=', 24)
                ->count();
            $totalPassed = Score::where($where)
                ->where('type', 3)
                ->where('total', '>=', 40)
                ->count();

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

            if($studentResult->middle == $studentResult->final){
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
