<?php

use SneakerPeak\App\Services\CheckoutService;

require_once SNEAKERPEAK_ROOT . '/src/App/Services/CheckoutService.php';
require SNEAKERPEAK_ROOT . '/database/config.php';

$customer = (string) $_SESSION['CustomerID'];
$Ordercode = (string) ($_SESSION['Ordercode'] ?? '');
require_once SNEAKERPEAK_ROOT . '/src/App/Models/OrderModel.php';
$row = \SneakerPeak\App\Models\OrderModel::owned($conn, $customer, $Ordercode);
if (!$row || !in_array($row['Payment_Type'], ['Card_Payment', 'QR_Payment'], true)) {
    header('Location: selectaddress.php');
    exit;
}
if ($row['Payment_Status'] === 'Complete') {
    header('Location: invoice.php');
    exit;
}

$method = $row['Payment_Type'];
$total = (float) $row['Order_TotalAmmount'];
$ship = (float) $row['ShippingFeed'];
$all = $total + $ship;
$demoMode = sneakerpeakSetting('CHECKOUT_DEMO_MODE') === 'true';
$token = $_SESSION['cart_token'] ??= bin2hex(random_bytes(32));
$message = null;

if (isset($_POST['complete_demo'])) {
    if (!$demoMode || !hash_equals($token, (string) ($_POST['cart_token'] ?? ''))) {
        http_response_code(403);
        exit('This payment submission is unavailable.');
    }
    try {
        CheckoutService::completeDemo($conn, $customer, $Ordercode, $method);
        unset($_SESSION['pending_checkout']);
        header('Location: invoice.php');
        exit;
    } catch (DomainException $error) {
        $message = $error->getMessage();
    } catch (Throwable $error) {
        error_log('SneakerPeak demo payment could not complete.');
        $message = 'Checkout is temporarily unavailable. Please try again.';
    }
}

require SNEAKERPEAK_ROOT . '/resources/views/storefront/Payment.php';
