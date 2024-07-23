<?php

namespace App\Http\Controllers;

use App\Exports\ExportAttendanceShoqa;
use App\Exports\ExportShoqa;
use App\Models\SubGrade;
use App\Models\Subject;
use App\Models\Year;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ShoqaController extends Controller
{
    public function index(Request $request)
    {
        $grades = SubGrade::whereIsActive(true)->get();
        $subjects = Subject::whereIsActive(true)->get();
        $years = Year::all(['id', 'name']);
        $types = [
            ['id' => 1, 'name' => 'Midterm Exam'],
            ['id' => 2, 'name' => 'Final Exam'],
            ['id' => 3, 'name' => 'Final'],
        ];

        return inertia('Shoqa/Index', ['subjects' => $subjects, 'examTypes' => $types, 'grades' => $grades, 'years' => $years]);
    }

    public function getShoqaAsExcel(Request $request)
    {
        if ($request->grade_id && $request->year && $request->subject_id && $request->type && $request->export_type) {
            $grade = SubGrade::with('grade')->whereId($request->grade_id)->first();
            $className = $grade?->grade?->number.$grade->level;
            $subjectName = Subject::whereId($request->subject_id)->value('name');

            if ($request->export_type == 'score') {

                $response = Excel::download(new ExportShoqa($request->all()), "shoqa.xlsx", \Maatwebsite\Excel\Excel::XLSX);
                ob_end_clean();

                return $response;
            }

            return Excel::download(new ExportAttendanceShoqa($request->all()), "ضوابط-حاضری-$subjectName-صنف-$className.xlsx");
        }
    }
}
