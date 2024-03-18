<?php

namespace App\Models\Relations;

use App\Models\Year;

trait BelongsToYear
{
    public function year()
    {
        return $this->BelongsTo(Year::class);
    }
}
