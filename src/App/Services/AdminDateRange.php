<?php

namespace SneakerPeak\App\Services;

final class AdminDateRange
{
    public static function valid(string $date): bool
    {
        return preg_match('/^([1-9][0-9]{3})-([0-9]{2})-([0-9]{2})$/D', $date, $parts) === 1
            && checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1]);
    }

    public static function error(mixed $from, mixed $to): ?string
    {
        foreach ([$from, $to] as $value) {
            if ($value !== '' && (!is_string($value) || !self::valid($value))) {
                return 'Enter valid dates.';
            }
        }
        return $from !== '' && $to !== '' && $from > $to ? 'The end date must be on or after the start date.' : null;
    }
}
