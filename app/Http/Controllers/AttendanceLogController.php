<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendance\CreateMultipleAttendanceLogRequest;
use App\Imports\StudentAttendanceLogImport;
use App\Models\AttendanceLog;
use App\Models\Month;
use App\Models\SubGrade;
use App\Models\Subject;
use App\Models\Year;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceLog::query()->with(['student:id,fa_name,fa_father_name,uuid,id_number', 'subGrade:id,full_name', 'month:id,name', 'subject:id,en_name']);

        if ($request->search) {
            $name = $request->search;
            $query->where(function ($query) use ($name) {
                $query->whereHas('student', function ($query) use ($name) {
                    $query->whereAny(['name', 'username', 'father_name', 'fa_name', 'fa_father_name', 'email', 'id_number'], 'like', "%$name%");
                });
            });
        }
        if ($request->grade_id) {
            $query->where('sub_grade_id', $request->grade_id);
        }

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->year) {
            $query->where('year', $request->year);
        }

        if ($request->month_id) {
            $query->where('month_id', $request->month_id);
        }

        $scores = $query->paginate(350)->appends(request()->query());
        $grades = SubGrade::whereIsActive(true)->get();
        $years = Year::all(['id', 'name']);
        $months = Month::all(['id', 'name']);
        $subjects = Subject::all(['id', 'name']);

        return inertia('AttendanceLog/Index', ['scores' => $scores, 'months' => $months, 'years' => $years, 'grades' => $grades, 'subjects' => $subjects]);
    }

    public function create()
    {
        $grades = SubGrade::whereIsActive(true)->get();

        return inertia('AttendanceLog/Create', ['grades' => $grades]);
    }



    public function createMultipleStudentAttendance()
    {
        $grades = SubGrade::whereIsActive(true)->get();
        $years = Year::all(['id', 'name']);
        $subjects = Subject::all(['id', 'name']);
        $months = Month::all(['id', 'name']);

        return inertia('AttendanceLog/CreateMultipleStudentsAttendanceLog', [
            'months' => $months,
            'subjects' => $subjects,
            'grades' => $grades,
            'years' => $years,
        ]);
    }

    public function storeMultipleStudentsAttendanceLog(CreateMultipleAttendanceLogRequest $request)
    {
        Excel::import(new StudentAttendanceLogImport(), $request->file);
    }
}
