<?php

use SneakerPeak\App\Models\OrderModel;

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();
require_once SNEAKERPEAK_ROOT . '/src/App/Models/OrderModel.php';

$customer = (string) $_SESSION['CustomerID'];
$page = strtolower(basename((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
require_once SNEAKERPEAK_ROOT . '/src/App/Services/AdminDateRange.php';

$read = static fn(string $key): string => is_string($_GET[$key] ?? null) ? trim($_GET[$key]) : '';
$criteria = [];
foreach (['code', 'from', 'to', 'order_status', 'payment_status'] as $field) {
    $criteria[$field] = $read($field);
}
$sort = $read('sort');
$sort = isset(OrderModel::HISTORY_SORTS[$sort]) ? $sort : 'newest';
$activeFilters = array_filter($criteria, static fn(string $value): bool => $value !== '');
$returnCriteria = http_build_query(['sort' => $sort] + $activeFilters);
$historyUrl = 'OrderHistory.php?' . $returnCriteria;

if ($page === 'orderhistory.php') {
    $error = \SneakerPeak\App\Services\AdminDateRange::error($criteria['from'], $criteria['to']);
    foreach (['order_status' => OrderModel::ORDER_STATUSES, 'payment_status' => OrderModel::PAYMENT_STATUSES] as $key => $options) {
        if ($criteria[$key] !== '' && !isset($options[$criteria[$key]])) {
            $error ??= 'Choose a supported status.';
        }
    }
    $orders = $error === null ? OrderModel::history($conn, $customer, $sort, $criteria) : [];
    require SNEAKERPEAK_ROOT . '/resources/views/account/OrderHistory.php';
    return;
}

$invoice = $page === 'invoice.php';
$orderCode = (string) ($invoice ? ($_SESSION['Ordercode'] ?? '') : $read('Ordercode'));
$row = OrderModel::owned($conn, $customer, $orderCode);
if (!$row) {
    http_response_code(404);
    exit('Order not found.');
}
if ($page === 'orderdetails.php' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['order_confirmation_token'] ?? null;
    if (!is_string($submittedToken) || !isset($_SESSION['order_confirmation_token'])
        || !hash_equals($_SESSION['order_confirmation_token'], $submittedToken)) {
        http_response_code(403);
        exit('Please reload the order details and try again.');
    }
    if (!OrderModel::confirmReceipt($conn, $customer, $orderCode)) {
        http_response_code(409);
        exit('This order cannot be completed.');
    }
    header('Location: OrderDetails.php?Ordercode=' . rawurlencode($orderCode) . '&' . $returnCriteria, true, 303);
    exit;
}

$lines = OrderModel::lines($conn, $customer, $orderCode);
$shipping = (float) $row['ShippingFeed'];
$all = $shipping + (float) $row['Order_TotalAmmount'];
$method = $row['Payment_Type'];
$Ordercode = $orderCode;
if ($page === 'orderdetails.php') {
    $confirmationToken = $_SESSION['order_confirmation_token'] ??= bin2hex(random_bytes(32));
    require SNEAKERPEAK_ROOT . '/resources/views/account/OrderDetails.php';
} elseif ($invoice) {
    $contact = $conn->prepare('SELECT DisplayName AS name, Email AS email, Phone AS Customer_ContractNumber FROM T_User WHERE Id = ?');
    $contact->execute([$customer]);
    $roll = $contact->get_result()->fetch_assoc();
    require SNEAKERPEAK_ROOT . '/resources/views/account/invoice.php';
} else {
    if ($row['Payment_Status'] !== 'Complete') {
        http_response_code(404);
        exit('Receipt not available.');
    }
    require SNEAKERPEAK_ROOT . '/resources/views/account/recei.php';
}