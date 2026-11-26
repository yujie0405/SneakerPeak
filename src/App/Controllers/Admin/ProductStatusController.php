<?php

$token = $_SESSION['catalog_admin_token'] ?? null;
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $token === null
    || !hash_equals($token, (string) ($_POST['catalog_token'] ?? ''))) {
    http_response_code(403);
    exit('Access denied.');
}

require SNEAKERPEAK_ROOT . '/database/config.php';

$path = strtolower(basename((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
if ($path === 'update_status.php') {
    $update = $conn->prepare(
        "UPDATE T_ProductSizeAndCategory i JOIN T_Product p ON p.Id = i.ProductId JOIN E_Size z ON z.Id = i.SizeId
         SET i.IsAvailable = NOT i.IsAvailable WHERE p.Name = ? AND z.SizeLabel = ? AND i.Qty > 0"
    );
    $update->execute([(string) ($_POST['prodName'] ?? ''), (string) ($_POST['prodSize'] ?? '')]);
    echo $update->affected_rows > 0 ? 'Quantity status updated successfully' : 'Select an existing size with stock.';
    return;
}

$status = $path === 'delete_item.php' ? 'DEACTIVATED' : 'AVAILABLE';
$update = $conn->prepare('UPDATE T_Product SET ProductStatusId = (SELECT Id FROM E_ProductStatus WHERE Code = ?) WHERE Id = ?');
$update->execute([$status, (int) ($_POST['id'] ?? 0)]);
echo $status === 'DEACTIVATED' ? 'Item deleted successfully' : 'Item is now available';