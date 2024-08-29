<?php

namespace App\Http\Controllers;

use App\Enums\UserTypeEnum;
use App\Exports\ExportStudentResult;
use App\Http\Requests\Score\CreateMultipleStudentsScoreRequest;
use App\Http\Requests\Score\CreateScoreRequest;
use App\Imports\StudentScoreImport;
use App\Models\Country;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\SubGrade;
use App\Models\Subject;
use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentResultController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentResult::query()->with(['student:id,name,father_name,country_id,fa_name,fa_father_name,uuid', 'middleResult:id,name', 'subGrade:id,full_name', 'teacher:id,name']);

        if ($request->search || $request->country_id) {
            $countryId = $request->country_id;
            $name = $request->search;
            $query->where(function ($query) use ($name, $countryId) {
                $query->whereHas('student', function ($query) use ($name, $countryId) {
                    if ($name) {
                        $query->whereAny(['name', 'username', 'father_name', 'fa_name', 'fa_father_name', 'email', 'id_number'], 'like', "%$name%");
                    }
                    if ($countryId) {
                        $query->where('country_id', $countryId);
                    }
                });
                if ($name) {
                    $query->orWhere('result_name', 'like', $name . '%');
                }
            });
        }
        if ($request->grade_id) {
            $query->where('sub_grade_id', $request->grade_id);
        }

        if ($request->year) {
            $query->where('year', $request->year);
        }

        $scores = $query->paginate(350);

        $grades = SubGrade::whereIsActive(true)->get();
        $years = Year::all(['id', 'name']);
        $countries = Country::all(['id', 'name']);

        return inertia('ResultCard/Index', ['scores' => $scores, 'grades' => $grades, 'countries' => $countries, 'years' => $years]);
    }

    public function create()
    {
        $grades = SubGrade::whereIsActive(true)->get();

        return inertia('Student/Create', ['grades' => $grades]);
    }

    public function store(CreateScoreRequest $request)
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

    public function edit(Student $student)
    {
        $grades = SubGrade::whereIsActive(true)->get();

        return inertia('Score/Edit', ['student' => $student, 'grades' => $grades]);
    }

    public function update(CreateScoreRequest $request, Student $student)
    {
        $student->update([
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

    public function getStudentResultAsExcel(Request $request)
    {
        if ($request->year) {
            return Excel::download(new ExportStudentResult($request->all()), 'student-result.xlsx');
        }
    }
}
