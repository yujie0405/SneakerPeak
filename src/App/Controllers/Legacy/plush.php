<?php

$token = $_SESSION['cart_token'] ?? null;
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $token === null
    || !hash_equals($token, (string) ($_POST['cart_token'] ?? ''))) {
    http_response_code(403);
    exit('Access denied.');
}

require SNEAKERPEAK_ROOT . '/database/config.php';
$delta = 1;
$update = $conn->prepare(
    "UPDATE cart c JOIN sizeandqty s ON s.Prod_Name = c.Product_Name AND s.Prod_Size = c.Select_Size
     SET c.Select_qty = CAST(c.Select_qty AS SIGNED) + ?
     WHERE c.UID = ? AND c.Product_Name = ? AND c.Select_Size = ?
       AND CAST(c.Select_qty AS SIGNED) + ? >= 1
       AND (? < 0 OR (s.Qty_status = 'Available'
           AND CAST(c.Select_qty AS SIGNED) + ? <= CAST(s.Prod_Qty AS SIGNED)))"
);
$update->execute([
    $delta, (string) $_SESSION['CustomerID'], (string) ($_POST['name'] ?? ''),
    (string) ($_POST['size'] ?? ''), $delta, $delta, $delta,
]);
