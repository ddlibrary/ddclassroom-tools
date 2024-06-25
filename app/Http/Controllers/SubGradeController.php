<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubGrade\SubGradeRequest;
use App\Models\Grade;
use App\Models\SubGrade;
use App\Models\Year;
use Illuminate\Http\Request;

class SubGradeController extends Controller
{
    // Result list
    public function index(Request $request)
    {
        $query = SubGrade::query();

        if ($request->name) {
            $name = $request->name;
            $query->where(function ($query) use ($name) {
                $query->whereAny(['name', 'full_name', 'location', 'level'], 'like', "%$name%");
            });
        }

        if ($request->year) {
            $query->where('year', $request->year);
        }

        $subGrades = $query->orderBy('year', 'desc')->paginate();
        $grades = Grade::whereIsActive(true)->get();
        $years = Year::all(['id', 'name']);

        return inertia('SubGrade/Index', ['subGrades' => $subGrades, 'grades' => $grades, 'years' => $years]);
    }

    // Toggle result state
    public function toggleIsActive(Request $request)
    {
        SubGrade::where('id', $request->id)->update(['is_active' => $request->is_active]);

        return redirect('sub-grades');
    }

    // Store or update result
    public function store(SubGradeRequest $request)
    {
        $grade = Grade::find($request->grade_id);
        $fullName = $grade->name;
        if ($request->level) {
            $fullName .= " $request->level";
        }
        if ($request->location) {
            $fullName .= " ($request->location)";
        }

        $data = [
            'name' => $grade->name . ' ' . $request->level,
            'full_name' => $fullName,
            'level' => $request->level,
            'location' => $request->location,
            'year' => $request->year,
            'grade_id' => $request->grade_id,
            'is_active' => $request->is_active,
        ];

        $request->id ? SubGrade::where('id', $request->id)->update($data) : SubGrade::insert($data);

        return redirect('sub-grades');
    }

    // Delete result
    public function destroy(SubGrade $subGrade)
    {
        $subGrade->delete();

        return redirect('sub-grades');
    }
}
