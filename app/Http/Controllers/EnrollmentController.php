<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function getStudents(Request $request)
    {
        $students = Student::whereHas('enrollments', function ($query) use ($request) {
            $query->where(['year' => $request->year, 'sub_grade_id' => $request->grade_id]);
        })->get();

        return response()->json(['data' => $students]);
    }
}
