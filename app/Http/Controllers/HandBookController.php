<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\Student;
use App\Models\SubGrade;
use Illuminate\Http\Request;

class HandBookController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query()->with(['country:id,name', 'subGrade']);

        if ($request->username) {
            $search = $request->username;
            $query->where(function ($query) use ($search) {
                $query->where('username', $search)
                    ->orWhere('email', $search);
            });
        }

        if ($request->grade_id) {
            $query->where('sub_grade_id', $request->grade_id);
        }

        $students = $query->orderBy('id', 'desc')->paginate(100);

        $grades = SubGrade::whereIsActive(true)->get();

        return inertia('Handbook/Index', ['students' => $students, 'grades' => $grades]);
    }

    public function sendHandbook(Request $request)
    {
        $query = Student::query();

        if ($request->username) {
            $search = $request->username;
            $query->where(function ($query) use ($search) {
                $query->where('username', $search)
                    ->orWhere('email', $search);
            });
        }

        if ($request->country_id) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->grade_id) {
            $query->where('sub_grade_id', $request->grade_id);
        }

        $students = $query->get();

        foreach ($students as $student) {
            $student = Student::find($student->id);

            dispatch(new SendEmailJob($student))->onConnection('database');
        }

        return back();
    }
}
