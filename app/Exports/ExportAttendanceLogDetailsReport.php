<?php

namespace App\Exports;
ini_set('max_execution_time', 256);
ini_set('memory_limit', '256M');

use App\Models\AttendanceLog;
use App\Traits\AttendanceLogConditionTrait;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportAttendanceLogDetailsReport implements FromView, ShouldAutoSize
{
    use AttendanceLogConditionTrait;
    public function __construct(private array $data)
    {
    }

    public function view(): View
    {
        $request = (object) $this->data;

        // Start building the query
        $query = AttendanceLog::query()
            ->with([
                'student:id,fa_name,fa_father_name,id_number,email',
                'subGrade:id,full_name',
                'subject:id,name',
                'month:id,name',
            ])
           ->select('id', 'date', 'student_id', 'status', 'date', 'sub_grade_id', 'subject_id', 'month_id', 'user_id'); // Select only necessary columns

        $query
            ->when($request->sub_grade_id, function ($q) use ($request) {
                $q->where('sub_grade_id', $request->sub_grade_id);
            })
            ->when($request->month_id, function ($q) use ($request) {
                $q->where('month_id', $request->month_id);
            })
            ->when($request->country_id, function ($q) use ($request) {
                // Replace the ID fetching with whereHas
                $q->whereHas('student', function ($query) use ($request) {
                    $query->where('country_id', $request->country_id);
                });
            })
            ->when($request->subject_id, function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });

        // Order and get the results
        $logs = $query->take(20000)->orderBy('date')->get();



        return view('attendance.export-attendance-log-details-report-as-excel', [
            'logs' => $logs,
            'year' => $request->year,
        ]);
    }
}
