<?php

namespace App\Models;

use App\Models\Relations\BelongsToSubGrade;
use App\Models\Relations\BelongsToSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubGradeSubjectSemester extends Model
{
    use HasFactory, BelongsToSubGrade, BelongsToSubject;

    protected $guarded = [];
}
