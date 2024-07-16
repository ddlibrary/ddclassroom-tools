<?php

namespace App\Models\Relations;

use App\Models\AttendanceLog;

trait HasManyAttendanceLog
{
    public function attendanceLogs()
    {
        return $this->HasMany(AttendanceLog::class);
    }

    public function present()
    {
        return $this->HasMany(AttendanceLog::class)->where('status', 'P');
    }

    public function absent()
    {
        return $this->HasMany(AttendanceLog::class)->where('status', 'A');
    }

    public function late()
    {
        return $this->HasMany(AttendanceLog::class)->where('status', 'L');
    }

    public function excused()
    {
        return $this->HasMany(AttendanceLog::class)->where('status', 'E');
    }
}
