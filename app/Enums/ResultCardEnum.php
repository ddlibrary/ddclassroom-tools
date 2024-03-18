<?php

namespace App\Enums;

enum ResultCardEnum: string
{
    case Passed = 'کامیاب';

    case Repeat = 'تکرار صنف';

    case Failed = 'ناکام';

    case TrayAgain = 'مشروط';
}
