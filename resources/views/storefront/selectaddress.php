<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout | Sneaker Peak</title>
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <link rel="stylesheet" href="assets/css/checkout.css">
    <script src="assets/js/checkout.js" defer></script>
</head>
<body>
<header><?php require SNEAKERPEAK_ROOT . '/resources/views/partials/header.php'; ?></header>
<main class="checkout">
    <h1>Checkout</h1>
    <?php if ($message !== null) { ?>
        <p class="checkout-error" role="alert"><?= htmlspecialchars($message) ?></p>
    <?php } ?>
    <div class="checkout-grid">
        <form method="post" class="checkout-panel">
            <input type="hidden" name="cart_token" value="<?= htmlspecialchars($token) ?>">
            <input type="hidden" name="order_code" value="<?= htmlspecialchars($orderCode) ?>">
            <fieldset class="checkout-options">
                <legend>Shipping address</legend>
                <?php foreach ($addresses as $saved) { ?>
                    <label class="checkout-option">
                        <input type="radio" name="shipping_address" value="<?= htmlspecialchars((string) $saved['Id']) ?>"
                               <?= $selected === (string) $saved['Id'] ? 'checked' : '' ?>>
                        <span>
                            <strong><?= htmlspecialchars($saved['RecipientName'] ?? $profile['DisplayName'] ?? '') ?></strong>
                            <span class="checkout-address"><?= htmlspecialchars($saved['AddressText']) ?></span>
                            <span><?= htmlspecialchars($saved['RecipientPhone'] ?? $profile['Phone'] ?? '') ?></span>
                        </span>
                    </label>
                <?php } ?>
                <label class="checkout-option">
                    <input type="radio" name="shipping_address" value="new" aria-controls="manual-shipping"
                           <?= $selected === 'new' ? 'checked' : '' ?>>
                    <span>Enter a new address</span>
                </label>
                <div class="checkout-fields" id="manual-shipping">
                    <label for="shipping-recipient">Recipient name</label>
                    <input id="shipping-recipient" name="recipient" class="form-control" maxlength="255" autocomplete="shipping name"
                           value="<?= htmlspecialchars($recipient) ?>">
                    <label for="shipping-phone">Phone number</label>
                    <input id="shipping-phone" name="phone" type="tel" class="form-control" maxlength="255" autocomplete="shipping tel"
                           value="<?= htmlspecialchars($phone) ?>">
                    <label for="shipping-address">Full shipping address</label>
                    <textarea id="shipping-address" name="address" class="form-control" rows="3" maxlength="255"
                              autocomplete="shipping street-address"><?= htmlspecialchars($address) ?></textarea>
                    <label class="checkout-option">
                        <input type="checkbox" name="save_address" value="1" <?= $saveAddress ? 'checked' : '' ?>>
                        <span>Save this address to my profile</span>
                    </label>
                </div>
            </fieldset>
            <fieldset class="checkout-options">
                <legend>Payment method</legend>
                <?php foreach ($methods as $code => $label) { ?>
                    <label class="checkout-option">
                        <input type="radio" name="card" value="<?= htmlspecialchars($code) ?>" required <?= $method === $code ? 'checked' : '' ?>>
                        <span><?= htmlspecialchars($label) ?></span>
                    </label>
                <?php } ?>
            </fieldset>
            <button type="submit" class="btn checkout-button">Continue to payment</button>
        </form>
        <aside class="checkout-panel" aria-labelledby="order-summary">
            <h2 id="order-summary">Order summary</h2>
            <dl class="checkout-totals">
                <dt>Items (<?= $totalQuantity ?>)</dt><dd>RM <?= number_format((float) $row['Order_TotalAmmount'], 2) ?></dd>
                <dt>Shipping</dt><dd>RM <?= number_format((float) $row['ShippingFeed'], 2) ?></dd>
                <dt>Total</dt><dd><strong>RM <?= number_format((float) $row['Order_TotalAmmount'] + (float) $row['ShippingFeed'], 2) ?></strong></dd>
            </dl>
        </aside>
    </div>
</main>
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/footer.php'; ?>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
