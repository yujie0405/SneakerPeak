<?php

namespace SneakerPeak\App\Models;

use mysqli;

final class OrderModel
{
    public static function history(mysqli $connection, string $customer): array
    {
        $statement = $connection->prepare('SELECT * FROM oder WHERE UID = ?');
        $statement->execute([$customer]);
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function owned(mysqli $connection, string $customer, string $code): ?array
    {
        $statement = $connection->prepare('SELECT * FROM oder WHERE UID = ? AND Order_code = ? LIMIT 1');
        $statement->execute([$customer, $code]);
        return $statement->get_result()->fetch_assoc();
    }

    public static function lines(mysqli $connection, string $customer, string $code): array
    {
        $statement = $connection->prepare('SELECT * FROM oder_details WHERE UID = ? AND Order_code = ? ORDER BY ID');
        $statement->execute([$customer, $code]);
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
