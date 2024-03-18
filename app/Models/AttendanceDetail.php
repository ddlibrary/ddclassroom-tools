<?php

namespace App\Models;

use App\Models\Relations\BelongsToStudent;
use App\Models\Relations\BelongsToSubGrade;
use App\Models\Relations\BelongsToSubject;
use App\Models\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceDetail extends Model
{
    use HasFactory, BelongsToUser, BelongsToSubGrade, BelongsToStudent, BelongsToSubject;

    protected $guarded = [];
}
