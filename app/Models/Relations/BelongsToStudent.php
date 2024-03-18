<?php

namespace App\Models\Relations;

use App\Models\Student;

trait BelongsToStudent
{
    public function student()
    {
        return $this->BelongsTo(Student::class);
    }
}
