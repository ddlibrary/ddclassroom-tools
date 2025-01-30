<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\CreateMultipleStudentsRequest;
use App\Http\Requests\Student\CreateStudentRequest;
use App\Http\Requests\Student\UpdateStudentInfoRequest;
use App\Imports\StudentImport;
use App\Imports\UpdateStudentInfoImport;
use App\Jobs\SendEmailJob;
use App\Models\Country;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\SubGrade;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\DomCrawler\Crawler;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query()->with(['country:id,name', 'subGrade:id,full_name']);

        if ($request->search) {
            $name = $request->search;
            $query->where(function ($query) use ($name) {
                $query->whereAny(['name', 'last_name', 'username', 'father_name', 'fa_name', 'fa_last_name', 'fa_father_name', 'email', 'phone', 'id_number'], 'like', "%$name%");
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

        Enrollment::create([
            'student_id' => $student->id,
            'sub_grade_id' => $student->sub_grade_id,
            'user_id' => auth()->id(),
            'year' => date('Y'),
        ]);

        // Upload student photo
        if ($request->hasFile('photo')) {
            $student->photo = 'student-photos/'.$this->upload($request);
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
            $filename = time().'-'.$request->photo->getClientOriginalName();
            $request->photo->storeAs('student-photos', $filename, 'public');

            return $filename;
        }
    }

    public function update(CreateStudentRequest $request, Student $student)
    {
        if ($student->sub_grade_id != $request->grade_id) {
            $currentClass = $student->subGrade->grade;
            $newClass = SubGrade::find($request->grade_id)->grade;

            if ($currentClass->id == $newClass->id) {
                Enrollment::where(['student_id' => $student->id, 'sub_grade_id' => $student->sub_grade_id])->update([
                    'sub_grade_id' => $request->grade_id,
                    'user_id' => auth()->id(),
                ]);
            } else {
                Enrollment::create([
                    'student_id' => $student->id,
                    'sub_grade_id' => $request->grade_id,
                    'user_id' => auth()->id(),
                    'year' => date('Y'),
                ]);
            }
            $student->sub_grade_id = $request->grade_id;
            $student->save();
        }

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
            'father_phone' => $request->father_phone,
            'father_email' => $request->father_email,
            'province' => $request->province,
            'username' => $request->username,
            'email' => $request->email,
            'country_id' => $request->country_id,

            'password' => $request->password,
            'name_in_system' => $request->name_in_system,
            'school' => $request->school,
            'password' => $request->password,
            'is_active' => $request->is_active == 1 ? true : false,
        ]);

        // Upload student photo
        if ($request->hasFile('photo')) {
            $student->photo = 'student-photos/'.$this->upload($request);
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

    public function editStudentInfo()
    {
        return inertia('Student/EditStudentInfo');
    }

    public function storeMultipleStudents(CreateMultipleStudentsRequest $request)
    {
        Excel::import(new StudentImport, $request->file);
    }

    public function updateStudentInfo(UpdateStudentInfoRequest $request)
    {
        Excel::import(new UpdateStudentInfoImport, $request->file);
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

    public function sendResultCard(Request $request)
    {
        // Retrieve the student's data from the request
        $student = $request->input('student');

        // Render the Blade view that displays the student's result card
        $html = view('student.result-card', compact('student'))->render();

        // Use the Symfony DomCrawler to capture a screenshot of the result card
        $crawler = new Crawler($html);
        $screenshot = $crawler->filter('body')->screenshot('result-card.png');

        // Create an Excel file attachment
        $excel = Excel::create('student-result', function ($excel) use ($student) {
            $excel->sheet('Results', function ($sheet) use ($student) {
                $sheet->fromArray([
                    'Name' => $student['name'],
                    'Grade' => $student['grade'],
                    'Attendance' => $student['attendance'],
                ]);
            });
        })->store('xlsx');

        // Send the email with the screenshot and Excel attachment
        Mail::send('emails.result-card', compact('student', 'screenshot', 'excel'), function ($message) use ($student) {
            $message->to($student['email'])->subject('Student Result Card');
            $message->attach(storage_path('app/result-card.png'));
            $message->attach(storage_path('app/student-result.xlsx'));
        });

        return redirect()->back()->with('success', 'Result card sent successfully!');
    }

    public function destroy(Student $student)
    {
        $student->update(['is_active' => ! $student->is_active]);

        return back();
    }
}
