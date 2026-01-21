<?php

namespace App\Exports;

use App\Http\Controllers\ReportController;
use App\Models\StudentResult;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportStudentResultsReport implements FromView, ShouldAutoSize
{
    public function __construct(private array $filters) {}

    public function view(): View
    {
        $request = new Request($this->filters);
        $controller = new ReportController();
        
        // Get the same data as the report method
        $query = StudentResult::query()->with([
            'student:id,name,father_name,country_id,fa_name,fa_father_name,uuid,id_number,email',
            'middleResult:id,name',
            'subGrade:id,full_name,grade_id',
            'subGrade.grade:id,name',
            'teacher:id,name'
        ])->whereHas('student', function ($q) {
            $q->where('is_active', true);
        });

        // Apply filters using the same logic as the controller
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

        $results = $query->orderByDesc('id')->get();

        return view('exports.student-results-report', [
            'results' => $results,
        ]);
    }
}


