<?php

use SneakerPeak\App\Models\WishlistModel;

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();
require_once SNEAKERPEAK_ROOT . '/src/App/Models/WishlistModel.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$customer = (string) ($_SESSION['CustomerID'] ?? '');
$wishlist = $customer === '' ? [] : WishlistModel::items($conn, $customer);
require SNEAKERPEAK_ROOT . '/resources/views/account/wishlist.php';