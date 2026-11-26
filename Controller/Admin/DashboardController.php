<?php

use SneakerPeak\App\Models\ReportingModel;

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/ReportingModel.php';

$routeName = strtolower(basename((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
if ($routeName === 'admin-productdashboard.php') {
    [$productData, $productDat] = ReportingModel::productCounts($conn);
    $counts = array_change_key_case(array_column($productDat, 'TotalStatus', 'Prod_Status'));
    $totalPending = $counts['available'] ?? 0;
    $totalShipping = $counts['deleted'] ?? 0;
    $totalComplete = $counts['out of stock'] ?? $counts['out-of-stock'] ?? 0;
    $tp = array_sum($counts);
    require SNEAKERPEAK_ROOT . '/resources/views/admin/admin-ProductDashboard.php';
    return;
}

$weekStart = date('Y-m-d', strtotime('monday this week'));
$weekEnd = date('Y-m-d', strtotime('sunday this week'));
$orderDashboard = $routeName === 'admin-orderdashboard.php';
$start = (string) ($_POST[$orderDashboard ? 'date1' : 'start_date'] ?? '');
$end = (string) ($_POST[$orderDashboard ? 'date2' : 'end_date'] ?? '');
$valid = static function (string $value): bool {
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
    return $date !== false && $date->format('Y-m-d') === $value;
};
if (!$valid($start) || !$valid($end) || $start > $end) {
    $start = $weekStart;
    $end = $weekEnd;
}
$series = ReportingModel::orderSeries($conn, $start, $end);

if ($orderDashboard) {
    $counts = ReportingModel::statusCounts($conn);
    $totalPending = $counts['Pending'] ?? 0;
    $totalShipping = ($counts['Shipping'] ?? 0) + ($counts['Shipped'] ?? 0);
    $totalComplete = $counts['Complete'] ?? 0;
    $totalOrders = array_sum(array_column($series, 'total_orders'));
    $orderData = array_map(static fn($row) => [
        'Order_Date' => $row['order_date'], 'total_orders' => $row['total_orders'],
    ], $series);
    require SNEAKERPEAK_ROOT . '/resources/views/admin/admin-OrderDashboard.php';
    return;
}

$weekly = ReportingModel::orderSeries($conn, $weekStart, $weekEnd);
$totalEarnings = array_sum(array_column($weekly, 'total_sales'));
[$totalProductsSold, $summary, $topProducts] = ReportingModel::overview($conn, $weekStart, $weekEnd);
$totalCustomers = $summary['customers'];
$totalProducts = $summary['products'];
$labels = array_column($series, 'order_date');
$data = array_column($series, 'total_sales');
require SNEAKERPEAK_ROOT . '/resources/views/admin/DashBoad.php';