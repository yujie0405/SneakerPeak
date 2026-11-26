<?php

use SneakerPeak\App\Models\CartModel;
use SneakerPeak\App\Models\PromotionModel;
use SneakerPeak\App\Services\CheckoutService;

require_once SNEAKERPEAK_ROOT . '/src/App/Services/CheckoutService.php';
require SNEAKERPEAK_ROOT . '/database/config.php';

session_start();
$customer = (string) ($_SESSION['CustomerID'] ?? '');
$token = $_SESSION['cart_token'] ??= bin2hex(random_bytes(32));
$message = null;

if ($customer !== '' && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!hash_equals($token, (string) ($_POST['cart_token'] ?? ''))) {
        http_response_code(403);
        exit('Please reload your cart and try again.');
    }

    if (isset($_POST['remove'])) {
        CartModel::remove($conn, $customer, (string) $_POST['remove'], (string) ($_POST['size'] ?? ''));
        header('Location: cart.php');
        exit;
    }

    if (isset($_POST['PTC'])) {
        try {
            $_SESSION['Ordercode'] = $_SESSION['pending_checkout'] ?? CheckoutService::start(
                $conn, $customer, trim((string) ($_POST['PC'] ?? ''))
            );
            $_SESSION['pending_checkout'] = $_SESSION['Ordercode'];
            header('Location: selectaddress.php');
            exit;
        } catch (DomainException $error) {
            $message = $error->getMessage();
        } catch (Throwable $error) {
            error_log('SneakerPeak checkout could not create an order.');
            $message = 'Checkout is temporarily unavailable. Please try again.';
        }
    }
}

$items = $customer === '' ? [] : CartModel::items($conn, $customer);
$promoCode = isset($_POST['applyPromo']) && $customer !== '' ? trim((string) ($_POST['Promote_Code'] ?? '')) : '';
$promotion = $promoCode === '' ? null : PromotionModel::availableFor($conn, $customer, $promoCode);
if ($promoCode !== '' && $promotion === null) {
    $message = 'The promotion code is unavailable or has already been used.';
    $promoCode = '';
}
$quote = CheckoutService::quote($items, $promotion);

require SNEAKERPEAK_ROOT . '/resources/views/storefront/cart.php';
