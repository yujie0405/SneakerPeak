<?php

namespace SneakerPeak\App\Models;

use mysqli;

final class UserModel
{
    public static function emailKey(string $email): string
    {
        return strtolower(trim($email));
    }

    public static function find(mysqli $db, string $email): ?array
    {
        return $db->execute_query(
            'SELECT u.Id AS id, u.DisplayName AS name, u.Email AS email, u.PasswordHash AS password,
                    u.IsActive, u.IsAdmin, u.EmailVerified, a.UserId IS NOT NULL AS HasAdmin
             FROM T_User u LEFT JOIN T_Admin a ON a.UserId = u.Id AND a.AdminFlag = 1 WHERE u.EmailKey = ?',
            [self::emailKey($email)]
        )->fetch_assoc();
    }

    public static function canAuthenticate(?array $account): bool
    {
        return $account !== null && (bool) $account['IsActive']
            && (bool) $account['IsAdmin'] === (bool) $account['HasAdmin'];
    }

    public static function saveAddress(mysqli $db, string $id, int $slot, string $address): void
    {
        if (trim($address) === '') {
            $db->execute_query('DELETE FROM T_CustomerAddress WHERE UserId = ? AND AddressSlot = ?', [$id, $slot]);
            return;
        }
        $db->execute_query(
            'INSERT INTO T_CustomerAddress (UserId, AddressSlot, AddressText) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE AddressText = VALUES(AddressText)', [$id, $slot, $address]
        );
    }

    public static function address(mysqli $db, string $id, int $slot): string
    {
        return $db->execute_query('SELECT AddressText FROM T_CustomerAddress WHERE UserId = ? AND AddressSlot = ?',
            [$id, $slot])->fetch_row()[0] ?? '';
    }
}
