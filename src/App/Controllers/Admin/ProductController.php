<?php

require SNEAKERPEAK_ROOT . '/database/config.php';

$status = (string) ($_GET['status'] ?? '');
$brand = (string) ($_GET['brand'] ?? '');
$search = (string) ($_GET['search'] ?? '');
$statement = $conn->prepare(
    "SELECT * FROM product
     WHERE (? = '' OR Prod_Status = ?) AND (? = '' OR Prod_Brand = ?)
       AND (? = '' OR Prod_Name LIKE CONCAT('%', ?, '%'))"
);
$statement->execute([$status, $status, $brand, $brand, $search, $search]);
$products = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
$brands = $conn->query('SELECT Brand_Name FROM brand')->fetch_all(MYSQLI_ASSOC);

$catalogToken = $_SESSION['catalog_admin_token'] ??= bin2hex(random_bytes(32));
require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_productlist.php';