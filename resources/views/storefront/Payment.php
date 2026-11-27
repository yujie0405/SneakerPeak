<?php use SneakerPeak\App\Models\OrderModel; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm payment | Sneaker Peak</title>
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <link rel="stylesheet" href="assets/css/checkout.css">
    <script src="assets/js/checkout.js" defer></script>
</head>
<body>
<header><?php require SNEAKERPEAK_ROOT . '/resources/views/partials/header.php'; ?></header>
<main class="checkout checkout-confirmation">
    <h1>Confirm payment</h1>
    <section class="checkout-panel" aria-label="Payment summary">
        <dl>
            <dt>Order total</dt>
            <dd><strong>RM <?= number_format($all, 2) ?></strong></dd>
            <dt>Shipping address</dt>
            <dd>
                <?= htmlspecialchars((string) $row['Receiver_Name']) ?><br>
                <span class="checkout-address"><?= htmlspecialchars((string) $row['Order_Address']) ?></span>
                <?= htmlspecialchars((string) $row['Receiver_Contract']) ?>
            </dd>
            <dt>Payment method</dt>
            <dd><?= htmlspecialchars($methodLabel) ?></dd>
            <dt>Payment status</dt>
            <dd><?= htmlspecialchars(OrderModel::PAYMENT_STATUSES[$row['Payment_Status']] ?? $row['Payment_Status']) ?></dd>
        </dl>
        <?php if ($message !== null) { ?>
            <p class="checkout-error" role="alert"><?= htmlspecialchars($message) ?></p>
        <?php } elseif ($demoMode) { ?>
            <p>Demo payment. No money will be charged.</p>
        <?php } else { ?>
            <p>Online payment is currently unavailable. Your order is still unpaid.</p>
        <?php } ?>
        <?php if ($demoMode) { ?>
            <form method="post" id="checkout-pay" class="checkout-payment-actions">
                <input type="hidden" name="cart_token" value="<?= htmlspecialchars($token) ?>">
                <input type="hidden" name="payment_review" value="<?= htmlspecialchars($paymentReview) ?>">
                <button type="submit" class="btn checkout-button" name="payment_status" value="Complete">Payment success</button>
                <button type="submit" class="btn btn-outline-danger" name="payment_status" value="Failed">Payment failed</button>
                <button type="submit" class="btn btn-outline-secondary" name="payment_status" value="Paying">Payment pending</button>
            </form>
        <?php } ?>
        <a class="checkout-back" href="selectaddress.php">Change shipping address or payment method</a>
    </section>
</main>
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/footer.php'; ?>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
