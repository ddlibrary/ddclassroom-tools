<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\CreateMultipleStudentsRequest;
use App\Http\Requests\Student\CreateStudentRequest;
use App\Imports\StudentImport;
use App\Jobs\SendEmailJob;
use App\Models\Country;
use App\Models\Student;
use App\Models\SubGrade;
use App\Models\Year;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query()->with(['country:id,name', 'subGrade:id,full_name']);

        if ($request->search) {
            $name = $request->search;
            $query->where(function ($query) use ($name) {
                $query
                    ->where('name', 'like', "%$name%")
                    ->orWhere('last_name', 'like', "%$name%")
                    ->orWhere('username', 'like', "%$name%")
                    ->orWhere('father_name', 'like', "%$name%")
                    ->orWhere('email', 'like', "$name%")
                    ->orWhere('phone', 'like', "%$name%");
            });
        }
        if ($request->grade_id) {
            $query->where('sub_grade_id', $request->grade_id);
        }

        $students = $query->orderByDesc('id')->paginate(35);
        $grades = SubGrade::whereIsActive(true)->get();
        $countries = Country::whereIsActive(true)->get();

        return inertia('Student/Index', ['students' => $students, 'countries' => $countries, 'grades' => $grades]);
    }

    public function create()
    {
        $grades = SubGrade::whereIsActive(true)->get();
        $countries = Country::whereIsActive(true)->get();

        return inertia('Student/Create', ['countries' => $countries, 'grades' => $grades]);
    }

    public function store(CreateStudentRequest $request)
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

        // Upload student photo
        if ($request->hasFile('photo')) {
            $student->photo = 'student-photos/' . $this->upload($request);
            $student->save();
        }

        return redirect('students');
    }

    public function edit(Student $student)
    {
        $grades = SubGrade::whereIsActive(true)->get();
        $countries = Country::whereIsActive(true)->get();

        return inertia('Student/Edit', ['student' => $student, 'countries' => $countries, 'grades' => $grades]);
    }

    public function upload($request)
    {
        if ($request->hasFile('photo')) {
            $filename = time() . '-' . $request->photo->getClientOriginalName();
            $request->photo->storeAs('student-photos', $filename, 'public');

            return $filename;
        }
    }

    public function update(CreateStudentRequest $request, Student $student)
    {
        $student->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'father_name' => $request->father_name,
            'fa_name' => $request->fa_name,
            'fa_last_name' => $request->fa_last_name,
            'fa_father_name' => $request->fa_father_name,
            'id_number' => $request->id_number,
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

        // Upload student photo
        if ($request->hasFile('photo')) {
            $student->photo = 'student-photos/' . $this->upload($request);
            $student->save();
        }

        return redirect('students');
    }

    public function createMultipleStudents()
    {
        $grades = SubGrade::whereIsActive(true)->get();
        $years = Year::all(['id', 'name']);

        return inertia('Student/CreateMultipleStudents', ['grades' => $grades, 'years' => $years]);
    }

    public function storeMultipleStudents(CreateMultipleStudentsRequest $request)
    {
        Excel::import(new StudentImport(), $request->file);
    }

    public function emailHandbook($uuid)
    {
        // $students = Student::where('id', '>=', 503)->where('id', '<=', 510)->get();
        // $delay = 2; // Delay in seconds
        // foreach ($students as $index => $student) {
        //     $job = (new SendEmailJob($student))
        //         ->onConnection('database')
        //         ->delay(now()->addSeconds($index * $delay));
        //     dispatch($job);
        // }
        $student = Student::where('uuid', $uuid)->firstOrFail();

        dispatch(new SendEmailJob($student))->onConnection('database');

        return back();
    }

    public function studentUpladingSampleFile()
    {
        $filePath = storage_path('app/public/student-uploading-sample-file.xlsx');

        $fileName = 'student-uploading-sample-file.xlsx';

        return response()->download($filePath, $fileName);
    }
}
