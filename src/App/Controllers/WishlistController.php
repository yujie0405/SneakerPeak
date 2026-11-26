<?php

use SneakerPeak\App\Models\WishlistModel;

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/WishlistModel.php';

session_start();
$customer = (string) ($_SESSION['CustomerID'] ?? '');
$wishlist = $customer === '' ? [] : WishlistModel::items($conn, $customer);
require SNEAKERPEAK_ROOT . '/resources/views/account/wishlist.php';