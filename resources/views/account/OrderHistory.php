<?php use SneakerPeak\App\Models\OrderModel; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order history | Sneaker Peak</title>
    <link rel="stylesheet" href="assets/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/icomoon.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <link rel="stylesheet" href="assets/css/order-history.css">
</head>
<body>
<header><?php require SNEAKERPEAK_ROOT . '/resources/views/partials/header.php'; ?></header>
<main class="customer-orders">
    <h1>Order history</h1>
    <form class="order-panel" method="get" action="OrderHistory.php">
        <div class="order-controls">
            <details>
                <summary class="btn btn-primary">Sort</summary>
                <label for="order-sort">Sort by</label>
                <select id="order-sort" name="sort" class="form-control">
                    <?php foreach (OrderModel::HISTORY_SORTS as $value => $label) { ?>
                        <option value="<?= $value ?>" <?= $sort === $value ? 'selected' : '' ?>><?= $label ?></option>
                    <?php } ?>
                </select>
            </details>
            <details <?= $activeFilters ? 'open' : '' ?>>
                <summary class="btn btn-primary">Filter</summary>
                <div class="order-filter-fields">
                    <label>Order code
                        <input class="form-control" name="code" value="<?= htmlspecialchars($criteria['code']) ?>">
                    </label>
                    <label>Date from
                        <input class="form-control" name="from" type="date" value="<?= htmlspecialchars($criteria['from']) ?>">
                    </label>
                    <label>Date to
                        <input class="form-control" name="to" type="date" value="<?= htmlspecialchars($criteria['to']) ?>">
                    </label>
                    <?php foreach (['order_status' => ['Order status', OrderModel::ORDER_STATUSES], 'payment_status' => ['Payment status', OrderModel::PAYMENT_STATUSES]] as $key => [$label, $options]) { ?>
                        <label><?= $label ?>
                            <select class="form-control" name="<?= $key ?>">
                                <option value="">All</option>
                                <?php foreach ($options as $value => $option) { ?>
                                    <option value="<?= $value ?>" <?= $criteria[$key] === $value ? 'selected' : '' ?>><?= $option ?></option>
                                <?php } ?>
                            </select>
                        </label>
                    <?php } ?>
                </div>
            </details>
        </div>
        <div class="order-control-actions">
            <button class="btn btn-primary" type="submit">Apply</button>
            <a href="OrderHistory.php?sort=<?= $sort ?>">Clear filters</a>
            <span>Sorted by <?= strtolower(OrderModel::HISTORY_SORTS[$sort]) ?></span>
        </div>
    </form>

    <?php if ($error !== null) { ?>
        <p class="order-error" role="alert"><?= htmlspecialchars($error) ?></p>
    <?php } elseif (!$orders) { ?>
        <p><?= $activeFilters ? 'No orders match these filters.' : 'You have no orders yet.' ?></p>
    <?php } ?>

    <div class="order-grid">
        <?php foreach ($orders as $order) { ?>
            <?php $detailsUrl = 'OrderDetails.php?Ordercode=' . rawurlencode($order['Order_code']) . '&' . $returnCriteria; ?>
            <article class="order-panel order-card">
                <h2>Order #<?= htmlspecialchars($order['Order_code']) ?></h2>
                <dl class="order-facts">
                    <div><dt>Order date</dt><dd><?= htmlspecialchars($order['Order_Date'] ?? 'Unavailable') ?></dd></div>
                    <div><dt>Order status</dt><dd><?= htmlspecialchars(OrderModel::ORDER_STATUSES[$order['Order_Status']] ?? $order['Order_Status']) ?></dd></div>
                    <div><dt>Payment status</dt><dd><?= htmlspecialchars(OrderModel::PAYMENT_STATUSES[$order['Payment_Status']] ?? $order['Payment_Status']) ?></dd></div>
                    <div><dt>Items</dt><dd><?= $order['ItemQuantity'] === null ? 'Unavailable' : (int) $order['ItemQuantity'] ?></dd></div>
                    <div><dt>Total</dt><dd><?= htmlspecialchars($order['CurrencyCode']) ?> <?= number_format((float) $order['Order_TotalAmmount'] + (float) $order['ShippingFeed'], 2) ?></dd></div>
                </dl>
                <a class="btn btn-primary" href="<?= htmlspecialchars($detailsUrl) ?>">View Order</a>
            </article>
        <?php } ?>
    </div>
</main>
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/footer.php'; ?>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
