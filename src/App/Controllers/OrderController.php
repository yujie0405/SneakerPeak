<?php

use SneakerPeak\App\Models\OrderModel;

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/OrderModel.php';

$customer = (string) $_SESSION['CustomerID'];
$page = strtolower(basename((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
if ($page === 'orderhistory.php') {
    $orders = OrderModel::history($conn, $customer);
    $orderCode = '';
    $os = '';
    if (isset($_POST['od'])) {
        $order = OrderModel::owned($conn, $customer, (string) ($_POST['odcode'] ?? ''));
        $orderCode = $order['Order_code'] ?? 'Invalid Order Code';
        $os = $order['Order_Status'] ?? '';
    }
    $oc = $os === '' ? $orderCode : $os;
    require SNEAKERPEAK_ROOT . '/resources/views/account/OrderHistory.php';
    return;
}

$invoice = $page === 'invoice.php';
$orderCode = (string) ($invoice ? ($_SESSION['Ordercode'] ?? '') : ($_GET['Ordercode'] ?? ''));
$row = OrderModel::owned($conn, $customer, $orderCode);
if (!$row) {
    http_response_code(404);
    exit('Order not found.');
}
$lines = OrderModel::lines($conn, $customer, $orderCode);
$shipping = (float) $row['ShippingFeed'];
$all = $shipping + (float) $row['Order_TotalAmmount'];
$method = $row['Payment_Type'];
$Ordercode = $orderCode;
if ($invoice) {
    $contact = $conn->prepare('SELECT name, email, Customer_ContractNumber FROM customer WHERE id = ?');
    $contact->execute([$customer]);
    $roll = $contact->get_result()->fetch_assoc();
    require SNEAKERPEAK_ROOT . '/resources/views/account/invoice.php';
} else {
    require SNEAKERPEAK_ROOT . '/resources/views/account/recei.php';
}