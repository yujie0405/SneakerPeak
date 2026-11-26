<?php

namespace SneakerPeak\App\Models;

use mysqli;
use Throwable;

final class CartModel
{
    public const ITEMS = "SELECT c.Id AS ID, c.ProductId, c.SizeId, p.Name AS Product_Name,
                p.Image1Path AS Prod_Img1, z.SizeLabel AS Select_Size, c.Qty AS Select_qty,
                CASE WHEN st.Code = 'AVAILABLE' THEN p.Price END AS Current_Price
             FROM T_Cart c JOIN T_Product p ON p.Id = c.ProductId JOIN E_Size z ON z.Id = c.SizeId
             JOIN E_ProductStatus st ON st.Id = p.ProductStatusId";

    public static function count(mysqli $connection, string $customerId): int
    {
        return (int) $connection->execute_query('SELECT COUNT(*) FROM T_Cart WHERE UserId = ?', [$customerId])->fetch_row()[0];
    }

    public static function items(mysqli $connection, string $customerId): array
    {
        return $connection->execute_query(self::ITEMS . ' WHERE c.UserId = ? ORDER BY c.Id', [$customerId])->fetch_all(MYSQLI_ASSOC);
    }

    public static function remove(mysqli $connection, string $customerId, string $name, string $size): void
    {
        $connection->execute_query(
            'DELETE c FROM T_Cart c JOIN T_Product p ON p.Id=c.ProductId JOIN E_Size z ON z.Id=c.SizeId
             WHERE c.UserId = ? AND p.Name = ? AND z.SizeLabel = ?',
            [$customerId, $name, $size]
        );
    }

    public static function changeQuantity(mysqli $connection, string $customerId, string $name, string $size, int $delta): void
    {
        $connection->execute_query(
            "UPDATE T_Cart c JOIN T_Product p ON p.Id = c.ProductId JOIN E_Size z ON z.Id = c.SizeId
             JOIN T_ProductSizeAndCategory i ON i.ProductId = c.ProductId AND i.SizeId = c.SizeId
             JOIN E_ProductStatus st ON st.Id = p.ProductStatusId
             SET c.Qty = CAST(c.Qty AS SIGNED) + ?
             WHERE c.UserId = ? AND p.Name = ? AND z.SizeLabel = ?
               AND CAST(c.Qty AS SIGNED) + ? >= 1
               AND (? < 0 OR (i.IsAvailable = 1 AND st.Code = 'AVAILABLE' AND c.Qty + ? <= i.Qty))",
            [$delta, $customerId, $name, $size, $delta, $delta, $delta]
        );
    }

    public static function add(mysqli $connection, string $customerId, array $product, string $size, int $quantity): bool
    {
        if ($quantity < 1) return false;
        $connection->begin_transaction();
        try {
            $customer = $connection->execute_query('SELECT Id FROM T_User WHERE Id = ? AND IsActive = 1 AND IsAdmin = 0 FOR UPDATE', [$customerId]);
            $stock = $connection->execute_query(
                "SELECT i.SizeId, i.Qty FROM T_ProductSizeAndCategory i JOIN E_Size z ON z.Id = i.SizeId
                 JOIN T_Product p ON p.Id = i.ProductId JOIN E_ProductStatus st ON st.Id = p.ProductStatusId
                 WHERE i.ProductId = ? AND z.SizeLabel = ? AND i.IsAvailable = 1 AND st.Code = 'AVAILABLE'
                 ORDER BY z.SortOrder, z.Id LIMIT 1 FOR UPDATE", [$product['Prod_ID'], $size]
            )->fetch_assoc();
            if (!$customer->fetch_row() || !$stock) {
                $connection->rollback();
                return false;
            }
            $key = [$customerId, $product['Prod_ID'], $stock['SizeId']];
            $current = (int) ($connection->execute_query(
                'SELECT Qty FROM T_Cart WHERE UserId = ? AND ProductId = ? AND SizeId = ? FOR UPDATE', $key
            )->fetch_row()[0] ?? 0);
            if ($current + $quantity > (int) $stock['Qty']) {
                $connection->rollback();
                return false;
            }
            $connection->execute_query(
                'INSERT INTO T_Cart (UserId, ProductId, SizeId, Qty) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE Qty = VALUES(Qty)',
                [...$key, $current + $quantity]
            );
            $connection->commit();
            return true;
        } catch (Throwable $error) {
            $connection->rollback();
            throw $error;
        }
    }
}
