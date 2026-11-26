<?php

namespace SneakerPeak\App\Models;

use mysqli;

final class ReportingModel
{
    public static function orderSeries(mysqli $connection, string $start, string $end): array
    {
        $statement = $connection->prepare(
            "SELECT OrderedOn AS order_date,
                    COUNT(*) AS total_orders, SUM(NetItemsAmount) AS total_sales
             FROM T_Order WHERE OrderedOn BETWEEN ? AND ?
             GROUP BY order_date ORDER BY order_date"
        );
        $statement->execute([$start, $end]);
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function statusCounts(mysqli $connection): array
    {
        $rows = $connection->query('SELECT OrderStatus AS Order_Status, COUNT(*) AS count FROM T_Order GROUP BY OrderStatus');
        return array_column($rows->fetch_all(MYSQLI_ASSOC), 'count', 'Order_Status');
    }

    public static function productCounts(mysqli $connection): array
    {
        $brands = $connection->query('SELECT b.Name AS Prod_Brand, COUNT(*) AS TotalCount FROM T_Product p JOIN E_Brand b ON b.Id=p.BrandId GROUP BY b.Name');
        $statuses = $connection->query('SELECT CASE st.Code WHEN \'DEACTIVATED\' THEN \'Deleted\' WHEN \'OUT_OF_STOCK\' THEN \'out-of-stock\' ELSE \'available\' END AS Prod_Status, COUNT(*) AS TotalStatus FROM T_Product p JOIN E_ProductStatus st ON st.Id=p.ProductStatusId GROUP BY st.Code');
        return [$brands->fetch_all(MYSQLI_ASSOC), $statuses->fetch_all(MYSQLI_ASSOC)];
    }

    public static function overview(mysqli $connection, string $start, string $end): array
    {
        $statement = $connection->prepare(
            "SELECT COUNT(*) FROM T_OrderLineItem WHERE RecordedOn BETWEEN ? AND ?"
        );
        $statement->execute([$start, $end]);
        $sold = (int) $statement->get_result()->fetch_row()[0];
        $totals = $connection->query(
            'SELECT (SELECT COUNT(*) FROM T_User WHERE IsAdmin=0) AS customers, (SELECT COUNT(*) FROM T_Product) AS products'
        )->fetch_assoc();
        $top = $connection->query(
            'SELECT d.ProductNameSnapshot AS Order_Product, MIN(p.Image1Path) AS Prod_img1, COUNT(*) AS OrderCount
             FROM T_OrderLineItem d JOIN T_Product p ON p.Id = d.ProductId
             GROUP BY d.ProductNameSnapshot ORDER BY OrderCount DESC LIMIT 3'
        )->fetch_all(MYSQLI_ASSOC);
        return [$sold, $totals, $top];
    }
}