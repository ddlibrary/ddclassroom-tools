<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\SubGrade;
use App\Traits\ResultTypeTrait;
use Inertia\Inertia;

class HomeController extends Controller
{
    use ResultTypeTrait;

    public function index()
    {
        $year = 2024;

        $subGradeIds = Enrollment::where('year', $year)->groupBy('sub_grade_id')->pluck('sub_grade_id');

        $where = [
            'year' => $year,
        ];

        // $students = Student::with(['scores' => function($query){
        //     $query->where([
        //         'type' => 1,
        //     ])->where('total', '<', 16);
        // }, 'scores.subject', 'subGrade'])->withCount(['scores' => function($query){
        //     $query->where([
        //         'type' => 1,
        //     ])->where('total', '<', 16);
        // }])->get();

        // $students = $students->where('scores_count', '=', 1);

        // echo "<table style='border-collapse:collapse'>";
        // echo "<tr>
        // <th style='padding:2px 10px;border:1px solid #000'> #</th>
        // <th style='padding:2px 10px;border:1px solid #000'>نام کامل</th>
        // <th style='padding:2px 10px;border:1px solid #000'>صنف</th>
        // <th style='padding:2px 10px;border:1px solid #000'>مضمون</th>
        // <th style='padding:2px 10px;border:1px solid #000'>نمره</th>
        // </tr>";
        // $i=1;
        // foreach($students as $student){
        //     echo "<tr>
        //     <td style='padding:2px 10px;border:1px solid #000'>$i</td>
        //     <td style='padding:2px 10px;border:1px solid #000'>$student->fa_name $student->fa_father_name</td>
        //     <td style='padding:2px 10px;border:1px solid #000'>".$student->subGrade->name."</td>
        //     <td style='padding:2px 10px;border:1px solid #000'>".$student->scores[0]->subject->name."</td>
        //     <td style='padding:2px 10px;border:1px solid #000'>".$student->scores[0]->total."</td>
        //     </tr>";
        //     $i++;

        // }
        // echo "</table>";

        // die;

        // $students = StudentResult::with('student')->where('middle_subject_passed', 10)->get();

        // foreach ($students as $student) {
        //     echo Score::where([
        //         'student_id' => $student->student_id,
        //         'sub_grade_id' => $student->sub_grade_id,
        //         'year' => $student->year,
        //         'type' => 1
        //     ])
        //         ->where('total', '<', 16)
        //         ->count();
        //         echo "<br>";
        // }

        // die;
        // return $students;

        $subGrades = SubGrade::with(['grade:id,name'])
            ->whereIn('id', $subGradeIds)

            ->withCount([
                'results as total_students' => function ($query) use ($where) {
                    $query->where($where);
                },
                'results as passed_count' => function ($query) use ($where) {
                    $query->where($where)->where('result_name', 'کامیاب');
                },
                'results as try_again' => function ($query) use ($where) {
                    $query->where($where)->where('result_name', 'تکرار بیشتر');
                },
                'results as failed' => function ($query) use ($where) {
                    $query->where($where)->where('result_name', 'تکرار صنف')->orWhere('result_name', 'ناکام');
                },
            ])

            ->get();

        $generalView = [
            'total_students' => StudentResult::where($where)->count(),
            'total_passed' => StudentResult::where($where)->where('result_name', 'کامیاب')->count(),
            'total_conditional' => StudentResult::where($where)->where('result_name', 'تکرار بیشتر')->count(),
            'total_failed' => StudentResult::where($where)->where('result_name', 'تکرار صنف')->orWhere('result_name', 'ناکام')->count(),
        ];

        return Inertia::render('Dashboard', ['subGrades' => $subGrades, 'generalView' => $generalView]);
    }
}
