<?php

namespace App\Models\Relations;

use App\Models\Enrollment;

trait HasManyEnrollment
{
    public function enrollments()
    {
        return $this->HasMany(Enrollment::class);
    }
}
