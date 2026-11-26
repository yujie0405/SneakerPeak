<?php

use SneakerPeak\App\Services\EmailService;

$token = $_SESSION['order_admin_token'] ?? null;
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $token === null
    || !hash_equals($token, (string) ($_POST['order_token'] ?? ''))) {
    http_response_code(403);
    exit('Access denied.');
}

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/EmailService.php';

$path = strtolower(basename((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
$markShipped = $path === 'changestatus.php';
$orderCode = (string) ($_POST['orderCode'] ?? '');
$sql = $markShipped
    ? "UPDATE oder SET Order_Status = 'Shipped' WHERE Order_code = ? AND Order_Status = 'Pending' AND Payment_Status = 'Complete'"
    : "UPDATE oder SET ask = 1 WHERE Order_code = ? AND Order_Status IN ('Shipping', 'Shipped') AND ask = '0'";
$update = $conn->prepare($sql);
$update->execute([$orderCode]);
if (!$update->affected_rows) {
    http_response_code(409);
    exit('This order cannot move to the requested status.');
}

$lookup = $conn->prepare('SELECT c.email FROM customer c JOIN oder o ON c.id = o.UID WHERE o.Order_code = ? LIMIT 1');
$lookup->execute([$orderCode]);
$email = (string) ($lookup->get_result()->fetch_row()[0] ?? '');
$subject = $markShipped ? 'Order shipped' : 'Confirm order completion';
$message = $markShipped
    ? "Your SneakerPeak order $orderCode has shipped."
    : "Please sign in and confirm the completion of SneakerPeak order $orderCode in Order History.";
echo EmailService::send($email, $subject, $message)
    ? 'Email notification sent to the customer!'
    : 'Order updated. The email notification could not be sent.';