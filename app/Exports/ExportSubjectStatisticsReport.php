<?php

namespace App\Exports;

use App\Models\Score;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportSubjectStatisticsReport implements FromView, ShouldAutoSize
{
    public function __construct(private array $filters) {}

    public function view(): View
    {
        $request = new Request($this->filters);

        $query = Score::query()
            ->select('scores.subject_id', 'subjects.name as subject_name')
            ->selectRaw('COUNT(CASE WHEN scores.is_passed = 1 THEN 1 END) as passed_count')
            ->selectRaw('COUNT(CASE WHEN scores.is_passed = 0 THEN 1 END) as failed_count')
            ->selectRaw('COUNT(*) as total_count')
            ->join('subjects', 'scores.subject_id', '=', 'subjects.id')
            ->groupBy('scores.subject_id', 'subjects.name');

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
            $query->whereHas('subGrade', function ($q) use ($request) {
                $q->where('grade_id', $request->grade_id);
            });
        }
        if ($request->sub_grade_id) {
            $query->where('scores.sub_grade_id', $request->sub_grade_id);
        }
        if ($request->year) {
            $query->where('scores.year', $request->year);
        }
        if ($request->exam_type === 'midterm') {
            $query->where('scores.type', 1);
        } elseif ($request->exam_type === 'final') {
            $query->where('scores.type', 2);
        } elseif ($request->exam_type === 'result') {
            $query->where('scores.type', 3);
        } else {
            $query->where('scores.type', 3);
        }

        $results = $query->orderBy('subjects.name')->get();

        $totalPassed = $results->sum('passed_count');
        $totalFailed = $results->sum('failed_count');
        $totalStudents = $results->sum('total_count');

        return view('exports.subject-statistics-report', [
            'results' => $results,
            'totalPassed' => $totalPassed,
            'totalFailed' => $totalFailed,
            'totalStudents' => $totalStudents,
        ]);
    }
}



