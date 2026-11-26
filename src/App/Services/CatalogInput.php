<?php

namespace SneakerPeak\App\Services;

final class CatalogInput
{
    public const MAX_QUANTITY = 4294967295;

    public static function text(array $input, string $field): string
    {
        return is_string($input[$field] ?? null) ? trim($input[$field]) : '';
    }

    public static function validPrice(string $value): bool
    {
        return preg_match('/^(?:[0-9]{1,11}(?:\.[0-9]{0,2})?|\.[0-9]{1,2})$/D', $value) === 1;
    }

    public static function quantity(mixed $value): int|false
    {
        return filter_var($value, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => self::MAX_QUANTITY],
        ]);
    }

    public static function id(mixed $value): ?string
    {
        if (!is_string($value) || !preg_match('/^[1-9][0-9]{0,19}$/D', $value)
            || (strlen($value) === 20 && strcmp($value, '18446744073709551615') > 0)) {
            return null;
        }
        return $value;
    }
}
