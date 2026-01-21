<?php

namespace App\Exports;

use App\Models\GradeSubject;
use App\Models\Score;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExportAllStudentsSubjectScores implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(private array $filters) {}

    public function view(): View
    {
        $request = new Request($this->filters);

        // Get all active students with filters
        $query = Student::query()
            ->where('is_active', true)
            ->with([
                'subGrade:id,full_name,grade_id',
                'subGrade.grade:id,name',
                'country:id,name'
            ])
            ->select(['id', 'name', 'father_name', 'fa_name', 'fa_father_name', 'id_number', 'email', 'sub_grade_id', 'country_id']);

        // Apply filters
        if ($request->student_id) {
            $query->where('id_number', 'like', "%{$request->student_id}%");
        }

        if ($request->email) {
            $query->where('email', 'like', "%{$request->email}%");
        }

        if ($request->country_id) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->grade_id) {
            $gradeIds = is_array($request->grade_id) ? $request->grade_id : [$request->grade_id];
            $gradeIds = array_filter($gradeIds);
            if (!empty($gradeIds)) {
                $query->whereHas('subGrade', function ($q) use ($gradeIds) {
                    $q->whereIn('grade_id', $gradeIds);
                });
            }
        }

        if ($request->sub_grade_id) {
            $subGradeIds = is_array($request->sub_grade_id) ? $request->sub_grade_id : [$request->sub_grade_id];
            $subGradeIds = array_filter($subGradeIds);
            if (!empty($subGradeIds)) {
                $query->whereIn('sub_grade_id', $subGradeIds);
            }
        }

        $students = $query->orderBy('name')->get();

        // Build results array with scores for each student
        $results = [];
        $year = $request->year ?: date('Y');
        $examType = $request->exam_type ?: 'result'; // Default to result
        
        // Map exam type to score relationship name
        $scoreRelationship = match($examType) {
            'midterm' => 'middle',
            'final' => 'final',
            'result' => 'finalResult',
            default => 'finalResult',
        };
        
        // Group students by grade_id for efficient eager loading
        $studentsByGrade = $students->groupBy(function ($student) {
            return $student->subGrade->grade_id;
        });
        
        foreach ($studentsByGrade as $gradeId => $gradeStudents) {
            // Get all subjects for this grade
            $gradeSubjects = GradeSubject::with(['grade:id,name', 'subject:id,name,en_name'])
                ->where('grade_id', $gradeId)
                ->get();
            
            // Get all subjects
            $allSubjects = $gradeSubjects->pluck('subject')->unique('id')->values();
            
            // Get all student IDs for this grade to eager load student results
            $studentIds = $gradeStudents->pluck('id')->toArray();
            
            // Eager load student results for all students in this grade
            $studentResults = StudentResult::whereIn('student_id', $studentIds)
                ->where('year', $year)
                ->get()
                ->keyBy('student_id');
            
            // For each student in this grade, eager load scores
            foreach ($gradeStudents as $student) {
                $where = [
                    'year' => $year,
                    'student_id' => $student->id,
                    'sub_grade_id' => $student->sub_grade_id,
                ];
                
                // Eager load scores for all subjects for this student
                $subjectsWithScores = Subject::whereIn('id', $allSubjects->pluck('id'))
                    ->with([$scoreRelationship => function ($query) use ($where) {
                        $query->where($where);
                    }])
                    ->get();
                
                $studentScores = [];
                
                // Build scores array in the same order as allSubjects
                foreach ($allSubjects as $subject) {
                    $subjectWithScore = $subjectsWithScores->find($subject->id);
                    $score = $subjectWithScore ? $subjectWithScore->{$scoreRelationship} : null;

                    $studentScores[] = [
                        'subject_id' => $subject->id,
                        'subject_name' => $subject->en_name ?? $subject->name,
                        'score' => $score ? [
                            'total' => $score->total,
                            'is_passed' => $score->is_passed,
                        ] : null,
                    ];
                }

                // Get student result status
                $studentResult = $studentResults->get($student->id);
                $resultStatus = $studentResult ? $studentResult->result_name : null;

                $results[] = [
                    'student' => $student,
                    'subjects' => $studentScores,
                    'result_status' => $resultStatus,
                ];
            }
        }

        // Calculate result statistics
        $totalKamyab = 0;
        $totalMashroot = 0;
        $totalNakam = 0;
        
        foreach ($results as $result) {
            $status = $result['result_status'] ?? '';
            if (str_contains($status, 'کامیاب')) {
                $totalKamyab++;
            } elseif (str_contains($status, 'مشروط')) {
                $totalMashroot++;
            } elseif (str_contains($status, 'ناکام') || str_contains($status, 'تکرار')) {
                $totalNakam++;
            }
        }
        
        return view('exports.all-students-subject-scores-report', [
            'results' => $results,
            'subjects' => $allSubjects,
            'examType' => $examType,
            'totalKamyab' => $totalKamyab,
            'totalMashroot' => $totalMashroot,
            'totalNakam' => $totalNakam,
        ]);
    }
    
    /**
     * Set the sheet title (max 31 characters for Excel)
     */
    public function title(): string
    {
        // Keep it under 31 characters
        return 'Students Scores';
    }
}

