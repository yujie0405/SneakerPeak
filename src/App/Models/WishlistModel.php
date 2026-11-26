<?php

namespace SneakerPeak\App\Models;

use mysqli;

final class WishlistModel
{
    public static function add(mysqli $connection, string $customer, string $name): bool
    {
        $statement = $connection->prepare(
            "INSERT IGNORE INTO T_Wishlist (UserId, ProductId)
             SELECT ?, p.Id FROM T_Product p JOIN E_ProductStatus st ON st.Id = p.ProductStatusId
             WHERE p.Name = ? AND st.Code = 'AVAILABLE'
               AND NOT EXISTS (SELECT 1 FROM T_Wishlist w WHERE w.UserId = ? AND w.ProductId = p.Id)
             ORDER BY p.Id LIMIT 1"
        );
        $statement->execute([$customer, $name, $customer]);
        return $statement->affected_rows === 1;
    }

    public static function items(mysqli $connection, string $customer): array
    {
        $statement = $connection->prepare(
            'SELECT p.Name AS prod_name, p.Image1Path AS Prod_img1, sc.Name AS Prod_SubC
             FROM T_Wishlist w JOIN T_Product p ON w.ProductId = p.Id
             JOIN E_SubCategory sc ON sc.Id = p.SubCategoryId WHERE w.UserId = ?'
        );
        $statement->execute([$customer]);
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
