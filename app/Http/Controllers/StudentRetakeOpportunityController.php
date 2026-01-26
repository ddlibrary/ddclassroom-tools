<?php

namespace App\Http\Controllers;

use App\Models\StudentRetakeOpportunity;
use App\Models\Student;
use App\Models\SubGrade;
use App\Models\Subject;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentRetakeOpportunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StudentRetakeOpportunity::query()
            ->with(['student:id,name,fa_name,father_name,fa_father_name,uuid', 'subject:id,name', 'subGrade:id,name', 'year:id,name']);

        if ($request->search) {
            $name = $request->search;
            $query->where(function ($query) use ($name) {
                $query->whereHas('student', function ($query) use ($name) {
                    $query->whereAny(['name', 'username', 'fa_name', 'fa_father_name', 'father_name', 'email', 'id_number'], 'like', "%$name%");
                });
            });
        }

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->sub_grade_id) {
            $query->where('sub_grade_id', $request->sub_grade_id);
        }

        if ($request->year_id) {
            $query->where('year_id', $request->year_id);
        }

        $retakeOpportunities = $query->paginate(300);
        $grades = SubGrade::whereIsActive(true)->get();
        $subjects = Subject::all(['id', 'name']);
        $years = Year::all(['id', 'name']);

        return inertia('StudentRetakeOpportunity/Index', [
            'retakeOpportunities' => $retakeOpportunities,
            'grades' => $grades,
            'subjects' => $subjects,
            'years' => $years,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grades = SubGrade::whereIsActive(true)->get();
        $subjects = Subject::all(['id', 'name']);
        $years = Year::all(['id', 'name']);

        return inertia('StudentRetakeOpportunity/Create', [
            'grades' => $grades,
            'subjects' => $subjects,
            'years' => $years,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'sub_grade_id' => 'required|exists:sub_grades,id',
            'year_id' => 'required|exists:years,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'nullable|numeric|min:0|max:100',
            'second_chance_score' => 'nullable|numeric|min:0|max:100',
            'third_chance_score' => 'nullable|numeric|min:0|max:100',
            'first_chance_date' => 'nullable|date',
            'second_chance_date' => 'nullable|date',
            'is_passed' => 'boolean',
        ]);

        StudentRetakeOpportunity::create($validated);

        return redirect()->route('student-retake-opportunities.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentRetakeOpportunity $studentRetakeOpportunity)
    {
        $studentRetakeOpportunity->load(['student', 'subject', 'subGrade', 'year']);

        return inertia('StudentRetakeOpportunity/Show', [
            'retakeOpportunity' => $studentRetakeOpportunity,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentRetakeOpportunity $studentRetakeOpportunity)
    {
        $studentRetakeOpportunity->load(['student', 'subject', 'subGrade', 'year']);
        $grades = SubGrade::whereIsActive(true)->get();
        $subjects = Subject::all(['id', 'name']);
        $years = Year::all(['id', 'name']);

        return inertia('StudentRetakeOpportunity/Edit', [
            'retakeOpportunity' => $studentRetakeOpportunity,
            'grades' => $grades,
            'subjects' => $subjects,
            'years' => $years,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentRetakeOpportunity $studentRetakeOpportunity)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'sub_grade_id' => 'required|exists:sub_grades,id',
            'year_id' => 'required|exists:years,id',
            'subject_id' => 'required|exists:subjects,id',
            'score' => 'nullable|numeric|min:0|max:100',
            'second_chance_score' => 'nullable|numeric|min:0|max:100',
            'third_chance_score' => 'nullable|numeric|min:0|max:100',
            'first_chance_date' => 'nullable|date',
            'second_chance_date' => 'nullable|date',
            'is_passed' => 'boolean',
        ]);

        $studentRetakeOpportunity->update($validated);

        return redirect()->route('student-retake-opportunities.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentRetakeOpportunity $studentRetakeOpportunity)
    {
        $studentRetakeOpportunity->delete();

        return redirect()->route('student-retake-opportunities.index');
    }
}
