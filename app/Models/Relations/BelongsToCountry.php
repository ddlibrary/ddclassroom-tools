<?php

namespace App\Models\Relations;

use App\Models\Country;

trait BelongsToCountry
{
    public function country()
    {
        return $this->BelongsTo(Country::class);
    }
}
