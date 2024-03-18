<?php

namespace App\Models\Relations;

use App\Models\Score;

trait HasManyScore
{
    public function scores()
    {
        return $this->HasMany(Score::class);
    }
}
