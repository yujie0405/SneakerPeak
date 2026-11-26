<?php

namespace SneakerPeak\App\Models;

use mysqli;

final class ReportingModel
{
    public static function orderSeries(mysqli $connection, string $start, string $end): array
    {
        $statement = $connection->prepare(
            "SELECT DATE_FORMAT(STR_TO_DATE(REPLACE(Order_Date, '-', '/'), '%d/%m/%Y'), '%Y-%m-%d') AS order_date,
                    COUNT(*) AS total_orders, SUM(Order_TotalAmmount) AS total_sales
             FROM oder WHERE STR_TO_DATE(REPLACE(Order_Date, '-', '/'), '%d/%m/%Y') BETWEEN ? AND ?
             GROUP BY order_date ORDER BY order_date"
        );
        $statement->execute([$start, $end]);
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function statusCounts(mysqli $connection): array
    {
        $rows = $connection->query('SELECT Order_Status, COUNT(*) AS count FROM oder GROUP BY Order_Status');
        return array_column($rows->fetch_all(MYSQLI_ASSOC), 'count', 'Order_Status');
    }

    public static function productCounts(mysqli $connection): array
    {
        $brands = $connection->query('SELECT Prod_Brand, COUNT(*) AS TotalCount FROM product GROUP BY Prod_Brand');
        $statuses = $connection->query('SELECT Prod_Status, COUNT(*) AS TotalStatus FROM product GROUP BY Prod_Status');
        return [$brands->fetch_all(MYSQLI_ASSOC), $statuses->fetch_all(MYSQLI_ASSOC)];
    }

    public static function overview(mysqli $connection, string $start, string $end): array
    {
        $statement = $connection->prepare(
            "SELECT COUNT(*) FROM oder_details WHERE STR_TO_DATE(REPLACE(Order_Day, '-', '/'), '%d/%m/%Y') BETWEEN ? AND ?"
        );
        $statement->execute([$start, $end]);
        $sold = (int) $statement->get_result()->fetch_row()[0];
        $totals = $connection->query(
            'SELECT (SELECT COUNT(*) FROM customer) AS customers, (SELECT COUNT(*) FROM product) AS products'
        )->fetch_assoc();
        $top = $connection->query(
            'SELECT d.Order_Product, MIN(p.Prod_img1) AS Prod_img1, COUNT(*) AS OrderCount
             FROM oder_details d JOIN product p ON p.Prod_Name = d.Order_Product
             GROUP BY d.Order_Product ORDER BY OrderCount DESC LIMIT 3'
        )->fetch_all(MYSQLI_ASSOC);
        return [$sold, $totals, $top];
    }
}