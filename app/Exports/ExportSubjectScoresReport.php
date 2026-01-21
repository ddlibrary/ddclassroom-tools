<?php

namespace App\Exports;

use App\Models\Score;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportSubjectScoresReport implements FromView, ShouldAutoSize
{
    public function __construct(private array $filters) {}

    public function view(): View
    {
        $request = new Request($this->filters);

        $query = Score::query()
            ->select([
                'scores.id',
                'scores.written',
                'scores.verbal',
                'scores.attendance',
                'scores.activity',
                'scores.homework',
                'scores.evaluation',
                'scores.total',
                'scores.type',
                'scores.is_passed',
                'scores.year',
                'scores.student_id',
                'scores.sub_grade_id',
                'scores.subject_id',
            ])
            ->with([
                'student:id,name,father_name,country_id,fa_name,fa_father_name,uuid,id_number,email',
                'subject:id,name',
                'subGrade:id,full_name,grade_id',
                'subGrade.grade:id,name',
            ])
            ->whereHas('student', function ($q) {
                $q->where('is_active', true);
            });

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->student_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('id_number', 'like', "%{$request->student_id}%");
            });
        }
        if ($request->email) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->email}%");
            });
        }
        if ($request->country_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
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
        if ($request->year) {
            $query->where('year', $request->year);
        }
        if ($request->exam_type === 'midterm') {
            $query->where('type', 1);
        } elseif ($request->exam_type === 'final') {
            $query->where('type', 2);
        } elseif ($request->exam_type === 'result') {
            $query->where('type', 3);
        } else {
            $query->where('type', 3);
        }
        if ($request->status === 'passed') {
            $query->where('is_passed', true);
        } elseif ($request->status === 'failed') {
            $query->where('is_passed', false);
        }

        $results = $query->orderByDesc('id')->get();

        return view('exports.subject-scores-report', [
            'results' => $results,
        ]);
    }
}


