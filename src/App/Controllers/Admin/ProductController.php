<?php

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/ProductModel.php';

$status = (string) ($_GET['status'] ?? '');
$brand = (string) ($_GET['brand'] ?? '');
$search = (string) ($_GET['search'] ?? '');
$statement = $conn->prepare(
    "SELECT * FROM (" . \SneakerPeak\App\Models\ProductModel::CATALOG . ") catalog
     WHERE (? = '' OR Prod_Status = ?) AND (? = '' OR Prod_Brand = ?)
       AND (? = '' OR Prod_Name LIKE CONCAT('%', ?, '%'))"
);
$statement->execute([$status, $status, $brand, $brand, $search, $search]);
$products = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
$brands = $conn->query('SELECT Name AS Brand_Name FROM E_Brand')->fetch_all(MYSQLI_ASSOC);

$catalogToken = $_SESSION['catalog_admin_token'] ??= bin2hex(random_bytes(32));
require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_productlist.php';