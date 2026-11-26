<?php

require SNEAKERPEAK_ROOT . '/database/config.php';

$code = $_GET['Ordercode'] ?? '';
if (!is_string($code) || $code === '') {
    http_response_code(400);
    exit;
}
$statement = $conn->prepare(
    "UPDATE oder SET ask = 0, Order_Status = 'Complete'
     WHERE UID = ? AND Order_code = ? AND ask <> '0'"
);
$statement->execute([(string) $_SESSION['CustomerID'], $code]);
if (!$statement->affected_rows) {
    http_response_code(409);
    exit('This order cannot be completed.');
}
echo 'Order status updated successfully.';