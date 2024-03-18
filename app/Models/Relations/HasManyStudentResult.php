<?php

namespace App\Models\Relations;

use App\Models\StudentResult;

trait HasManyStudentResult
{
    public function results()
    {
        return $this->HasMany(StudentResult::class);
    }
}
