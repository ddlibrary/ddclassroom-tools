<?php

namespace App\Http\Controllers;

use App\Enums\SubjectMinScoreEnum;
use App\Enums\UserTypeEnum;
use App\Http\Requests\Score\CreateMultipleStudentsScoreRequest;
use App\Http\Requests\Score\MultipleStudentRequest;
use App\Http\Requests\ScoreRequest;
use App\Imports\StudentScoreImport;
use App\Models\Score;
use App\Models\SubGrade;
use App\Models\Subject;
use App\Models\User;
use App\Models\Year;
use App\Services\SubjectScore;
use App\Traits\StoreStudentScoreTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ScoreController extends Controller
{
    use StoreStudentScoreTrait;

    public function index(Request $request)
    {
        $query = Score::query()->with(['student:id,name,fa_name,father_name,fa_father_name,uuid', 'subject:id,name', 'subGrade:id,name', 'teacher:id,name']);

        if ($request->search) {
            $name = $request->search;
            $query->where(function ($query) use ($name) {
                $query->whereHas('student', function ($query) use ($name) {
                    $query->whereAny(['name', 'username', 'fa_name', 'fa_father_name', 'father_name', 'email', 'id_number'], 'like', "%$name%");
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
            if (is_array($scores) && !empty($scores)) {
                $grade = SubGrade::find($request->sub_grade_id)->grade;
                foreach ($scores as $score) {
                    $this->storeScore($request, $score, $grade);
                }

                DB::commit();
            }
        } catch (Exception $exception) {
            DB::rollback();

            throw $exception;
        }

        return back();
    }

    public function edit($id)
    {
        $score = Score::with(['student:id,name,father_name', 'subGrade:id,name', 'subject'])->find($id);
        $grades = SubGrade::whereIsActive(true)->get();

        return inertia('Score/Edit', ['score' => $score, 'grades' => $grades]);
    }

    public function update(ScoreRequest $request, Score $score)
    {
        // dd($request->all(), $score);
        DB::beginTransaction();

        try {
            $scoreWhere = [
                'year' => $score->year,
                'sub_grade_id' => $score->sub_grade_id,
                'subject_id' => $score->subject_id,
                'student_id' => $score->student_id,
            ];

            $studentResultWhere = [
                'year' => $score->year,
                'sub_grade_id' => $score->sub_grade_id,
                'student_id' => $score->student_id,
            ];

            $grade = $score->subGrade->grade;


            // Update Final Score
            // $finalScore = Score::where([
            //     'subject_id' => $score->subject_id,
            //     'type' => 3,
            // ])
            //     ->where($scoreWhere)
            //     ->first();

            // $finalScore->update([
            //     'total' => $finalScore->total - $score->total + $request->total,
            //     'written' => $finalScore->written - $score->written + $request->written,
            //     'activity' => $finalScore->activity - $score->activity + $request->activity,
            //     'verbal' => $finalScore->verbal - $score->verbal + $request->verbal,
            //     'attendance' => $finalScore->attendance - $score->attendance + $request->attendance,
            //     'homework' => $finalScore->homework - $score->homework + $request->homework,
            //     'evaluation' => $finalScore->evaluation - $score->evaluation + $request->evaluation,
            //     'is_passed' => $finalScore->total - $score->total + $request->total >= $maxAmount ? true : false,
            // ]);
            $type = $score->type;
            $minAmount = $type == 1 ? SubjectMinScoreEnum::Middle->value : SubjectMinScoreEnum::Final->value;

            $score->update([
                'total' => $request->total,
                'written' => $request->written,
                'activity' => $request->activity,
                'verbal' => $request->verbal,
                'attendance' => $request->attendance,
                'homework' => $request->homework,
                'evaluation' => $request->evaluation,
                'is_passed' => $request->total >= $minAmount ? true : false,
            ]);

        //Score::where($scoreWhere)->where('type', $type)->first();
        $secondScore = Score::where($scoreWhere)
            ->where('type', $type == 1 ? 2 : 1)
            ->first();

        // Update final score
        Score::where($scoreWhere)
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

            $this->updateStudentResult($grade, $studentResultWhere, $type);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();

            throw $exception;
        }

        return redirect('student-score');
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
