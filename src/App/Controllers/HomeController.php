<?php

use SneakerPeak\App\Models\ProductModel;
use SneakerPeak\App\Models\WishlistModel;
use SneakerPeak\Core\Database;

require_once SNEAKERPEAK_ROOT . '/src/App/Models/ProductModel.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/WishlistModel.php';

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$result = ProductModel::latest();
$wishlistMessage = null;
if (isset($_GET['add']) && is_string($_GET['add'])) {
    if (!isset($_SESSION['CustomerID'])) {
        header('Location: Login.php');
        exit;
    }
    $added = WishlistModel::add(Database::getInstance()->getConnection(), (string) $_SESSION['CustomerID'], $_GET['add']);
    $wishlistMessage = $added ? 'Item successfully added to wishlist' : 'The item was already added or is unavailable.';
}

require SNEAKERPEAK_ROOT . '/resources/views/storefront/index.php';
