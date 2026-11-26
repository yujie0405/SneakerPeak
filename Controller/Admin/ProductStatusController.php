<?php

use SneakerPeak\App\Services\CatalogInput;
use SneakerPeak\Manager\EncryptionManager;

$token = $_SESSION['catalog_admin_token'] ?? null;
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $token === null
    || !hash_equals($token, (string) ($_POST['catalog_token'] ?? ''))) {
    http_response_code(403);
    exit('Access denied.');
}

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();
require_once SNEAKERPEAK_ROOT . '/src/App/Services/CatalogInput.php';

$productId = CatalogInput::id(EncryptionManager::decrypt($_POST['id'] ?? null, EncryptionManager::PRODUCT_DETAILS));
if ($productId === null) {
    http_response_code(400);
    exit('The product link is invalid.');
}

$path = strtolower(basename((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
if ($path === 'update_status.php') {
    $rows = $conn->execute_query(
        'SELECT i.Id FROM T_ProductSizeAndCategory i
         JOIN E_Size z ON z.Id = i.SizeId WHERE i.ProductId = ? AND z.SizeLabel = ? LIMIT 2',
        [$productId, CatalogInput::text($_POST, 'prodSize')]
    )->fetch_all();
    if (count($rows) !== 1) {
        http_response_code(422);
        exit('Choose a specific size row on the product details page.');
    }
    $update = $conn->prepare(
        'UPDATE T_ProductSizeAndCategory SET IsAvailable = NOT IsAvailable WHERE Id = ? AND Qty > 0'
    );
    $update->execute([$rows[0][0]]);
    echo $update->affected_rows > 0 ? 'Quantity status updated successfully' : 'Select an existing size with stock.';
    return;
}

$status = $path === 'delete_item.php' ? 'DEACTIVATED' : 'AVAILABLE';
$update = $conn->prepare('UPDATE T_Product SET ProductStatusId = (SELECT Id FROM E_ProductStatus WHERE Code = ?) WHERE Id = ?');
$update->execute([$status, $productId]);
echo $status === 'DEACTIVATED' ? 'Item deleted successfully' : 'Item is now available';
