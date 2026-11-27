<?php

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();

$code = $_GET['Ordercode'] ?? '';
if (!is_string($code) || $code === '') {
    http_response_code(400);
    exit;
}
require_once SNEAKERPEAK_ROOT . '/src/App/Models/OrderModel.php';
if (!\SneakerPeak\App\Models\OrderModel::confirmReceipt($conn, (string) $_SESSION['CustomerID'], $code)) {
    http_response_code(409);
    exit('This order cannot be completed.');
}
echo 'Order status updated successfully.';