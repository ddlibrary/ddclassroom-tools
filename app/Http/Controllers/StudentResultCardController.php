<?php

namespace App\Http\Controllers;

use App\Models\ClassResponsible;
use App\Models\GradeSubject;
use App\Models\Result;
use App\Models\Student;
use App\Models\StudentResult;
use Illuminate\Http\Request;

class StudentResultCardController extends Controller
{
    public function studentHandBook($uuid)
    {
        $student = Student::where('uuid', $uuid)->firstOrFail();

        return view('students.show', compact('student'));
    }

    public function studentResultCard(Request $request, $uuid, $year, StudentResult $studentResult)
    {
        $subGradeId = $studentResult->sub_grade_id;
        $student = Student::where('uuid', $uuid)
            ->with('middleAttendance', function ($query) use ($year, $subGradeId) {
                $query->where([
                    'year' => $year,
                    'sub_grade_id' => $subGradeId
                ]);
            })
            ->with('finalAttendance', function ($query) use ($year, $subGradeId) {
                $query->where([
                    'year' => $year,
                    'sub_grade_id' => $subGradeId
                ]);
            })
            ->firstOrFail();

        $responsible = ClassResponsible::with('teacher:id,name,signature')->where([
            'year' => $year,
            'sub_grade_id' => $student->sub_grade_id,
        ])->first();

        $studentId = $student->id;
        $where = [
            'year' => $year,
            'student_id' => $studentId,
            'sub_grade_id' => $subGradeId
        ];

        $subjects = GradeSubject::with(['grade:id,name', 'subject'])
            ->with('subject.middle', function ($query) use ($where) {
                $query->where($where);
            })
            ->with('subject.final', function ($query) use ($where) {
                $query->where($where);
            })
            ->with('subject.finalResult', function ($query) use ($where) {
                $query->where($where);
            })
            ->where('grade_id', $student->subGrade->grade_id)->get();
        $results = Result::all();

        if ($request->en_result) {
            return view('students.student-en-result-card', compact('student', 'subjects', 'results', 'responsible', 'studentResult'));
        }

        return view('students.student-result-card', compact('student', 'subjects', 'results', 'responsible', 'year', 'studentResult'));
    }

    public function resultCards() {}
}
