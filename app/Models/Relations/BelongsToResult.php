<?php

namespace App\Models\Relations;

use App\Models\Result;

trait BelongsToResult
{
    public function result()
    {
        return $this->BelongsTo(Result::class, 'result_id');
    }

    public function finalResult()
    {
        return $this->BelongsTo(Result::class, 'final_result_id');
    }

    public function middleResult()
    {
        return $this->BelongsTo(Result::class, 'middle_result_id');
    }
}
