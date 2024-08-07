<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    public function midtermStudentResults(){
        return $this->hasMany(StudentResult::class, 'middle_result_id');
    }

    public function finalStudentResults(){
        return $this->hasMany(StudentResult::class, 'result_id');
    }
}
