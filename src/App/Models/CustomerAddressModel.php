<?php

namespace SneakerPeak\App\Models;

use DomainException;
use mysqli;

final class CustomerAddressModel
{
    public static function all(mysqli $db, string $userId): array
    {
        return $db->execute_query(
            'SELECT Id, AddressText, RecipientName, RecipientPhone FROM T_CustomerAddress WHERE UserId = ? ORDER BY Id',
            [$userId]
        )->fetch_all(MYSQLI_ASSOC);
    }

    public static function validate(string $recipient, string $phone, string $address): void
    {
        if ($recipient === '' || $phone === '' || $address === '') {
            throw new DomainException('Enter the recipient name, phone number, and shipping address.');
        }
        if (max(mb_strlen($recipient), mb_strlen($phone), mb_strlen($address)) > 255) {
            throw new DomainException('Keep each address field to 255 characters or fewer.');
        }
    }

    // The caller owns the transaction. Lock the user to serialize slot allocation.
    public static function add(mysqli $db, string $userId, string $recipient, string $phone, string $address): void
    {
        $db->execute_query('SELECT Id FROM T_User WHERE Id = ? FOR UPDATE', [$userId]);
        $slots = $db->execute_query(
            'SELECT AddressSlot FROM T_CustomerAddress WHERE UserId = ? ORDER BY AddressSlot FOR UPDATE', [$userId]
        );
        $slot = 1;
        while (($row = $slots->fetch_row()) && (int) $row[0] === $slot) {
            ++$slot;
        }
        if ($slot > 255) {
            throw new DomainException('Your address book is full. Remove an address before adding another.');
        }
        $db->execute_query(
            'INSERT INTO T_CustomerAddress (UserId, AddressSlot, RecipientName, RecipientPhone, AddressText) VALUES (?, ?, ?, ?, ?)',
            [$userId, $slot, $recipient, $phone, $address]
        );
    }

    public static function delete(mysqli $db, string $userId, string $addressId): void
    {
        $db->execute_query('DELETE FROM T_CustomerAddress WHERE UserId = ? AND Id = ?', [$userId, $addressId]);
        if ($db->affected_rows !== 1) {
            throw new DomainException('This saved address is no longer available.');
        }
    }
}
