<?php

namespace App\Support;

final class Money
{
    /**
     * Normalize a monetary value to an integer amount of VND using round half-up.
     */
    public static function vnd(mixed $value): int
    {
        if ($value === null) {
            return 0;
        }

        if (is_string($value)) {
            $value = trim($value);
            if ($value === '') {
                return 0;
            }
        }

        if (!is_numeric($value)) {
            return 0;
        }

        return (int) round((float) $value, 0, PHP_ROUND_HALF_UP);
    }
}

