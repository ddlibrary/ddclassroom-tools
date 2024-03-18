<?php

namespace App\Exports;

use App\Models\StudentResult;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportStudentResult implements FromView, ShouldAutoSize
{
    public function __construct(private array $data)
    {
    }

    public function view(): View
    {
        $year = $this->data['year'];
        $gradeId = $this->data['grade_id'];
        $results = StudentResult::with(['student:id,name,father_name,uuid', 'middleResult:id,name', 'finalResult:id,name', 'result:id,name', 'teacher:id,name'])
            ->where('year', $year)
            ->where(function($query) use ($gradeId){
                if($gradeId){
                    $query->where('sub_grade_id', $gradeId);
                }
            })
            ->with([
                'subGrade' => function ($query) use ($year) {
                    $query->where('year', $year)->with([
                        'responsible' => function ($query) use ($year) {
                            $query->where('year', $year)->with(['teacher:id,name']);
                        },
                    ]);
                },
            ])
            ->get();

        return view('student-results.student-results-as-excel', [
            'results' => $results,
        ]);
    }
}
