<?php

namespace SneakerPeak\App\Services;

use DomainException;
use mysqli;
use SneakerPeak\App\Models\CartModel;
use SneakerPeak\App\Models\PromotionModel;
use SneakerPeak\App\Models\OrderModel;
use Throwable;

require_once dirname(__DIR__) . '/Models/CartModel.php';
require_once dirname(__DIR__) . '/Models/PromotionModel.php';
require_once dirname(__DIR__) . '/Models/OrderModel.php';

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
        $type = $promotion['TypeCode'] ?? null;
        $discount = match ($type) {
            'FIXED' => (int) round(100 * $value),
            'PERCENTAGE' => (int) round($subtotal * $value / 100),
            default => 0,
        };
        if ($type === 'FREE_SHIPPING') {
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
            $lock = $connection->prepare('SELECT Id FROM T_User WHERE Id = ? AND IsAdmin = 0 AND IsActive = 1 FOR UPDATE');
            $lock->execute([$customerId]);
            if (!$lock->get_result()->fetch_row()) {
                throw new DomainException('Please sign in to continue.');
            }
            $items = CartModel::items($connection, $customerId);
            if (!$items) {
                throw new DomainException('Your cart is empty.');
            }

            $inventory = $connection->prepare(
                "SELECT Qty AS Prod_Qty FROM T_ProductSizeAndCategory WHERE ProductId = ? AND SizeId = ? AND IsAvailable = 1"
            );
            foreach ($items as $item) {
                $inventory->execute([$item['ProductId'], $item['SizeId']]);
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
            $date = date('Y-m-d');
            $codeCheck = $connection->prepare('SELECT Id FROM T_Order WHERE OrderCode = ?');
            do {
                $orderCode = (string) random_int(100000, 999999);
                $codeCheck->execute([$orderCode]);
            } while ($codeCheck->get_result()->fetch_row());

            $order = $connection->prepare(
                "INSERT INTO T_Order
                 (UserId, OrderCode, OrderedOn, NetItemsAmount, ShippingFee, PaymentType, PaymentStatus, OrderStatus, PromotecodeId, PromotionCodeSnapshot)
                 VALUES (?, ?, ?, ?, ?, 'unset', 'Paying', 'Pending', ?, ?)"
            );
            $order->execute([$customerId, $orderCode, $date, $quote['total'] - $quote['shipping'], $quote['shipping'], $promotion['Id'] ?? null, $promotion['Code'] ?? null]);
            $orderId = $connection->insert_id;
            $line = $connection->prepare(
                'INSERT INTO T_OrderLineItem (OrderId, ProductId, SizeId, ProductNameSnapshot, UnitPrice, SizeLabelSnapshot, Qty, RecordedOn)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );
            foreach ($items as $item) {
                $line->execute([
                    $orderId, $item['ProductId'], $item['SizeId'], $item['Product_Name'], $item['Current_Price'],
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

    public static function recordDemoPayment(mysqli $connection, string $customerId, string $orderCode, string $method, string $status): void
    {
        if (!isset(OrderModel::PAYMENT_STATUSES[$status])) {
            throw new DomainException('Choose a demo payment outcome.');
        }

        $connection->begin_transaction();
        try {
            $order = $connection->prepare(
                'SELECT Id, PaymentStatus AS Payment_Status, PaymentType AS Payment_Type FROM T_Order WHERE UserId = ? AND OrderCode = ? FOR UPDATE'
            );
            $order->execute([$customerId, $orderCode]);
            $current = $order->get_result()->fetch_assoc();
            if (!$current || $current['Payment_Type'] !== $method) {
                throw new DomainException('This checkout no longer matches the selected payment method.');
            }
            if ($current['Payment_Status'] === 'Complete' && $status === 'Complete') {
                $connection->commit();
                return;
            }
            if (!in_array($current['Payment_Status'], OrderModel::UNPAID_STATUSES, true)) {
                throw new DomainException('This order is no longer awaiting payment.');
            }

            $update = $connection->prepare('UPDATE T_Order SET PaymentStatus = ? WHERE Id = ?');
            if ($status !== 'Complete') {
                $update->execute([$status, $current['Id']]);
                $connection->commit();
                return;
            }

            $lines = $connection->prepare(
                'SELECT ProductId, SizeId, Qty AS Order_qty FROM T_OrderLineItem WHERE OrderId = ? ORDER BY ProductId, SizeId'
            );
            $lines->execute([$current['Id']]);
            $items = $lines->get_result()->fetch_all(MYSQLI_ASSOC);
            if (!$items) {
                throw new DomainException('The order has no items.');
            }

            $stock = $connection->prepare(
                "UPDATE T_ProductSizeAndCategory SET IsAvailable = IF(Qty = ?, 0, IsAvailable), Qty = Qty - ?
                 WHERE ProductId = ? AND SizeId = ? AND IsAvailable = 1 AND Qty >= ?"
            );
            $cart = $connection->prepare(
                'DELETE FROM T_Cart WHERE UserId = ? AND ProductId = ? AND SizeId = ? AND Qty = ?'
            );
            foreach ($items as $item) {
                $quantity = (int) $item['Order_qty'];
                if ($quantity < 1) {
                    throw new DomainException('The order contains an invalid quantity.');
                }
                $stock->execute([$quantity, $quantity, $item['ProductId'], $item['SizeId'], $quantity]);
                if ($stock->affected_rows !== 1) {
                    throw new DomainException('An item is no longer available in the requested quantity.');
                }
                $cart->execute([$customerId, $item['ProductId'], $item['SizeId'], $quantity]);
            }

            $update->execute([$status, $current['Id']]);
            $connection->commit();
        } catch (Throwable $error) {
            $connection->rollback();
            throw $error;
        }
    }
}
