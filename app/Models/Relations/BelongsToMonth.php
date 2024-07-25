<?php

namespace App\Models\Relations;

use App\Models\Month;

trait BelongsToMonth
{
    public function month()
    {
        return $this->BelongsTo(Month::class);
    }
}
