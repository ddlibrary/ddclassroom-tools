<?php

namespace App\Http\Controllers;

use App\Models\StudentRetakeOpportunity;
use App\Models\SubGrade;
use App\Models\Subject;
use App\Models\Year;
use Illuminate\Http\Request;

class StudentRetakeOpportunityController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentRetakeOpportunity::query()
            ->with([
                'student:id,name,fa_name,father_name,fa_father_name,email,id_number',
                'subGrade:id,name,full_name',
                'year:id,name',
                'subject:id,name'
            ]);

        if ($request->search) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->whereAny(['name', 'fa_name', 'email', 'id_number'], 'like', "%$search%");
            });
        }

        if ($request->sub_grade_id) {
            $query->where('sub_grade_id', $request->sub_grade_id);
        }

        if ($request->year_id) {
            $query->where('year_id', $request->year_id);
        }

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->has('is_passed') && $request->is_passed !== '') {
            $query->where('is_passed', $request->is_passed == '1' ? true : false);
        }

        $retakeOpportunities = $query->orderByDesc('id')->paginate(50);
        $grades = SubGrade::whereIsActive(true)->get();
        $years = Year::all(['id', 'name']);
        $subjects = Subject::all(['id', 'name']);

        return inertia('Student/RetakeOpportunities', [
            'retakeOpportunities' => $retakeOpportunities,
            'grades' => $grades,
            'years' => $years,
            'subjects' => $subjects,
        ]);
    }
}
