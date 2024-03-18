<?php

namespace App\Models;

use App\Models\Relations\BelongsToStudent;
use App\Models\Relations\BelongsToSubGrade;
use App\Models\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class ClassResponsible extends Model
{
    use BelongsToStudent, BelongsToSubGrade, BelongsToUser;
}
