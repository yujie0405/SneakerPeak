<?php

require SNEAKERPEAK_ROOT . '/database/config.php';

$name = $_GET['name'] ?? '';
if (!is_string($name) || $name === '') {
    http_response_code(400);
    exit;
}
$statement = $conn->prepare('DELETE FROM wishlist WHERE UID = ? AND prod_name = ?');
$statement->execute([(string) $_SESSION['CustomerID'], $name]);
echo 'Item deleted from the wishlist.';