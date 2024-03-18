<?php

namespace App\Models;

use App\Models\Relations\BelongsToResult;
use App\Models\Relations\BelongsToStudent;
use App\Models\Relations\BelongsToSubGrade;
use App\Models\Relations\BelongsToSubject;
use App\Models\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use BelongsToResult, BelongsToResult, BelongsToStudent, BelongsToSubGrade, BelongsToSubject, BelongsToUser;

    protected $guarded = [];

    protected function totalClassHours(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (int) $value,
        );
    }

    protected function present(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (int) $value,
        );
    }

    protected function absent(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (int) $value,
        );
    }

    protected function permission(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (int) $value,
        );
    }

    protected function patient(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (int) $value,
        );
    }
}
