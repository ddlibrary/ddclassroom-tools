<?php

namespace App\Models;

use App\Models\Relations\BelongsToGrade;
use App\Models\Relations\BelongsToSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeSubject extends Model
{
    use BelongsToGrade, BelongsToSubject, HasFactory;
}
