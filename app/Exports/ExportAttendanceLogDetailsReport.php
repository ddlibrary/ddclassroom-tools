<?php

namespace App\Exports;

use App\Models\AttendanceLog;
use App\Models\Month;
use App\Models\Student;
use App\Traits\AttendanceLogConditionTrait;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportAttendanceLogDetailsReport implements FromView, ShouldAutoSize
{
    use AttendanceLogConditionTrait;
    public function __construct(private array $data) {}

    public function view(): View
    {

        $request = (object)$this->data;

        $query = AttendanceLog::query()->with(['student:id,fa_name,fa_father_name,id_number,email','subGrade:id,full_name', 'subject:id,name','user:id,name','month:id,name']);

        if($request->sub_grade_id){
            $query->where('sub_grade_id', $request->sub_grade_id);
        }

        if($request->month_id){
            $query->where('month_id', $request->month_id);
        }
        if($request->country_id){
            $students = Student::whereCountryId($request->country_id)->pluck('id');

            $query->whereIn('student_id', $students);
        }

        if($request->subject_id){
            $query->where('subject_id', $request->subject_id);
        }

        $logs = $query->orderBy('date')->get();

        return view('attendance.export-attendance-log-details-report-as-excel', [
            'logs' => $logs,
            'year' => $request->year
        ]);
    }
}
