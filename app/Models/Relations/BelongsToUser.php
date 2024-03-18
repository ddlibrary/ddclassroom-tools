<?php

namespace App\Models\Relations;

use App\Models\User;

trait BelongsToUser
{
    public function user()
    {
        return $this->BelongsTo(User::class);
    }

    public function teacher()
    {
        return $this->BelongsTo(User::class, 'teacher_id');
    }
}
