<?php

namespace SneakerPeak\App\Models;

use mysqli;

final class OrderModel
{
    public const PAYMENT_TYPES = ['Card_Payment' => 'Card payment', 'QR_Payment' => 'QR payment', 'unset' => 'Not selected'];
    public const PAYMENT_STATUSES = ['Paying' => 'Pending', 'Failed' => 'Failed', 'Complete' => 'Complete'];
    public const UNPAID_STATUSES = ['Paying', 'Failed'];
    public const ORDER_STATUSES = ['Pending' => 'Pending', 'Shipping' => 'Shipping', 'Shipped' => 'Shipped', 'Complete' => 'Complete'];

    public const FIELDS = "Id AS ID, UserId AS UID, OrderCode AS Order_code, CurrencyCode,
        DATE_FORMAT(OrderedOn, '%d/%m/%Y') AS Order_Date, NetItemsAmount AS Order_TotalAmmount,
        ShippingFee AS ShippingFeed, PaymentType AS Payment_Type, PaymentStatus AS Payment_Status,
        OrderStatus AS Order_Status, PromotionCodeSnapshot AS Promote_Code, ConfirmationRequested AS ask,
        ShippingAddressSnapshot AS Order_Address, ShippingRecipientSnapshot AS Receiver_Name,
        ShippingPhoneSnapshot AS Receiver_Contract";

    public const HISTORY_TABS = [
        'payment' => 'Payment pending / failed',
        'shipping' => 'Shipping',
        'complete' => 'Complete',
    ];

    public const HISTORY_SORTS = [
        'newest' => 'Newest first',
        'oldest' => 'Oldest first',
        'highest' => 'Highest total',
        'lowest' => 'Lowest total',
    ];

    public static function history(mysqli $connection, string $customer, string $sort, array $filters, string $tab): array
    {
        $orderBy = match ($sort) {
            'oldest' => 'OrderedOn IS NULL, OrderedOn ASC, Id ASC',
            'highest' => '(NetItemsAmount + ShippingFee) DESC, Id DESC',
            'lowest' => '(NetItemsAmount + ShippingFee) ASC, Id DESC',
            default => 'OrderedOn IS NULL, OrderedOn DESC, Id DESC',
        };
        $tabCondition = match ($tab) {
            'shipping' => "PaymentStatus = 'Complete' AND OrderStatus IN ('Pending', 'Shipping', 'Shipped')",
            'complete' => "PaymentStatus = 'Complete' AND OrderStatus = 'Complete'",
            default => "PaymentStatus IN ('Paying', 'Failed')",
        };
        $conditions = ['UserId = ?', $tabCondition];
        $values = [$customer];
        foreach ([
            'code' => 'LOCATE(?, OrderCode) > 0',
            'from' => 'OrderedOn >= ?',
            'to' => 'OrderedOn <= ?',
            'order_status' => 'OrderStatus = ?',
            'payment_status' => 'PaymentStatus = ?',
        ] as $key => $condition) {
            if (($filters[$key] ?? '') !== '') {
                $conditions[] = $condition;
                $values[] = $filters[$key];
            }
        }
        return $connection->execute_query(
            'SELECT ' . self::FIELDS . ', (SELECT SUM(Qty) FROM T_OrderLineItem WHERE OrderId = T_Order.Id) AS ItemQuantity
             FROM T_Order WHERE ' . implode(' AND ', $conditions) . ' ORDER BY ' . $orderBy,
            $values
        )->fetch_all(MYSQLI_ASSOC);
    }


    public static function owned(mysqli $connection, string $customer, string $code): ?array
    {
        return $connection->execute_query('SELECT ' . self::FIELDS . ' FROM T_Order WHERE UserId = ? AND OrderCode = ?', [$customer, $code])->fetch_assoc();
    }

    public static function confirmReceipt(mysqli $connection, string $customer, string $code): bool
    {
        $connection->execute_query(
            "UPDATE T_Order SET ConfirmationRequested = 0, OrderStatus = 'Complete'
             WHERE UserId = ? AND OrderCode = ? AND ConfirmationRequested <> 0",
            [$customer, $code]
        );
        return $connection->affected_rows === 1;
    }


    public static function lines(mysqli $connection, string $customer, string $code): array
    {
        return $connection->execute_query(
            "SELECT l.ProductNameSnapshot AS Order_Product, l.SizeLabelSnapshot AS Order_Size,
                l.UnitPrice AS Order_ProdcutP, l.Qty AS Order_qty
             FROM T_OrderLineItem l JOIN T_Order o ON o.Id = l.OrderId WHERE o.UserId = ? AND o.OrderCode = ? ORDER BY l.Id",
            [$customer, $code]
        )->fetch_all(MYSQLI_ASSOC);
    }
}
