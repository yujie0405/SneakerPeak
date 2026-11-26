<?php

use SneakerPeak\App\Models\ProductModel;
use SneakerPeak\App\Models\WishlistModel;

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();
require_once SNEAKERPEAK_ROOT . '/src/App/Models/ProductModel.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/WishlistModel.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$customer = (string) ($_SESSION['CustomerID'] ?? '');
$filters = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
$search = is_string($filters['search'] ?? null) ? trim($filters['search']) : '';
$categories = array_column($conn->query('SELECT Name FROM E_Category ORDER BY Name')->fetch_all(MYSQLI_ASSOC), 'Name');
$category = in_array($filters['Cate'] ?? null, $categories, true) ? $filters['Cate'] : '';
$brandRows = $conn->query('SELECT Name AS Brand_Name FROM E_Brand')->fetch_all(MYSQLI_ASSOC);
$subcategoryRows = $conn->query('SELECT Name AS S_Name FROM E_SubCategory')->fetch_all(MYSQLI_ASSOC);
$brandOptions = array_unique(array_map('trim', array_column($brandRows, 'Brand_Name')));
$subcategoryOptions = array_unique(array_map('trim', array_column($subcategoryRows, 'S_Name')));
$selected = static fn($values) => array_map('trim', array_filter((array) $values, 'is_string'));
$brands = array_values(array_intersect($brandOptions, $selected($filters['brands'] ?? [])));
$subcategories = array_values(array_intersect($subcategoryOptions, $selected($filters['subc'] ?? [])));
$requestedPage = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? 1
    : (filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1);
[$products, $total, $page, $pageCount] = ProductModel::browse($conn, $search, $brands, $subcategories, $category, $requestedPage);
$filterCount = ($search !== '' ? 1 : 0) + ($category !== '' ? 1 : 0) + count($brands) + count($subcategories);
$filterQuery = array_filter(['search' => $search, 'Cate' => $category, 'brands' => $brands, 'subc' => $subcategories]);
$pageUrl = static fn(int $number): string => 'menu.php?' . http_build_query($filterQuery + ['page' => $number]);
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
