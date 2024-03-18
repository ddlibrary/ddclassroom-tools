<?php

namespace App\Models\Relations;

use App\Models\AttendanceDetail;

trait HasOneAttendanceDetail
{
    public function attendanceDetail()
    {
        return $this->HasOne(AttendanceDetail::class);
    }

    public function middleAttendanceDetail()
    {
        return $this->HasOne(AttendanceDetail::class)->where('type', 1);
    }

    public function finalAttendanceDetail()
    {
        return $this->HasOne(AttendanceDetail::class)->where('type', 2);
    }
}
