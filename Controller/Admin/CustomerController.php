<?php

use SneakerPeak\App\Services\CatalogInput;

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();
require_once SNEAKERPEAK_ROOT . '/src/App/Services/CatalogInput.php';

$name = CatalogInput::text($_GET, 'name');
$requestedPage = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;
$token = $_SESSION['customer_admin_token'] ??= bin2hex(random_bytes(32));
$pageUrl = static function (int $number) use ($name): string {
    $query = array_filter(['name' => $name, 'page' => $number > 1 ? $number : ''], static fn($value): bool => $value !== '');
    return 'viewCustomers.php' . ($query ? '?' . http_build_query($query) : '');
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($token, CatalogInput::text($_POST, 'customer_token'))) {
        http_response_code(403);
        exit('Please reload the customer list and try again.');
    }
    $id = CatalogInput::id($_POST['customer_id'] ?? null);
    if ($id === null) {
        http_response_code(400);
        exit('Choose a valid customer.');
    }
    $conn->execute_query('UPDATE T_User SET IsActive = 0 WHERE Id = ? AND IsAdmin = 0 AND IsActive = 1', [$id]);
    $_SESSION['customer_admin_notice'] = $conn->affected_rows === 1
        ? 'Customer account terminated.' : 'This customer account is already inactive or unavailable.';
    header('Location: ' . $pageUrl($requestedPage), true, 303);
    exit;
}

$notice = $_SESSION['customer_admin_notice'] ?? null;
unset($_SESSION['customer_admin_notice']);
$where = 'FROM T_User WHERE IsAdmin = 0';
$parameters = [];
if ($name !== '') {
    $where .= ' AND LOCATE(?, DisplayName) > 0';
    $parameters[] = $name;
}
$total = (int) $conn->execute_query('SELECT COUNT(*) ' . $where, $parameters)->fetch_row()[0];
$pageSize = 20;
$pageCount = max(1, (int) ceil($total / $pageSize));
$page = min($requestedPage, $pageCount);
$customers = $conn->execute_query(
    'SELECT Id, DisplayName, Email, Phone, IsActive ' . $where . ' ORDER BY DisplayName, Id LIMIT ? OFFSET ?',
    [...$parameters, $pageSize, ($page - 1) * $pageSize]
)->fetch_all(MYSQLI_ASSOC);
require SNEAKERPEAK_ROOT . '/resources/views/admin/viewCustomers.php';
