<?php

namespace App\Models\Relations;

use App\Models\Attendance;

trait HasOneStudentAttendance
{
    public function middleAttendance()
    {
        return $this->HasOne(Attendance::class)->where('type', 1);
    }

    public function finalAttendance()
    {
        return $this->HasOne(Attendance::class)->where('type', 2);
    }

    public function attendance()
    {
        return $this->HasOne(Attendance::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
