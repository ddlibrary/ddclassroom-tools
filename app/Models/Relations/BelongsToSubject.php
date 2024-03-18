<?php

namespace App\Models\Relations;

use App\Models\Subject;

trait BelongsToSubject
{
    public function subject()
    {
        return $this->BelongsTo(Subject::class);
    }
}
