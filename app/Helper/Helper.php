<?php

function getResultName($name)
{
    if ($name == 'کامیاب') {
        return 'Passed';
    } elseif ($name == 'ناکام') {
        return 'Failed';
    }

    return 'Repeat Course';
}
