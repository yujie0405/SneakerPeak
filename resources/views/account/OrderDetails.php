<?php
use SneakerPeak\App\Models\OrderModel;

$money = static fn($amount): string => htmlspecialchars($row['CurrencyCode']) . ' ' . number_format((float) $amount, 2);
$paymentMethod = OrderModel::PAYMENT_TYPES[$method ?? 'unset'] ?? $method;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order details | Sneaker Peak</title>
    <link rel="stylesheet" href="assets/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/icomoon.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <link rel="stylesheet" href="assets/css/order-history.css">
</head>
<body>
<header><?php require SNEAKERPEAK_ROOT . '/resources/views/partials/header.php'; ?></header>
<main class="customer-orders">
    <a class="order-back" href="<?= htmlspecialchars($historyUrl) ?>">Back to order history</a>
    <h1>Order #<?= htmlspecialchars($orderCode) ?></h1>
    <div class="order-grid">
        <section class="order-panel">
            <h2>Order summary</h2>
            <dl class="order-facts">
                <div><dt>Date</dt><dd><?= htmlspecialchars($row['Order_Date'] ?? 'Unavailable') ?></dd></div>
                <div><dt>Order status</dt><dd><?= htmlspecialchars(OrderModel::ORDER_STATUSES[$row['Order_Status']] ?? $row['Order_Status']) ?></dd></div>
                <div><dt>Payment status</dt><dd><?= htmlspecialchars(OrderModel::PAYMENT_STATUSES[$row['Payment_Status']] ?? $row['Payment_Status']) ?></dd></div>
                <?php if ($method && $method !== 'unset') { ?>
                    <div><dt>Payment method</dt><dd><?= htmlspecialchars($paymentMethod) ?></dd></div>
                <?php } ?>
                <div><dt>Net items amount</dt><dd><?= $money($row['Order_TotalAmmount']) ?></dd></div>
                <div><dt>Shipping</dt><dd><?= $money($shipping) ?></dd></div>
                <div><dt>Total</dt><dd><strong><?= $money($all) ?></strong></dd></div>
            </dl>
            <div class="order-control-actions">
                <?php if ($row['Payment_Status'] === 'Complete') { ?>
                    <a class="btn btn-primary" href="recei.php?Ordercode=<?= rawurlencode($orderCode) ?>">View receipt</a>
                <?php } ?>
                <?php if ($row['ask']) { ?>
                    <form method="post" onsubmit="return confirm('Confirm that you received this order?')">
                        <input type="hidden" name="order_confirmation_token" value="<?= htmlspecialchars($confirmationToken) ?>">
                        <button class="btn btn-primary" type="submit">Complete order</button>
                    </form>
                <?php } ?>
            </div>
        </section>
        <section class="order-panel">
            <h2>Shipping address</h2>
            <?php if (trim((string) $row['Order_Address']) !== '') { ?>
                <p>
                    <?= htmlspecialchars((string) $row['Receiver_Name']) ?><br>
                    <span class="order-address"><?= htmlspecialchars($row['Order_Address']) ?></span>
                    <?= htmlspecialchars((string) $row['Receiver_Contract']) ?>
                </p>
            <?php } else { ?>
                <p>Shipping information is unavailable for this order.</p>
            <?php } ?>
        </section>
    </div>
    <section class="order-panel order-items">
        <h2 id="purchased-items">Purchased items</h2>
        <?php if (!$lines) { ?>
            <p>Item details are unavailable for this order.</p>
        <?php } else { ?>
            <div class="order-table-scroll" tabindex="0" role="region" aria-labelledby="purchased-items">
                <table class="order-table">
                    <thead><tr>
                        <th scope="col">Product</th><th scope="col">Size</th><th scope="col">Quantity</th>
                        <th scope="col">Unit price</th><th scope="col">Line total</th>
                    </tr></thead>
                    <tbody>
                        <?php foreach ($lines as $line) { ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($line['Order_Product']) ?></th>
                                <td><?= htmlspecialchars($line['Order_Size']) ?></td>
                                <td><?= (int) $line['Order_qty'] ?></td>
                                <td><?= $money($line['Order_ProdcutP']) ?></td>
                                <td><?= $money((float) $line['Order_ProdcutP'] * (int) $line['Order_qty']) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </section>
</main>
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/footer.php'; ?>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
