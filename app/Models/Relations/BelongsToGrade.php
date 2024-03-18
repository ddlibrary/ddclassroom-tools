<?php

namespace App\Models\Relations;

use App\Models\Grade;

trait BelongsToGrade
{
    public function grade()
    {
        return $this->BelongsTo(Grade::class);
    }
}
