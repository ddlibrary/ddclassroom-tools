<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\Student;
use App\Models\SubGrade;
use App\Models\Year;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HandBookController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query()->with(['country:id,name', 'subGrade']);

        $query = $this->applyStudentFilters($query, $request);

        $students = $query->orderBy('id', 'desc')->paginate(100);

        $years = Year::all();
        $grades = SubGrade::whereIsActive(true)->get();

        return inertia('Handbook/Index', ['students' => $students, 'grades' => $grades, 'years' => $years]);
    }

    public function sendHandbook(Request $request)
    {
        $query = Student::query();

        $query = $this->applyStudentFilters($query, $request);

        $students = $query->get();

        foreach ($students as $student) {
            $student = Student::find($student->id);

            dispatch(new SendEmailJob($student))->onConnection('database');
        }

        return back();
    }

    private function applyStudentFilters(Builder $query, Request $request): Builder
    {
        $yearId  = $request->input('year_id');
        $gradeId = $request->input('grade_id');

        if ($yearId) {
            $year = Year::find($yearId);
            if ($year) {
                $query->whereHas('enrollments', function ($q) use ($year, $gradeId) {
                    $q->where([
                        'year' => $year->name,
                        'is_active' => true,
                    ]);
                    if ($gradeId) {
                        $q->where('sub_grade_id', $gradeId);
                    }
                });
            }
        }

        if ($request->filled('username')) {
            $search = $request->username;
            $query->where(function ($q) use ($search) {
                $q->where('username', $search)
                ->orWhere('email', $search);
            });
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($gradeId) {
            $query->where('sub_grade_id', $gradeId);
        }

        return $query;
    }
}
