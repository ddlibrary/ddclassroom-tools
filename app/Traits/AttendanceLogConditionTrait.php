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
        if (isset($request->term) && $request->term) {
            $query->where('first_term', $request->term == 1 ? true : false);
        }

        if (isset($request->from) && isset($request->to) && $request->from && $request->to) {

            $query->where('date', '>=', $request->from. ' 00:00:00')->where('date', '<=', $request->to. ' 23:59:59');
        }

        return $query;
    }
}
