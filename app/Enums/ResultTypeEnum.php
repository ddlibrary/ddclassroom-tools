<?php

namespace App\Enums;

enum ResultTypeEnum: int
{
    case MidtermSuccess = 1;

    case MidtermFailed = 2;

    case FinalSuccess = 3;

    case FinalConditional = 4;

    case FinalFailed = 5;
}
