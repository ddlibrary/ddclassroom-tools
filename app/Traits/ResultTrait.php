<?php

namespace App\Traits;

trait ResultTrait
{
    private function result($percentage)
    {
        if ($percentage >= 90) {
            return 1;
        } elseif ($percentage >= 75) {
            return 2;
        } elseif ($percentage >= 60) {
            return 3;
        } elseif ($percentage >= 50) {
            return 4;
        }

        return 5;
    }
}
