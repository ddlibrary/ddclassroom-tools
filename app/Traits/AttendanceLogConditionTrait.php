<?php

namespace App\Traits;

trait AttendanceLogConditionTrait
{
    protected function filterAttendance($query, $request)
    {
        if ($request->month_id) {
            $query->where('month_id', $request->month_id);
        }
        if ($request->year) {
            $query->where('year', $request->year);
        }
        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->sub_grade_id) {
            $query->where('sub_grade_id', $request->sub_grade_id);
        }
        if ($request->term) {
            $query->where('first_term', $request->term == 1 ? true : false);
        }

        return $query;
    }
}
