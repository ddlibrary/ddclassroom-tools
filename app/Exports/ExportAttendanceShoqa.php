<?php

namespace App\Exports;

use App\Models\AttendanceDetail;
use App\Models\Enrollment;
use App\Models\SubGrade;
use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportAttendanceShoqa implements FromView, ShouldAutoSize
{
    public function __construct(private array $data) {}

    public function view(): View
    {
        $year = $this->data['year'];
        $subjectId = $this->data['subject_id'];
        $subGradeId = $this->data['grade_id'];
        $type = $this->data['type'];
        $subGrade = SubGrade::where('id', $subGradeId)->first();

        $subject = Subject::where('id', $subjectId)->first();

        $where = [
            'year' => $year,
            'subject_id' => $subjectId,
            'sub_grade_id' => $subGradeId,
            'type' => $type,
        ];

        $attendanceDetail = AttendanceDetail::select(
            [
                'id', 'teacher_id', 'sub_grade_id', 'type', 'created_at',
            ])
            ->with(['teacher:id,name', 'subGrade:id,name'])
            ->where($where)
            ->first();

        $enrollments = Enrollment::with([
            'student' => function ($query) use ($where) {
                $query->with([
                    'attendanceDetail' => function ($query) use ($where) {
                        $query->where($where);
                    },
                ]);
            },
        ])->where(['sub_grade_id' => $subGradeId, 'year' => $year])->get();

        return view('shoqa.attendance-shoqa-as-excel', [
            'attendanceDetail' => $attendanceDetail,
            'enrollments' => $enrollments,
            'subject' => $subject,
            'year' => $year,
            'grade' => $subGrade,
            'type' => $type
        ]);
    }
}
