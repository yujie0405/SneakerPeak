<?php

namespace SneakerPeak\App\Models;

use mysqli_result;
use mysqli;
use SneakerPeak\Core\Database;

require_once dirname(__DIR__, 2) . '/Core/Database.php';

final class ProductModel
{
    public static function latest(): mysqli_result
    {
        $connection = Database::getInstance()->getConnection();
        return $connection->query('SELECT * FROM product ORDER BY Prod_ID DESC LIMIT 8');
    }

    public static function browse(mysqli $connection, string $search, array $brands, array $subcategories): mysqli_result
    {
        $sql = "SELECT * FROM product WHERE Prod_Status = 'available'";
        $parameters = [];
        if ($search !== '') {
            $sql .= ' AND Prod_Name LIKE ?';
            $parameters[] = '%' . $search . '%';
        }
        foreach (['Prod_Brand' => $brands, 'Prod_SubC' => $subcategories] as $field => $values) {
            $values = array_values(array_filter($values, 'is_string'));
            if ($values) {
                $placeholders = implode(', ', array_fill(0, count($values), '?'));
                $sql .= " AND $field IN ($placeholders)";
                array_push($parameters, ...$values);
            }
        }
        $statement = $connection->prepare($sql);
        $statement->execute($parameters);
        return $statement->get_result();
    }

    public static function details(mysqli $connection, string $name): ?array
    {
        $statement = $connection->prepare("SELECT * FROM product WHERE Prod_Name = ? AND Prod_Status = 'available' LIMIT 1");
        $statement->execute([$name]);
        return $statement->get_result()->fetch_assoc();
    }

    public static function sizes(mysqli $connection, string $name): array
    {
        $statement = $connection->prepare(
            "SELECT * FROM sizeandqty WHERE Prod_Name = ? AND Qty_status = 'Available'
             ORDER BY CAST(Prod_Size AS DECIMAL(10, 2))"
        );
        $statement->execute([$name]);
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
