<?php

namespace App\Models;

use App\Models\Relations\BelongsToStudent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use BelongsToStudent, HasFactory;

    protected $guarded = [];
}
