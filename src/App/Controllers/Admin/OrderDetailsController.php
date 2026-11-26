<?php

require SNEAKERPEAK_ROOT . '/database/config.php';

$orderCode = (string) ($_GET['orderCode'] ?? '');
$order = $conn->prepare('SELECT * FROM oder WHERE Order_code = ? LIMIT 1');
$order->execute([$orderCode]);
$cal = $order->get_result()->fetch_assoc();
if (!$cal) {
    http_response_code(404);
    exit('Order not found.');
}
$token = $_SESSION['order_admin_token'] ??= bin2hex(random_bytes(32));
$address = $conn->prepare('SELECT * FROM oder_address WHERE UID = ? AND Order_code = ? ORDER BY ID DESC LIMIT 1');
$address->execute([$cal['UID'], $orderCode]);
$call = $address->get_result()->fetch_assoc() ?? [];
$lines = $conn->prepare(
    'SELECT d.*, p.Prod_img1 FROM oder_details d
     LEFT JOIN product p ON d.Order_Product = p.Prod_Name
     WHERE d.UID = ? AND d.Order_code = ? ORDER BY d.ID'
);
$lines->execute([$cal['UID'], $orderCode]);
$orderItems = $lines->get_result()->fetch_all(MYSQLI_ASSOC);

require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_Orderdetails.php';