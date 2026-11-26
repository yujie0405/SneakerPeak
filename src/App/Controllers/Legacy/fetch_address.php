<?php

$field = $_GET['addressOption'] ?? '';
if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !in_array($field, ['Customer_Address', 'Customer_Address_2'], true)) {
    http_response_code(400);
    exit;
}

require SNEAKERPEAK_ROOT . '/database/config.php';
$statement = $conn->prepare("SELECT `$field` FROM customer WHERE id = ?");
$statement->execute([(string) $_SESSION['CustomerID']]);
header('Content-Type: text/plain; charset=UTF-8');
echo $statement->get_result()->fetch_row()[0] ?? '';