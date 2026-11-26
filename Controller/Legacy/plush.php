<?php

$token = $_SESSION['cart_token'] ?? null;
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $token === null
    || !hash_equals($token, (string) ($_POST['cart_token'] ?? ''))) {
    http_response_code(403);
    exit('Access denied.');
}

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();
require_once SNEAKERPEAK_ROOT . '/src/App/Models/CartModel.php';
\SneakerPeak\App\Models\CartModel::changeQuantity($conn, (string) $_SESSION['CustomerID'],
    (string) ($_POST['name'] ?? ''), (string) ($_POST['size'] ?? ''), 1);
