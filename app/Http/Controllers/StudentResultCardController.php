<?php

namespace App\Http\Controllers;

use App\Models\ClassResponsible;
use App\Models\GradeSubject;
use App\Models\Result;
use App\Models\Student;

class StudentResultCardController extends Controller
{
    public function studentHandBook($uuid)
    {
        $student = Student::where('uuid', $uuid)->firstOrFail();

        return view('students.show', compact('student'));
    }

    public function studentResultCard($uuid, $year)
    {
        $student = Student::where('uuid', $uuid)
            ->with('studentResult', function ($query) use ($year) {
                $query->where([
                    'year' => $year,
                ]);
            })
            ->with('middleAttendance', function ($query) use ($year) {
                $query->where([
                    'year' => $year,
                ]);
            })
            ->with('finalAttendance', function ($query) use ($year) {
                $query->where([
                    'year' => $year,
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

        //  return $subjects;

        return view('students.student-result-card', compact('student', 'subjects', 'results', 'responsible'));
    }

    public function resultCards() {}
}
