<?php

namespace App\Models;

use App\Models\Relations\BelongsToStudent;
use App\Models\Relations\BelongsToSubGrade;
use App\Models\Relations\BelongsToSubject;
use App\Models\Relations\BelongsToYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRetakeOpportunity extends Model
{
    use HasFactory, BelongsToStudent, BelongsToSubGrade, BelongsToSubject, BelongsToYear;

    protected $guarded = [];
}
