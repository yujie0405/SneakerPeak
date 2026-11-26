<?php

namespace SneakerPeak\App\Services;

use mysqli;

final class AuthService
{
    public static function verify(string $password, string $stored): bool
    {
        return password_get_info($stored)['algo'] === null
            ? hash_equals($stored, $password)
            : password_verify($password, $stored);
    }

    public static function canHash(string $password): bool
    {
        return $password !== '' && strlen($password) <= 72 && !str_contains($password, "\0");
    }

    public static function replacePassword(mysqli $connection, string $role, string $id, string $stored, string $new): bool
    {
        if (!self::canHash($new)) {
            return false;
        }
        $table = $role === 'admin' ? 'admin' : 'customer';
        $statement = $connection->prepare("UPDATE `$table` SET password = ? WHERE id = ? AND BINARY password = ?");
        $statement->execute([password_hash($new, PASSWORD_DEFAULT), $id, $stored]);
        return $statement->affected_rows === 1;
    }

    public static function changePassword(
        mysqli $connection, string $role, string $id, string $old, string $new, string $confirmation
    ): bool {
        if ($new !== $confirmation || strlen($new) < 8) {
            return false;
        }
        $table = $role === 'admin' ? 'admin' : 'customer';
        $statement = $connection->prepare("SELECT password FROM `$table` WHERE id = ?");
        $statement->execute([$id]);
        $stored = $statement->get_result()->fetch_row()[0] ?? null;
        return $stored !== null && self::verify($old, $stored)
            && self::replacePassword($connection, $role, $id, $stored, $new);
    }
}
