<?php

namespace App\Models\Relations;

use App\Models\SubGrade;

trait BelongsToSubGrade
{
    public function subGrade()
    {
        return $this->belongsTo(SubGrade::class);
    }
}
