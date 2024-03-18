<?php

namespace App\Models;

use App\Models\Relations\BelongsToGrade;
use App\Models\Relations\HasManyStudentResult;
use App\Models\Relations\HasOneResponsible;
use Illuminate\Database\Eloquent\Model;

class SubGrade extends Model
{
    use BelongsToGrade, HasManyStudentResult, HasOneResponsible;

    protected $guarded = [];
}
