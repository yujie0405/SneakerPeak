<?php

namespace SneakerPeak\App\Models;

use mysqli_result;
use mysqli;
use SneakerPeak\Core\Database;

require_once dirname(__DIR__, 2) . '/Core/Database.php';

final class ProductModel
{
    // Preserve the storefront field names while joining the migrated lookup tables.
    public const CATALOG = "SELECT p.Id AS Prod_ID, p.Name AS Prod_Name, b.Name AS Prod_Brand,
        p.Price AS Prod_Price, p.Colour AS Prod_Colour, p.Audience AS Prod_Cetegory,
        sc.Name AS Prod_SubC, p.Details AS Prod_Details, p.Image1Path AS Prod_img1,
        p.Image2Path AS Prod_img2, p.Image3Path AS Prod_img3,
        CASE st.Code WHEN 'DEACTIVATED' THEN 'Deleted' WHEN 'OUT_OF_STOCK' THEN 'out-of-stock'
             WHEN 'AVAILABLE' THEN 'available' ELSE LOWER(st.Code) END AS Prod_Status
        FROM T_Product p JOIN E_Brand b ON b.Id = p.BrandId
        JOIN E_SubCategory sc ON sc.Id = p.SubCategoryId
        JOIN E_ProductStatus st ON st.Id = p.ProductStatusId";

    public static function latest(): mysqli_result
    {
        $connection = Database::getInstance()->getConnection();
        return $connection->query(self::CATALOG . " WHERE st.Code = 'AVAILABLE' ORDER BY p.Id DESC LIMIT 8");
    }

    public static function browse(mysqli $connection, string $search, array $brands, array $subcategories, string $category, int $requestedPage): array
    {
        $where = "FROM (" . self::CATALOG . ") catalog WHERE Prod_Status = 'available'";
        $parameters = [];
        if ($search !== '') {
            $where .= ' AND Prod_Name LIKE ?';
            $parameters[] = '%' . $search . '%';
        }
        foreach (['Prod_Brand' => $brands, 'Prod_SubC' => $subcategories, 'Prod_Cetegory' => [$category]] as $field => $values) {
            $values = array_filter($values, static fn($value) => $value !== '');
            $values = array_values(array_filter($values, 'is_string'));
            if ($values) {
                $placeholders = implode(', ', array_fill(0, count($values), '?'));
                $where .= " AND TRIM($field) IN ($placeholders)";
                array_push($parameters, ...$values);
            }
        }
        $count = $connection->prepare("SELECT COUNT(*) $where");
        $count->execute($parameters);
        $total = (int) $count->get_result()->fetch_row()[0];
        $pageSize = 20;
        $pageCount = max(1, (int) ceil($total / $pageSize));
        $page = min(max(1, $requestedPage), $pageCount);
        $offset = ($page - 1) * $pageSize;

        $statement = $connection->prepare("SELECT * $where ORDER BY Prod_Name, Prod_ID LIMIT ? OFFSET ?");
        $statement->execute([...$parameters, $pageSize, $offset]);
        return [$statement->get_result()->fetch_all(MYSQLI_ASSOC), $total, $page, $pageCount];
    }

    public static function details(mysqli $connection, string $name): ?array
    {
        $statement = $connection->prepare(self::CATALOG . " WHERE p.Name = ? AND st.Code = 'AVAILABLE' ORDER BY p.Id LIMIT 1");
        $statement->execute([$name]);
        return $statement->get_result()->fetch_assoc();
    }

    public static function sizes(mysqli $connection, string $productId): array
    {
        $statement = $connection->prepare(
            "SELECT z.SizeLabel AS Prod_Size, i.Qty AS Prod_Qty
             FROM T_ProductSizeAndCategory i JOIN E_Size z ON z.Id = i.SizeId
             WHERE i.ProductId = ? AND i.IsAvailable = 1 AND i.Qty > 0 ORDER BY z.SortOrder, z.Id"
        );
        $statement->execute([$productId]);
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
