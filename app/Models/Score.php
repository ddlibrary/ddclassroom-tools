<?php

namespace App\Models;

use App\Models\Relations\BelongsToStudent;
use App\Models\Relations\BelongsToSubGrade;
use App\Models\Relations\BelongsToSubject;
use App\Models\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use BelongsToStudent, BelongsToSubGrade, BelongsToSubject, BelongsToUser;

    protected $guarded = [];
}
