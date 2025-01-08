<?php

namespace App\Models\Relations;

use App\Models\StudentResult;

trait HasOneStudentResult
{
    public function studentResult()
    {
        return $this->HasOne(StudentResult::class)->orderBy('id', 'desc');
    }
}
