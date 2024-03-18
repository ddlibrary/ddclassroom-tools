<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
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

        $subGradeIds = Enrollment::where('year', $year)
            ->groupBy('sub_grade_id')
            ->pluck('sub_grade_id');

        $where = [
            'year' => $year,
        ];

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

    public function clear()
    {
        $studentResults = StudentResult::all();

        foreach ($studentResults as $studentResult) {
            $result = $this->resultType($studentResult->result_name, 'final');
            $studentResult->update([
                'result_type_id' => $result,
            ]);
        }
    }
}
