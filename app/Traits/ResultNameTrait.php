<?php

namespace App\Traits;

use App\Enums\ResultCardEnum;

trait ResultNameTrait
{
    private function resultStatus($grade, $studentResult, $type = 1)
    {
        if ($type == 3) {
            if ($grade->total_subjects > ($studentResult->subject_passed + 3) || $studentResult->result_id == 5) {
                return ResultCardEnum::Repeat->value;
            } elseif ($grade->total_subjects > ($studentResult->subject_passed) && $studentResult->result_id != 5) {
                return ResultCardEnum::TrayAgain->value;
            }
        } elseif ($type == 2) {
            if ($grade->total_subjects > ($studentResult->final_subject_passed + 3) || $studentResult->final_result_id == 5) {
                return ResultCardEnum::Repeat->value;
            } elseif ($grade->total_subjects > ($studentResult->final_subject_passed) && $studentResult->final_result_id != 5) {
                return ResultCardEnum::TrayAgain->value;
            }
        } else {
            if ($grade->total_subjects > $studentResult->middle_subject_passed || $studentResult->middle_result_id == 5) {
                return 'ناکام';
            }
        }

        return 'کامیاب';
    }
}
