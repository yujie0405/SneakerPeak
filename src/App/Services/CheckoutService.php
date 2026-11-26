<?php

namespace SneakerPeak\App\Services;

use DomainException;
use mysqli;
use SneakerPeak\App\Models\CartModel;
use SneakerPeak\App\Models\PromotionModel;
use Throwable;

require_once dirname(__DIR__) . '/Models/CartModel.php';
require_once dirname(__DIR__) . '/Models/PromotionModel.php';

final class CheckoutService
{
    public static function quote(array $items, ?array $promotion): array
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (int) round(100 * (float) $item['Current_Price']) * (int) $item['Select_qty'];
        }

        $shipping = $subtotal >= 50000 ? 0 : 2000;
        $value = (float) ($promotion['Value'] ?? 0);
        $discount = match ($promotion['Promote_type'] ?? null) {
            'Value Off' => (int) round(100 * $value),
            'Percentage Off' => (int) round($subtotal * $value / 100),
            default => 0,
        };
        if (($promotion['Promote_type'] ?? null) === 'Free Shipping') {
            $shipping = 0;
        }
        $discount = min($subtotal, max(0, $discount));

        return [
            'subtotal' => $subtotal / 100,
            'shipping' => $shipping / 100,
            'discount' => $discount / 100,
            'total' => ($subtotal - $discount + $shipping) / 100,
        ];
    }

    public static function start(mysqli $connection, string $customerId, string $promotionCode): string
    {
        $connection->begin_transaction();
        try {
            $lock = $connection->prepare('SELECT id FROM customer WHERE id = ? FOR UPDATE');
            $lock->execute([$customerId]);
            if (!$lock->get_result()->fetch_row()) {
                throw new DomainException('Please sign in to continue.');
            }
            $items = CartModel::items($connection, $customerId);
            if (!$items) {
                throw new DomainException('Your cart is empty.');
            }

            $inventory = $connection->prepare(
                "SELECT Prod_Qty FROM sizeandqty WHERE Prod_Name = ? AND Prod_Size = ?
                 AND Qty_status = 'Available'"
            );
            foreach ($items as $item) {
                $inventory->execute([$item['Product_Name'], $item['Select_Size']]);
                $stock = $inventory->get_result()->fetch_assoc();
                if ($item['Current_Price'] === null || (int) $item['Select_qty'] < 1
                    || (int) $item['Select_qty'] > (int) ($stock['Prod_Qty'] ?? 0)) {
                    throw new DomainException('An item in your cart is no longer available in the selected quantity.');
                }
            }

            $promotion = $promotionCode === '' ? null : PromotionModel::availableFor($connection, $customerId, $promotionCode);
            if ($promotionCode !== '' && $promotion === null) {
                throw new DomainException('The promotion code is no longer available.');
            }
            $quote = self::quote($items, $promotion);
            $date = date('d/m/Y');
            $codeCheck = $connection->prepare('SELECT ID FROM oder WHERE Order_code = ?');
            do {
                $orderCode = (string) random_int(100000, 999999);
                $codeCheck->execute([$orderCode]);
            } while ($codeCheck->get_result()->fetch_row());

            $order = $connection->prepare(
                "INSERT INTO oder
                 (UID, Order_code, Order_Date, Order_TotalAmmount, ShippingFeed, Payment_Type, Payment_Status, Order_Status, Promote_Code, ask)
                 VALUES (?, ?, ?, ?, ?, 'unset', 'Paying', 'Pending', ?, '0')"
            );
            $order->execute([$customerId, $orderCode, $date, $quote['total'] - $quote['shipping'], $quote['shipping'], $promotionCode]);
            $line = $connection->prepare(
                'INSERT INTO oder_details (UID, Order_code, Order_Product, Order_ProdcutP, Order_Size, Order_qty, Order_Day)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            foreach ($items as $item) {
                $line->execute([
                    $customerId, $orderCode, $item['Product_Name'], $item['Current_Price'],
                    $item['Select_Size'], $item['Select_qty'], $date,
                ]);
            }
            $connection->commit();
            return $orderCode;
        } catch (Throwable $error) {
            $connection->rollback();
            throw $error;
        }
    }

    public static function completeDemo(mysqli $connection, string $customerId, string $orderCode, string $method): void
    {
        $connection->begin_transaction();
        try {
            $order = $connection->prepare(
                'SELECT Payment_Status, Payment_Type FROM oder WHERE UID = ? AND Order_code = ? FOR UPDATE'
            );
            $order->execute([$customerId, $orderCode]);
            $current = $order->get_result()->fetch_assoc();
            if (!$current || $current['Payment_Type'] !== $method) {
                throw new DomainException('This checkout no longer matches the selected payment method.');
            }
            if ($current['Payment_Status'] === 'Complete') {
                $connection->commit();
                return;
            }
            if ($current['Payment_Status'] !== 'Paying') {
                throw new DomainException('This order is no longer awaiting payment.');
            }

            $lines = $connection->prepare(
                'SELECT Order_Product, Order_Size, Order_qty FROM oder_details
                 WHERE UID = ? AND Order_code = ? ORDER BY Order_Product, Order_Size'
            );
            $lines->execute([$customerId, $orderCode]);
            $items = $lines->get_result()->fetch_all(MYSQLI_ASSOC);
            if (!$items) {
                throw new DomainException('The order has no items.');
            }

            $stock = $connection->prepare(
                "UPDATE sizeandqty
                 SET Qty_status = CASE WHEN CAST(Prod_Qty AS SIGNED) = ? THEN 'UnAvailable' ELSE Qty_status END,
                     Prod_Qty = CAST(Prod_Qty AS SIGNED) - ?
                 WHERE Prod_Name = ? AND Prod_Size = ? AND Qty_status = 'Available'
                   AND CAST(Prod_Qty AS SIGNED) >= ?"
            );
            $cart = $connection->prepare(
                'DELETE FROM cart WHERE UID = ? AND Product_Name = ? AND Select_Size = ? AND Select_qty = ?'
            );
            foreach ($items as $item) {
                $quantity = (int) $item['Order_qty'];
                if ($quantity < 1) {
                    throw new DomainException('The order contains an invalid quantity.');
                }
                $stock->execute([$quantity, $quantity, $item['Order_Product'], $item['Order_Size'], $quantity]);
                if ($stock->affected_rows !== 1) {
                    throw new DomainException('An item is no longer available in the requested quantity.');
                }
                $cart->execute([$customerId, $item['Order_Product'], $item['Order_Size'], $quantity]);
            }

            $update = $connection->prepare("UPDATE oder SET Payment_Status = 'Complete' WHERE UID = ? AND Order_code = ?");
            $update->execute([$customerId, $orderCode]);
            $connection->commit();
        } catch (Throwable $error) {
            $connection->rollback();
            throw $error;
        }
    }
}
