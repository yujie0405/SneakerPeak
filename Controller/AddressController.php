<?php

use SneakerPeak\App\Models\CustomerAddressModel;
use SneakerPeak\App\Models\OrderModel;
use SneakerPeak\Core\Database;

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/OrderModel.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/CustomerAddressModel.php';

$conn = Database::getInstance()->getConnection();
$customerId = (string) $_SESSION['CustomerID'];
$orderCode = (string) ($_SESSION['Ordercode'] ?? '');
$row = OrderModel::owned($conn, $customerId, $orderCode);
if (!$row) {
    header('Location: cart.php');
    exit;
}
if ($row['Payment_Status'] === 'Complete') {
    header('Location: invoice.php');
    exit;
}

$totalQuantity = (int) $conn->execute_query(
    'SELECT COALESCE(SUM(Qty), 0) FROM T_OrderLineItem WHERE OrderId = ?', [$row['ID']]
)->fetch_row()[0];
$profile = $conn->execute_query('SELECT DisplayName, Phone FROM T_User WHERE Id = ?', [$customerId])->fetch_assoc();
$addresses = array_column(CustomerAddressModel::all($conn, $customerId), null, 'Id');
$methods = array_diff_key(OrderModel::PAYMENT_TYPES, ['unset' => true]);
$token = $_SESSION['cart_token'] ??= bin2hex(random_bytes(32));
$message = null;
$method = (string) ($row['Payment_Type'] ?? '');
$selected = !empty($row['Order_Address']) || !$addresses ? 'new' : (string) array_key_first($addresses);
$recipient = (string) ($row['Receiver_Name'] ?? $profile['DisplayName'] ?? '');
$phone = (string) ($row['Receiver_Contract'] ?? $profile['Phone'] ?? '');
$address = (string) ($row['Order_Address'] ?? '');
$saveAddress = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($token, (string) ($_POST['cart_token'] ?? ''))) {
        http_response_code(403);
        exit('Please reload checkout and try again.');
    }

    $method = (string) ($_POST['card'] ?? '');
    $selected = (string) ($_POST['shipping_address'] ?? '');
    $recipient = trim((string) ($_POST['recipient'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $address = trim((string) ($_POST['address'] ?? ''));
    $saveAddress = isset($_POST['save_address']);

    try {
        if (($_POST['order_code'] ?? null) !== $orderCode) {
            throw new DomainException('The checkout order changed. Review the current order before continuing.');
        }
        if ($selected !== 'new' && !isset($addresses[$selected])) {
            throw new DomainException('Choose an available shipping address or enter a new one.');
        }
        $saved = $addresses[$selected] ?? null;
        $shippingRecipient = $saved ? (string) ($saved['RecipientName'] ?? $profile['DisplayName'] ?? '') : $recipient;
        $shippingPhone = $saved ? (string) ($saved['RecipientPhone'] ?? $profile['Phone'] ?? '') : $phone;
        $shippingAddress = $saved['AddressText'] ?? $address;
        CustomerAddressModel::validate($shippingRecipient, $shippingPhone, $shippingAddress);
        if (!isset($methods[$method])) {
            throw new DomainException('Choose a payment method.');
        }

        $conn->begin_transaction();
        try {
            $lock = $conn->execute_query('SELECT PaymentStatus FROM T_Order WHERE Id = ? AND UserId = ? FOR UPDATE', [$row['ID'], $customerId]);
            if (!in_array($lock->fetch_row()[0] ?? null, OrderModel::UNPAID_STATUSES, true)) {
                throw new DomainException('The order is no longer awaiting payment.');
            }
            if ($selected === 'new' && $saveAddress) {
                CustomerAddressModel::add($conn, $customerId, $recipient, $phone, $address);
            }
            $conn->execute_query(
                'UPDATE T_Order SET ShippingAddressSnapshot = ?, ShippingRecipientSnapshot = ?, ShippingPhoneSnapshot = ?, PaymentType = ?
                 WHERE Id = ? AND UserId = ?',
                [$shippingAddress, $shippingRecipient, $shippingPhone, $method, $row['ID'], $customerId]
            );
            $conn->commit();
        } catch (Throwable $error) {
            $conn->rollback();
            throw $error;
        }
        header('Location: Payment.php');
        exit;
    } catch (DomainException $error) {
        $message = $error->getMessage();
    } catch (Throwable $error) {
        error_log('SneakerPeak could not save the checkout address.');
        $message = 'The address could not be saved. Please try again.';
    }
}

if (!isset($addresses[$selected])) {
    $selected = 'new';
}

require SNEAKERPEAK_ROOT . '/resources/views/storefront/selectaddress.php';
