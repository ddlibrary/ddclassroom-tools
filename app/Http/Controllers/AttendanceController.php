<?php

namespace App\Http\Controllers;

use App\Enums\UserTypeEnum;
use App\Http\Requests\Attendance\CreateAttendanceRequest;
use App\Http\Requests\Attendance\CreateMultipleAttendanceRequest;
use App\Imports\StudentAttendanceImport;
use App\Models\Attendance;
use App\Models\SubGrade;
use App\Models\Subject;
use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::query()->with(['student:id,fa_name,fa_father_name,uuid,id_number', 'subGrade:id,full_name', 'teacher:id,name']);

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

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->year) {
            $query->where('year', $request->year);
        }

        $scores = $query->paginate(350);
        $grades = SubGrade::whereIsActive(true)->get();
        $years = Year::all(['id', 'name']);

        $types = [['id' => 1, 'name' => 'Middle Exam'], ['id' => 2, 'name' => 'Final Exam']];

        return inertia('Attendance/Index', ['scores' => $scores, 'years' => $years, 'grades' => $grades, 'attendanceTypes' => $types]);
    }

    public function create()
    {
        $grades = SubGrade::whereIsActive(true)->get();

        return inertia('Attendance/Create', ['grades' => $grades]);
    }

    public function store(CreateAttendanceRequest $request)
    {
        $student = Student::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'father_name' => $request->father_name,
            'phone' => $request->phone,
            'photo' => $request->photo,
            'username' => $request->username,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'sub_grade_id' => $request->grade_id,
            'password' => $request->password,
            'name_in_system' => $request->name_in_system,
            'school' => $request->school,
            'password' => $request->password,
            'is_active' => $request->is_active == 1 ? true : false,
        ]);

        return redirect('students');
    }

    public function edit(Attendance $attendance)
    {
        $grades = SubGrade::whereIsActive(true)->get();

        return inertia('Attendance/Edit', ['attendance' => $attendance, 'grades' => $grades]);
    }

    public function update(CreateAttendanceRequest $request, Attendance $attendance)
    {
        $attendance->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'father_name' => $request->father_name,
            'phone' => $request->phone,
            'photo' => $request->photo,
            'username' => $request->username,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'sub_grade_id' => $request->grade_id,
            'password' => $request->password,
            'name_in_system' => $request->name_in_system,
            'school' => $request->school,
            'password' => $request->password,
            'is_active' => $request->is_active == 1 ? true : false,
        ]);

        return redirect('score');
    }

    public function createMultipleStudentAttendance()
    {
        $grades = SubGrade::whereIsActive(true)->get();
        $years = Year::all(['id', 'name']);
        $subjects = Subject::all(['id', 'name']);
        $teachers = User::whereUserTypeId(UserTypeEnum::Teacher)->get();

        return inertia('Attendance/CreateMultipleStudentsAttendance', [
            'teachers' => $teachers,
            'subjects' => $subjects,
            'grades' => $grades,
            'years' => $years,
        ]);
    }

    public function storeMultipleStudentsAttendance(CreateMultipleAttendanceRequest $request)
    {
        Excel::import(new StudentAttendanceImport(), $request->file);
    }
}
