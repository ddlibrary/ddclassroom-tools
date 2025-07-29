<?php

namespace App\Exports;

use App\Models\StudentResult;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportStudentResult implements FromView, ShouldAutoSize
{
    public function __construct(private array $data) {}

    public function view(): View
    {
        $year = $this->data['year'];
        $countryId = $this->data['country_id'];
        $gradeId = $this->data['grade_id'];
        $query = StudentResult::query()->with(['student:id,name,country_id,father_name,uuid,id_number,fa_name,fa_father_name,email,password', 'student.country:id,name', 'middleResult:id,name', 'finalResult:id,name', 'result:id,name', 'teacher:id,name']);
        $query
            ->where('year', $year)
            ->where(function ($query) use ($gradeId) {
                if ($gradeId) {
                    $query->where('sub_grade_id', $gradeId);
                }
            })
            ->with([
                'subGrade' => function ($query) use ($year) {
                  //  $query->where('year', $year)->with([
                    $query->with([
                        'responsible' => function ($query) use ($year) {
                            $query->where('year', $year)->with(['teacher:id,name']);
                        },
                    ]);
                },
            ]);
        if ($countryId) {
            $query->where(function ($query) use ($countryId) {
                $query->whereHas('student', function ($query) use ($countryId) {
                    $query->where('country_id', $countryId);
                });
            });
        }
        $results = $query->get();

        return view('student-results.student-results-as-excel', [
            'results' => $results,
        ]);
    }
}
