<?php

namespace SneakerPeak\App\Models;

use mysqli;

final class WishlistModel
{
    public static function add(mysqli $connection, string $customer, string $name): bool
    {
        $statement = $connection->prepare(
            "INSERT INTO wishlist (UID, prod_name, Pimg1)
             SELECT ?, Prod_Name, Prod_img1 FROM product p
             WHERE Prod_Name = ? AND Prod_Status = 'available'
               AND NOT EXISTS (SELECT 1 FROM wishlist w WHERE w.UID = ? AND w.prod_name = p.Prod_Name)
             LIMIT 1"
        );
        $statement->execute([$customer, $name, $customer]);
        return $statement->affected_rows === 1;
    }

    public static function items(mysqli $connection, string $customer): array
    {
        $statement = $connection->prepare(
            'SELECT w.prod_name, p.Prod_img1, p.Prod_SubC FROM wishlist w
             JOIN product p ON w.prod_name = p.Prod_Name WHERE w.UID = ?'
        );
        $statement->execute([$customer]);
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
