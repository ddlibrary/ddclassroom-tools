<?php

namespace App\Models\Relations;

use App\Models\StudentResult;

trait HasManyStudentResult
{
    public function results()
    {
        return $this->HasMany(StudentResult::class);
    }

    public function midtermResults()
    {
        return $this->HasMany(StudentResult::class, 'middle_result_id');
    }
}
