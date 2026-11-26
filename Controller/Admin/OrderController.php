<?php

use SneakerPeak\App\Models\OrderModel;
use SneakerPeak\App\Models\ReportingModel;
use SneakerPeak\App\Services\AdminDateRange;
use SneakerPeak\App\Services\CatalogInput;

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/OrderModel.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/ReportingModel.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/AdminDateRange.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/CatalogInput.php';

$orderOptions = [
    'payment_type' => OrderModel::PAYMENT_TYPES,
    'payment_status' => OrderModel::PAYMENT_STATUSES,
    'order_status' => OrderModel::ORDER_STATUSES,
];
$filters = [];
foreach (['search', 'date_from', 'date_to', ...array_keys($orderOptions)] as $field) {
    $filters[$field] = CatalogInput::text($_GET, $field);
}
$error = AdminDateRange::error($_GET['date_from'] ?? '', $_GET['date_to'] ?? '');
foreach ($orderOptions as $field => $options) {
    if (is_array($_GET[$field] ?? null) || ($filters[$field] !== '' && !isset($options[$filters[$field]]))) {
        $error ??= 'Choose a supported payment type, payment status or order status.';
    }
}

$where = [];
$values = [];
$customerId = CatalogInput::id($_GET['customer'] ?? null);
$customerName = null;
if (isset($_GET['customer'])) {
    if ($customerId !== null) {
        $customerName = $conn->execute_query(
            'SELECT DisplayName FROM T_User WHERE Id = ? AND IsAdmin = 0', [$customerId]
        )->fetch_row()[0] ?? null;
    }
    if ($customerName === null) {
        $error ??= 'Customer not found.';
    } else {
        $where[] = 'UserId = ?';
        $values[] = $customerId;
    }
}
foreach ([
    'search' => 'LOCATE(?, OrderCode) > 0',
    'date_from' => 'OrderedOn >= ?',
    'date_to' => 'OrderedOn <= ?',
    'payment_type' => $filters['payment_type'] === 'unset' ? '(PaymentType = ? OR PaymentType IS NULL)' : 'PaymentType = ?',
    'payment_status' => 'PaymentStatus = ?',
    'order_status' => 'OrderStatus = ?',
] as $field => $condition) {
    if ($filters[$field] !== '') {
        $where[] = $condition;
        $values[] = $filters[$field];
    }
}
$orders = [];
if ($error === null) {
    $criteria = $where ? ' WHERE ' . implode(' AND ', $where) : '';
    $orders = $conn->execute_query(
        'SELECT ' . OrderModel::FIELDS . ' FROM T_Order' . $criteria
        . " ORDER BY CASE OrderStatus WHEN 'Pending' THEN 1 WHEN 'Shipping' THEN 2
              WHEN 'Shipped' THEN 2 WHEN 'Complete' THEN 3 ELSE 4 END, OrderedOn DESC, Id DESC",
        $values
    )->fetch_all(MYSQLI_ASSOC);
}
$counts = ReportingModel::statusCounts($conn);
$totalPending = $counts['Pending'] ?? 0;
$totalShipping = ($counts['Shipping'] ?? 0) + ($counts['Shipped'] ?? 0);
$totalComplete = $counts['Complete'] ?? 0;

require SNEAKERPEAK_ROOT . '/resources/views/admin/admin-Orderlist.php';
