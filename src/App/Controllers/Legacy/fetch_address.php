<?php

$field = $_GET['addressOption'] ?? '';
if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !in_array($field, ['Customer_Address', 'Customer_Address_2'], true)) {
    http_response_code(400);
    exit;
}

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/UserModel.php';
header('Content-Type: text/plain; charset=UTF-8');
echo \SneakerPeak\App\Models\UserModel::address($conn, (string) $_SESSION['CustomerID'], $field === 'Customer_Address' ? 1 : 2);