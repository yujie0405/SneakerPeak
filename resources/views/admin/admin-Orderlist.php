<?php use SneakerPeak\App\Models\OrderModel; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orders | Sneaker Peak</title>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
<body class="admin-workspace">
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
<main class="home-section">
    <header class="home-content"><h1>Orders</h1></header>
    <div class="bigbox">
        <?php foreach ([
            'Pending' => [$totalPending, 'processing.png'],
            'Shipping' => [$totalShipping, 'shipping.png'],
            'Complete' => [$totalComplete, 'complete.png'],
        ] as $label => [$total, $icon]) { ?>
            <div class="board">
                <img class="admin-order-summary-icon" src="assets/image/logo/<?= $icon ?>" alt="" width="28" height="28">
                <p><?= $label ?> <strong><?= (int) $total ?></strong></p>
            </div>
        <?php } ?>
    </div>
    <section class="table">
        <header class="table__header"><h2><?= $customerName === null ? 'Order list' : 'Orders for ' . htmlspecialchars($customerName) ?></h2></header>
        <form class="admin-catalog-filters admin-list-filters" method="get" action="admin-Orderlist.php" aria-label="Order filters">
            <?php if ($customerName !== null) { ?><input type="hidden" name="customer" value="<?= htmlspecialchars($customerId) ?>"><?php } ?>
            <label>Search order code <input type="search" name="search" value="<?= htmlspecialchars($filters['search']) ?>"></label>
            <label>Date from <input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from']) ?>"></label>
            <label>Date to <input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to']) ?>"></label>
            <?php foreach (['payment_type' => 'Payment type', 'payment_status' => 'Payment status', 'order_status' => 'Order status'] as $field => $label) { ?>
                <label><?= $label ?>
                    <select name="<?= $field ?>">
                        <option value="">All</option>
                        <?php foreach ($orderOptions[$field] as $value => $option) { ?>
                            <option value="<?= $value ?>"<?= $filters[$field] === $value ? ' selected' : '' ?>><?= $option ?></option>
                        <?php } ?>
                    </select>
                </label>
            <?php } ?>
            <div class="admin-actions">
                <button type="submit">Apply filters</button>
                <a class="admin-button secondary" href="admin-Orderlist.php">Clear filters</a>
            </div>
        </form>
        <?php if ($error !== null) { ?>
            <p class="admin-field-error" role="alert"><?= htmlspecialchars($error) ?></p>
        <?php } elseif (!$orders) { ?>
            <p>No orders match your filters.</p>
        <?php } else { ?>
            <div class="admin-table-scroll" tabindex="0" role="region" aria-label="Order results">
                <table>
                    <thead><tr>
                        <th scope="col">Order code</th><th scope="col">Order date</th><th scope="col">Amount (RM)</th>
                        <th scope="col">Payment type</th><th scope="col">Payment status</th><th scope="col">Order status</th><th scope="col">Actions</th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($orders as $row) {
                        $paymentStatus = $row['Payment_Status'];
                        $paymentBadge = match ($paymentStatus) {
                            'Paying' => 'pending',
                            'Complete' => 'complete',
                            default => 'neutral',
                        };
                        ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($row['Order_code']) ?></th>
                            <td><?= htmlspecialchars($row['Order_Date'] ?? '—') ?></td>
                            <td class="admin-numeric"><?= number_format((float) $row['Order_TotalAmmount'], 2) ?></td>
                            <td><?= htmlspecialchars(OrderModel::PAYMENT_TYPES[$row['Payment_Type'] ?? 'unset'] ?? $row['Payment_Type']) ?></td>
                            <td><span class="admin-payment-badge <?= $paymentBadge ?>"><?= htmlspecialchars(OrderModel::PAYMENT_STATUSES[$paymentStatus] ?? $paymentStatus) ?></span></td>
                            <td><?= htmlspecialchars(OrderModel::ORDER_STATUSES[$row['Order_Status']] ?? $row['Order_Status']) ?></td>
                            <td><a class="admin-button secondary" href="admin_OrderDetails.php?orderCode=<?= rawurlencode($row['Order_code']) ?>">View</a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </section>
</main>
</body>
</html>
