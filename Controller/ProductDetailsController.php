<?php

use SneakerPeak\App\Models\CartModel;
use SneakerPeak\App\Models\ProductModel;

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();
require_once SNEAKERPEAK_ROOT . '/src/App/Models/ProductModel.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/CartModel.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$Name = (string) ($_GET['Prod_Name'] ?? $_POST['Prod_Name'] ?? '');
if ($Name === '') {
    header('Location: menu.php');
    exit;
}
$product = ProductModel::details($conn, $Name);
if (!$product) {
    http_response_code(404);
    exit('This product is unavailable.');
}
$cartMessage = null;
$token = $_SESSION['cart_token'] ??= bin2hex(random_bytes(32));
if (isset($_POST['add-tocard'])) {
    if (!isset($_SESSION['CustomerID'])) {
        header('Location: Login.php');
        exit;
    }
    if (!hash_equals($token, (string) ($_POST['cart_token'] ?? ''))) {
        http_response_code(403);
        exit('Please reload the product page and try again.');
    }
    $quantity = filter_var($_POST['qty'] ?? null, FILTER_VALIDATE_INT) ?: 0;
    $added = CartModel::add($conn, (string) $_SESSION['CustomerID'], $product,
        (string) ($_POST['P_size'] ?? ''), $quantity);
    $cartMessage = $added ? 'Successfully added to the cart' : 'Choose an available size and quantity.';
}

$sizes = ProductModel::sizes($conn, (string) $product['Prod_ID']);
require SNEAKERPEAK_ROOT . '/resources/views/storefront/prod.php';