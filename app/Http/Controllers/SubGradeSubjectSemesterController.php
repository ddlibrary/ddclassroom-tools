<?php

namespace App\Http\Controllers;

use App\Models\SubGrade;
use App\Models\SubGradeSubjectSemester;
use App\Models\Subject;
use App\Models\Year;
use Illuminate\Http\Request;

class SubGradeSubjectSemesterController extends Controller
{
    public function index(Request $request)
    {
        $query = SubGradeSubjectSemester::with(['subGrade:id,name,full_name', 'subject:id,name,en_name']);

        if ($request->sub_grade_id) {
            $query->where('sub_grade_id', $request->sub_grade_id);
        }

        if ($request->semester) {
            $query->where('semester', $request->semester);
        }

        if ($request->year) {
            $query->where('year', $request->year);
        }

        $subGradeSubjectSemesters = $query->orderBy('year', 'desc')->orderBy('sub_grade_id')->orderBy('semester')->orderBy('subject_id')->paginate(50);

        // Get Grade 9 sub_grades only (since only Grade 9 uses semester-based system)
        $subGrades = SubGrade::whereHas('grade', function ($q) {
            $q->where('is_semester_based', true);
        })->get(['id', 'name', 'full_name']);

        $subjects = Subject::where('is_active', true)->get(['id', 'name', 'en_name']);
        $years = Year::all(['id', 'name']);

        return inertia('SubGradeSubjectSemester/Index', [
            'subGradeSubjectSemesters' => $subGradeSubjectSemesters,
            'subGrades' => $subGrades,
            'subjects' => $subjects,
            'years' => $years,
        ]);
    }

    public function create(Request $request)
    {
        // Get Grade 9 sub_grades only
        $subGrades = SubGrade::whereHas('grade', function ($q) {
            $q->where('is_semester_based', true);
        })->get(['id', 'name', 'full_name']);

        $subjects = Subject::where('is_active', true)->get(['id', 'name', 'en_name']);
        $years = Year::all(['id', 'name']);

        return inertia('SubGradeSubjectSemester/Create', [
            'subGrades' => $subGrades,
            'subjects' => $subjects,
            'years' => $years,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_grade_id' => 'required|exists:sub_grades,id',
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'exists:subjects,id',
            'semester' => 'required|in:1,2',
            'year' => 'required|string',
        ]);

        $subGradeId = $request->sub_grade_id;
        $semester = $request->semester;
        $year = $request->year;
        $subjectIds = $request->subject_ids;

        // Delete existing assignments for this sub_grade, semester, and year
        SubGradeSubjectSemester::where('sub_grade_id', $subGradeId)
            ->where('semester', $semester)
            ->where('year', $year)
            ->delete();

        // Create new assignments
        foreach ($subjectIds as $subjectId) {
            SubGradeSubjectSemester::create([
                'sub_grade_id' => $subGradeId,
                'subject_id' => $subjectId,
                'semester' => $semester,
                'year' => $year,
            ]);
        }

        return redirect()->route('sub-grade-subject-semesters.index')
            ->with('success', 'Subjects assigned successfully.');
    }

    public function destroy(SubGradeSubjectSemester $subGradeSubjectSemester)
    {
        $subGradeSubjectSemester->delete();

        return redirect()->route('sub-grade-subject-semesters.index')
            ->with('success', 'Subject assignment removed successfully.');
    }
}
