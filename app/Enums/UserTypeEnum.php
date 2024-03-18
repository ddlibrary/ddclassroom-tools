<?php

namespace App\Enums;

enum UserTypeEnum: int
{
    case SuperAdmin = 1;

    case Teacher = 2;

    case User = 3;
}
