<?php

require SNEAKERPEAK_ROOT . '/database/config.php';

$name = $_GET['name'] ?? '';
if (!is_string($name) || $name === '') {
    http_response_code(400);
    exit;
}
$statement = $conn->prepare('DELETE w FROM T_Wishlist w JOIN T_Product p ON p.Id = w.ProductId WHERE w.UserId = ? AND p.Name = ?');
$statement->execute([(string) $_SESSION['CustomerID'], $name]);
echo 'Item deleted from the wishlist.';