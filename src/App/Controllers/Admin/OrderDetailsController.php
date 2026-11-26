<?php

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/OrderModel.php';

$orderCode = (string) ($_GET['orderCode'] ?? '');
$order = $conn->prepare('SELECT ' . \SneakerPeak\App\Models\OrderModel::FIELDS . ' FROM T_Order WHERE OrderCode = ?');
$order->execute([$orderCode]);
$cal = $order->get_result()->fetch_assoc();
if (!$cal) {
    http_response_code(404);
    exit('Order not found.');
}
$token = $_SESSION['order_admin_token'] ??= bin2hex(random_bytes(32));
$call = $cal;
$lines = $conn->prepare(
    'SELECT d.ProductNameSnapshot AS Order_Product, d.SizeLabelSnapshot AS Order_Size,
       d.UnitPrice AS Order_ProdcutP, d.Qty AS Order_qty, p.Image1Path AS Prod_img1
     FROM T_OrderLineItem d LEFT JOIN T_Product p ON d.ProductId = p.Id WHERE d.OrderId = ? ORDER BY d.Id'
);
$lines->execute([$cal['ID']]);
$orderItems = $lines->get_result()->fetch_all(MYSQLI_ASSOC);

require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_Orderdetails.php';