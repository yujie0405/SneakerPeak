<?php

namespace SneakerPeak\App\Models;

use mysqli;
use Throwable;

final class CartModel
{
    public static function count(mysqli $connection, string $customerId): int
    {
        $statement = $connection->prepare('SELECT COUNT(*) FROM cart WHERE UID = ?');
        $statement->execute([$customerId]);
        return (int) $statement->get_result()->fetch_row()[0];
    }

    public static function items(mysqli $connection, string $customerId): array
    {
        $statement = $connection->prepare(
            "SELECT cart.*, product.Prod_Price AS Current_Price
             FROM cart
             LEFT JOIN product ON product.Prod_Name = cart.Product_Name
                AND product.Prod_Status = 'available'
             WHERE cart.UID = ? ORDER BY cart.ID"
        );
        $statement->execute([$customerId]);
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function remove(mysqli $connection, string $customerId, string $name, string $size): void
    {
        $statement = $connection->prepare('DELETE FROM cart WHERE UID = ? AND Product_Name = ? AND Select_Size = ?');
        $statement->execute([$customerId, $name, $size]);
    }

    public static function add(mysqli $connection, string $customerId, array $product, string $size, int $quantity): bool
    {
        if ($quantity < 1) {
            return false;
        }
        $connection->begin_transaction();
        try {
            $customer = $connection->prepare('SELECT id FROM customer WHERE id = ? FOR UPDATE');
            $customer->execute([$customerId]);
            if (!$customer->get_result()->fetch_row()) {
                $connection->rollback();
                return false;
            }
            $current = $connection->prepare(
                'SELECT ID, Select_qty FROM cart WHERE UID = ? AND Product_Name = ? AND Select_Size = ? LIMIT 1'
            );
            $current->execute([$customerId, $product['Prod_Name'], $size]);
            $item = $current->get_result()->fetch_assoc();
            $total = $quantity + (int) ($item['Select_qty'] ?? 0);

            $inventory = $connection->prepare(
                "SELECT Prod_Qty FROM sizeandqty WHERE Prod_Name = ? AND Prod_Size = ? AND Qty_status = 'Available'"
            );
            $inventory->execute([$product['Prod_Name'], $size]);
            $stock = (int) ($inventory->get_result()->fetch_row()[0] ?? 0);
            if ($total > $stock) {
                $connection->rollback();
                return false;
            }

            if ($item) {
                $statement = $connection->prepare('UPDATE cart SET Select_qty = ? WHERE UID = ? AND ID = ?');
                $statement->execute([$total, $customerId, $item['ID']]);
            } else {
                $statement = $connection->prepare(
                    'INSERT INTO cart (UID, Prod_Img1, Product_Name, Prod_Price, Select_Size, Select_qty)
                     VALUES (?, ?, ?, ?, ?, ?)'
                );
                $statement->execute([$customerId, $product['Prod_img1'], $product['Prod_Name'], $product['Prod_Price'], $size, $quantity]);
            }
            $connection->commit();
            return true;
        } catch (Throwable $error) {
            $connection->rollback();
            throw $error;
        }
    }
}
