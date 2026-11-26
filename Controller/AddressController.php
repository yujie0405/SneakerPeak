<?php

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/OrderModel.php';

$UID = (string) $_SESSION['CustomerID'];
$orderCode = (string) ($_SESSION['Ordercode'] ?? '');
$row = \SneakerPeak\App\Models\OrderModel::owned($conn, $UID, $orderCode);
if (!$row) {
    header('Location: cart.php');
    exit;
}
if ($row['Payment_Status'] === 'Complete') {
    header('Location: invoice.php');
    exit;
}

$quantity = $conn->prepare('SELECT COALESCE(SUM(l.Qty), 0) FROM T_OrderLineItem l JOIN T_Order o ON o.Id = l.OrderId WHERE o.UserId = ? AND o.OrderCode = ?');
$quantity->execute([$UID, $orderCode]);
$totalQuantity = (int) $quantity->get_result()->fetch_row()[0];
$token = $_SESSION['cart_token'] ??= bin2hex(random_bytes(32));
$message = null;

if (isset($_POST['go'])) {
    if (!hash_equals($token, (string) ($_POST['cart_token'] ?? ''))) {
        http_response_code(403);
        exit('Please reload checkout and try again.');
    }

    $method = (string) ($_POST['card'] ?? '');
    $recipient = trim((string) ($_POST['Receiver_Name'] ?? ''));
    $phone = trim((string) ($_POST['Receiver_Contract_Number'] ?? ''));
    $fields = [
        'Receiver_Address_Line1', 'Receiver_Address_Line2', 'PostCode', 'City', 'State',
    ];
    $addressParts = array_map(static fn($field) => trim((string) ($_POST[$field] ?? '')), $fields);

    if (!in_array($method, ['Card_Payment', 'QR_Payment'], true) || $recipient === '' || $phone === ''
        || in_array('', [$addressParts[0], $addressParts[2], $addressParts[3], $addressParts[4]], true)) {
        $message = 'Enter the recipient and address details, then choose a payment method.';
    } else {
        $address = implode(', ', array_filter($addressParts, static fn($part) => $part !== ''));
        $conn->begin_transaction();
        try {
            $lock = $conn->prepare('SELECT PaymentStatus FROM T_Order WHERE UserId = ? AND OrderCode = ? FOR UPDATE');
            $lock->execute([$UID, $orderCode]);
            if (($lock->get_result()->fetch_row()[0] ?? null) !== 'Paying') {
                throw new DomainException('The order is no longer awaiting payment.');
            }
            $conn->execute_query(
                'UPDATE T_Order SET ShippingAddressSnapshot = ?, ShippingRecipientSnapshot = ?, ShippingPhoneSnapshot = ?, PaymentType = ?
                 WHERE UserId = ? AND OrderCode = ?', [$address, $recipient, $phone, $method, $UID, $orderCode]
            );
            $conn->commit();
            header('Location: Payment.php');
            exit;
        } catch (Throwable $error) {
            $conn->rollback();
            error_log('SneakerPeak could not save the checkout address.');
            $message = 'The address could not be saved. Please try again.';
        }
    }
}

require SNEAKERPEAK_ROOT . '/resources/views/storefront/selectaddress.php';
