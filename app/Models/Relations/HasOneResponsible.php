<?php

namespace App\Models\Relations;

use App\Models\ClassResponsible;

trait HasOneResponsible
{
    public function responsible()
    {
        return $this->HasOne(ClassResponsible::class);
    }
}
