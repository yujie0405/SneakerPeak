<?php

use SneakerPeak\App\Models\ProductModel;
use SneakerPeak\App\Models\WishlistModel;

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/ProductModel.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/WishlistModel.php';

session_start();
$customer = (string) ($_SESSION['CustomerID'] ?? '');
$search = (string) ($_POST['search'] ?? '');
$brands = isset($_POST['Filter']) ? (array) ($_POST['brands'] ?? []) : [];
$subcategories = isset($_POST['Filter']) ? (array) ($_POST['subc'] ?? []) : [];
$result = ProductModel::browse($conn, $search, $brands, $subcategories);
$brandRows = $conn->query('SELECT Brand_Name FROM brand')->fetch_all(MYSQLI_ASSOC);
$subcategoryRows = $conn->query('SELECT S_Name FROM sub_category')->fetch_all(MYSQLI_ASSOC);
$wishlistMessage = null;
if (isset($_GET['add']) && is_string($_GET['add'])) {
    if ($customer === '') {
        header('Location: Login.php');
        exit;
    }
    $added = WishlistModel::add($conn, $customer, $_GET['add']);
    $wishlistMessage = $added ? 'Item successfully added to wishlist' : 'The item was already added or is unavailable.';
}

require SNEAKERPEAK_ROOT . '/resources/views/storefront/menu.php';
