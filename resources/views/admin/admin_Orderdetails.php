<?php
use SneakerPeak\App\Models\OrderModel;
use SneakerPeak\Manager\CatalogImageUpload;

$payment = $cal['Payment_Status'];
$status = $cal['Order_Status'];
$canShip = $status === 'Pending' && $payment === 'Complete';
$canRemind = in_array($status, ['Shipping', 'Shipped'], true) && !$cal['ask'];
$money = static fn($amount): string => number_format((float) $amount, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Details | Sneaker Peak</title>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
<body class="admin-workspace">
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
<main class="home-section">
    <header class="home-content"><h1>Order #<?= htmlspecialchars($orderCode) ?></h1></header>
    <section class="table">
        <header class="table__header"><h2>Order items</h2></header>
        <?php if (!$orderItems) { ?>
            <p>No line items are available for this order.</p>
        <?php } else { ?>
            <div class="admin-table-scroll" tabindex="0" role="region" aria-label="Order items">
                <table>
                    <thead><tr>
                        <th scope="col">Product</th><th scope="col">Size</th><th scope="col" class="admin-numeric">Quantity</th>
                        <th scope="col" class="admin-numeric">Unit price (RM)</th><th scope="col" class="admin-numeric">Line amount (RM)</th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($orderItems as $item) {
                        $image = CatalogImageUpload::url($item['Prod_img1']);
                        ?>
                        <tr>
                            <th scope="row"><div class="admin-product-cell">
                                <?php if ($image !== '') { ?><img class="admin-order-thumbnail" src="<?= htmlspecialchars($image) ?>" alt="" width="36" height="36"><?php } ?>
                                <?= htmlspecialchars($item['Order_Product']) ?>
                            </div></th>
                            <td><?= htmlspecialchars($item['Order_Size']) ?></td>
                            <td class="admin-numeric"><?= $item['Order_qty'] ?></td>
                            <td class="admin-numeric"><?= $money($item['Order_ProdcutP']) ?></td>
                            <td class="admin-numeric"><?= $money($item['LineAmount']) ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
        <dl class="admin-order-totals">
            <dt>Items subtotal</dt><dd>RM <?= $money($cal['Order_TotalAmmount']) ?></dd>
            <dt>Shipping fee</dt><dd>RM <?= $money($cal['ShippingFeed']) ?></dd>
            <dt><strong>Order total</strong></dt><dd><strong>RM <?= $money($cal['Order_TotalAmmount'] + $cal['ShippingFeed']) ?></strong></dd>
        </dl>
    </section>
    <section class="table">
        <header class="table__header"><h2>Order information</h2></header>
        <dl class="admin-inventory-summary">
            <dt>Order status</dt><dd><?= htmlspecialchars(OrderModel::ORDER_STATUSES[$status] ?? $status) ?></dd>
            <dt>Order date</dt><dd><?= htmlspecialchars($cal['Order_Date'] ?? '—') ?></dd>
            <dt>Payment type</dt><dd><?= htmlspecialchars(OrderModel::PAYMENT_TYPES[$cal['Payment_Type'] ?? 'unset'] ?? $cal['Payment_Type']) ?></dd>
            <dt>Payment status</dt><dd><?= htmlspecialchars(OrderModel::PAYMENT_STATUSES[$payment] ?? $payment) ?></dd>
            <dt>Shipping address</dt><dd><?= htmlspecialchars($cal['Order_Address'] ?? '—') ?></dd>
            <dt>Receiver</dt><dd><?= htmlspecialchars($cal['Receiver_Name'] ?? '—') ?></dd>
            <dt>Phone</dt><dd><?= htmlspecialchars($cal['Receiver_Contract'] ?? '—') ?></dd>
        </dl>
        <div class="admin-actions">
            <?php if ($canShip || $canRemind) { ?>
                <form method="post" action="<?= $canShip ? 'changestatus.php' : 'telluser.php' ?>" data-order-action>
                    <input type="hidden" name="orderCode" value="<?= htmlspecialchars($orderCode) ?>">
                    <input type="hidden" name="order_token" value="<?= htmlspecialchars($token) ?>">
                    <button type="submit"><?= $canShip ? 'Mark as shipped' : 'Remind customer to confirm' ?></button>
                </form>
            <?php } ?>
            <a class="admin-button secondary" href="admin-Orderlist.php">Back to orders</a>
        </div>
    </section>
</main>
<script>
    const orderAction = document.querySelector('[data-order-action]');
    orderAction?.addEventListener('submit', async event => {
        event.preventDefault();
        const button = orderAction.querySelector('button');
        if (!window.confirm(button.textContent.trim() + '?')) return;
        button.disabled = true;
        try {
            const response = await fetch(orderAction.action, {method: 'POST', body: new FormData(orderAction)});
            window.alert(await response.text());
            if (response.ok) location.reload();
        } catch {
            window.alert('The order action could not be completed.');
        } finally {
            button.disabled = false;
        }
    });
</script>
</body>
</html>
