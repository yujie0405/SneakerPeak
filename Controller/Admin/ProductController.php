<?php

use SneakerPeak\App\Models\ProductModel;
use SneakerPeak\App\Services\CatalogInput;

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/ProductModel.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/CatalogInput.php';

$filters = [];
foreach (['search', 'status', 'brand', 'category', 'subcategory', 'min_price', 'max_price'] as $field) {
    $filters[$field] = CatalogInput::text($_GET, $field);
}
$errors = [];
foreach (['min_price' => 'Minimum price', 'max_price' => 'Maximum price'] as $field => $label) {
    if (is_array($_GET[$field] ?? null) || ($filters[$field] !== '' && !CatalogInput::validPrice($filters[$field]))) {
        $errors[$field] = "$label must be a nonnegative amount with up to two decimal places.";
    }
}
foreach (['category' => 'category', 'subcategory' => 'subcategory'] as $field => $label) {
    if (is_array($_GET[$field] ?? null) || ($filters[$field] !== '' && CatalogInput::id($filters[$field]) === null)) {
        $errors[$field] = "Choose a valid $label.";
    }
}
if (!$errors && $filters['min_price'] !== '' && $filters['max_price'] !== ''
    && (float) $filters['min_price'] > (float) $filters['max_price']) {
    $errors['max_price'] = 'Maximum price must be at least the minimum price.';
}

$requestedPage = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;
$products = [];
$total = 0;
$page = $pageCount = 1;
if (!$errors) {
    [$products, $total, $page, $pageCount] = ProductModel::adminBrowse($conn, $filters, $requestedPage);
}
$brands = $conn->query('SELECT Name FROM E_Brand ORDER BY Name')->fetch_all(MYSQLI_ASSOC);
$categories = $conn->query('SELECT Id, Name FROM E_Category ORDER BY Name')->fetch_all(MYSQLI_ASSOC);
$subcategories = $conn->query('SELECT Id, Name FROM E_SubCategory ORDER BY Name')->fetch_all(MYSQLI_ASSOC);
$filterQuery = array_filter($filters, static fn(string $value): bool => $value !== '');
$pageUrl = static fn(int $number): string => 'admin_productlist.php?' . http_build_query($filterQuery + ['page' => $number]);
$catalogToken = $_SESSION['catalog_admin_token'] ??= bin2hex(random_bytes(32));
require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_productlist.php';
