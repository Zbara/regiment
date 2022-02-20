<?php

namespace App\Service;

class Libs
{
    public function rename(array $endings, $number): string
    {
        $cases = [2, 0, 1, 1, 1, 2];
        $n = ($number >= 0) ? $number : 0;

        return sprintf($endings[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]], $n);
    }

}
