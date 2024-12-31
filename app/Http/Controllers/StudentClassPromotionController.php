<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\SubGrade;
use App\Models\Year;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentClassPromotionController extends Controller
{
    public function index()
    {
        $grades = SubGrade::whereIsActive(true)->get();
        $years = Year::all(['id', 'name']);

        return inertia('StudentClassPromotion/Create', [
            'grades' => $grades,
            'years' => $years,
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $students = Student::where('sub_grade_id', $request->from_sub_grade_id)->with('studentResult')->get();

            foreach ($students as $student) {
                if ($student->studentResult && $student->studentResult->result_name == 'کامیاب') {
                    Enrollment::updateOrCreate([
                        'student_id' => $student->id,
                        'sub_grade_id' => $request->to_sub_grade_id,
                        'year' => $request->year,
                    ], [
                        'user_id' => auth()->id(),
                    ]);

                    $student->update([
                        'sub_grade_id' => $request->to_sub_grade_id,
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();

            throw $exception;
        }

        return back();
    }
}
