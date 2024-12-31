<?php

namespace App\Models;

use App\Models\Relations\BelongsToMonth;
use App\Models\Relations\BelongsToStudent;
use App\Models\Relations\BelongsToSubGrade;
use App\Models\Relations\BelongsToSubject;
use App\Models\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use BelongsToMonth, BelongsToStudent, BelongsToSubGrade, BelongsToSubject, BelongsToUser;

    protected $guarded = [];
}
