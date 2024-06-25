<?php

namespace App\Exports;

use App\Models\Enrollment;
use App\Models\SubGrade;
use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportShoqa implements FromView, ShouldAutoSize
{
    public function __construct(private array $data) {}

    public function view(): View
    {
        $year = $this->data['year'];
        $subjectId = $this->data['subject_id'];
        $subGradeId = $this->data['grade_id'];
        $type = $this->data['type'];

        $subject = Subject::where('id', $subjectId)->first();
        $subGrade = SubGrade::where('id', $subGradeId)->first();

        $enrollments = Enrollment::with([
            'student' => function ($query) use ($year, $subjectId, $subGradeId, $type) {
                $query->with([
                    'score' => function ($query) use ($year, $subjectId, $subGradeId, $type) {
                        $query->where([
                            'year' => $year,
                            'subject_id' => $subjectId,
                            'sub_grade_id' => $subGradeId,
                            'type' => $type,
                        ]);
                    },
                ]);
            },
        ])->where(['sub_grade_id' => $subGradeId, 'year' => $year])->get();

        return view('shoqa.shoqa-as-excel', [
            'enrollments' => $enrollments,
            'subject' => $subject,
            'type' => $type,
            'year' => $year,
            'grade' => $subGrade,
        ]);
    }
}
