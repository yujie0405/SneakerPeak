<?php

namespace SneakerPeak\App\Models;

use mysqli;

final class OrderModel
{
    public const FIELDS = "Id AS ID, UserId AS UID, OrderCode AS Order_code,
        DATE_FORMAT(OrderedOn, '%d/%m/%Y') AS Order_Date, NetItemsAmount AS Order_TotalAmmount,
        ShippingFee AS ShippingFeed, PaymentType AS Payment_Type, PaymentStatus AS Payment_Status,
        OrderStatus AS Order_Status, PromotionCodeSnapshot AS Promote_Code, ConfirmationRequested AS ask,
        ShippingAddressSnapshot AS Order_Address, ShippingRecipientSnapshot AS Receiver_Name,
        ShippingPhoneSnapshot AS Receiver_Contract";

    public static function history(mysqli $connection, string $customer): array
    {
        return $connection->execute_query('SELECT ' . self::FIELDS . ' FROM T_Order WHERE UserId = ? ORDER BY Id DESC', [$customer])->fetch_all(MYSQLI_ASSOC);
    }

    public static function owned(mysqli $connection, string $customer, string $code): ?array
    {
        return $connection->execute_query('SELECT ' . self::FIELDS . ' FROM T_Order WHERE UserId = ? AND OrderCode = ?', [$customer, $code])->fetch_assoc();
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
