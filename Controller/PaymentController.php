<?php

use SneakerPeak\App\Models\OrderModel;
use SneakerPeak\App\Services\CheckoutService;
use SneakerPeak\Core\Database;

require_once SNEAKERPEAK_ROOT . '/src/App/Services/CheckoutService.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/OrderModel.php';
require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';

$conn = Database::getInstance()->getConnection();
$customer = (string) $_SESSION['CustomerID'];
$orderCode = (string) ($_SESSION['Ordercode'] ?? '');
$row = OrderModel::owned($conn, $customer, $orderCode);
if ($row && $row['Payment_Status'] === 'Complete') {
    if (($_SESSION['pending_checkout'] ?? null) === $orderCode) {
        unset($_SESSION['pending_checkout']);
    }
    header('Location: invoice.php');
    exit;
}
if (!$row || !in_array($row['Payment_Type'], ['Card_Payment', 'QR_Payment'], true) || trim((string) $row['Order_Address']) === '') {
    header('Location: selectaddress.php');
    exit;
}

$method = $row['Payment_Type'];
$methodLabel = OrderModel::PAYMENT_TYPES[$method];
$all = (float) $row['Order_TotalAmmount'] + (float) $row['ShippingFeed'];
$demoMode = sneakerpeakSetting('CHECKOUT_DEMO_MODE') === 'true';
$token = $_SESSION['cart_token'] ??= bin2hex(random_bytes(32));
$paymentReview = hash_hmac('sha256', serialize([
    $orderCode, $method, $row['Order_TotalAmmount'], $row['ShippingFeed'],
    $row['Receiver_Name'], $row['Receiver_Contract'], $row['Order_Address'],
]), $token);
$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($token, (string) ($_POST['cart_token'] ?? ''))) {
        http_response_code(403);
        exit('Please reload checkout and try again.');
    }
    try {
        if (!$demoMode) {
            throw new DomainException('Online payment is currently unavailable. Your order is still unpaid.');
        }
        if (!hash_equals($paymentReview, (string) ($_POST['payment_review'] ?? ''))) {
            throw new DomainException('The checkout details changed. Review them before paying.');
        }
        $status = (string) ($_POST['payment_status'] ?? '');
        CheckoutService::recordDemoPayment($conn, $customer, $orderCode, $method, $status);
        if ($status === 'Complete') {
            unset($_SESSION['pending_checkout']);
            header('Location: invoice.php', true, 303);
        } else {
            header('Location: Payment.php', true, 303);
        }
        exit;
    } catch (DomainException $error) {
        $message = $error->getMessage();
    } catch (Throwable $error) {
        error_log('SneakerPeak demo payment could not complete.');
        $message = 'Checkout is temporarily unavailable. Please try again.';
    }
}

require SNEAKERPEAK_ROOT . '/resources/views/storefront/Payment.php';
