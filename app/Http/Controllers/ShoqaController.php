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
            ['id' => 1, 'name' => 'Middle Exam'],
            ['id' => 2, 'name' => 'Final Exam'],
            ['id' => 3, 'name' => 'Final'],
        ];

        return inertia('Shoqa/Index', ['subjects' => $subjects, 'examTypes' => $types, 'grades' => $grades, 'years' => $years]);
    }

    public function getShoqaAsExcel(Request $request)
    {
        if ($request->grade_id && $request->year && $request->subject_id && $request->type && $request->export_type) {
            if ($request->export_type == 'score') {

                return Excel::download(new ExportShoqa($request->all()), 'student-result.xlsx');
            }

            return Excel::download(new ExportAttendanceShoqa($request->all()), 'student-attendance-result.xlsx');
        }
    }
}
