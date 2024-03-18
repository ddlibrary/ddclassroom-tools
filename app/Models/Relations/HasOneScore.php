<?php

namespace App\Models\Relations;

use App\Models\Score;

trait HasOneScore
{
    public function score()
    {
        return $this->HasOne(Score::class);
    }

    public function middle()
    {
        return $this->HasOne(Score::class)->where('type', 1);
    }

    public function final()
    {
        return $this->HasOne(Score::class)->where('type', 2);
    }

    public function finalResult()
    {
        return $this->HasOne(Score::class)->where('type', 3);
    }
}
