<?php

namespace App\Models;

use App\Models\Relations\HasOneScore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory, HasOneScore;

    protected $guarded = [];

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'grade_subjects');
    }
}
