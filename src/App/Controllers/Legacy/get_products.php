<?php
require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/ProductModel.php';
$projection = \SneakerPeak\App\Models\ProductModel::CATALOG;
$status = strtolower((string) ($_GET['status'] ?? ''));
$statuses = ['available', 'out-of-stock', 'deleted'];
if (in_array($status, $statuses, true)) {
    $result = $conn->execute_query("SELECT * FROM ($projection) catalog WHERE Prod_Status = ?", [$status]);
} else {
    $result = $conn->query("SELECT * FROM ($projection) catalog WHERE Prod_Status IN ('available', 'out-of-stock')");
}
foreach ($result as $row) {
    echo '<tr>';
    foreach (['Prod_Name', 'Prod_Price', 'Prod_Status'] as $field) {
        echo '<td>', htmlspecialchars((string) $row[$field]), '</td>';
    }
    echo '</tr>';
}
