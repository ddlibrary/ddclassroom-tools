<?php

namespace App\Traits;

use App\Enums\ResultTypeEnum;

trait ResultTypeTrait
{
    private function resultType($resultName, $exam)
    {
        if ($exam == 'midterm') {
            if ($resultName == 'کامیاب') {
                return ResultTypeEnum::MidtermSuccess->value;
            }

            return ResultTypeEnum::MidtermFailed->value;
        } else {
            if ($resultName == 'کامیاب') {
                return ResultTypeEnum::FinalSuccess->value;
            } elseif ($resultName == 'تکرار صنف') {
                return ResultTypeEnum::FinalFailed->value;
            }

            return ResultTypeEnum::FinalConditional->value;
        }

    }
}
