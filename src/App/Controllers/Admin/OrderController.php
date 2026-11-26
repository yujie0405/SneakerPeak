<?php

require SNEAKERPEAK_ROOT . '/database/config.php';

$orders = $conn->query(
    "SELECT * FROM oder
     ORDER BY CASE Order_Status
         WHEN 'Pending' THEN 1 WHEN 'Shipping' THEN 2 WHEN 'Shipped' THEN 2 WHEN 'Complete' THEN 3 ELSE 4 END"
)->fetch_all(MYSQLI_ASSOC);
$counts = array_count_values(array_column($orders, 'Order_Status'));
$totalPending = $counts['Pending'] ?? 0;
$totalShipping = ($counts['Shipping'] ?? 0) + ($counts['Shipped'] ?? 0);
$totalComplete = $counts['Complete'] ?? 0;

require SNEAKERPEAK_ROOT . '/resources/views/admin/admin-Orderlist.php';