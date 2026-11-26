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
        "UPDATE sizeandqty SET Qty_status = IF(Qty_status = 'Available', 'UnAvailable', 'Available')
         WHERE Prod_Name = ? AND Prod_Size = ? AND CAST(Prod_Qty AS SIGNED) > 0"
    );
    $update->execute([(string) ($_POST['prodName'] ?? ''), (string) ($_POST['prodSize'] ?? '')]);
    echo $update->affected_rows > 0 ? 'Quantity status updated successfully' : 'Select an existing size with stock.';
    return;
}

$status = $path === 'delete_item.php' ? 'Deleted' : 'available';
$update = $conn->prepare('UPDATE product SET Prod_Status = ? WHERE Prod_ID = ?');
$update->execute([$status, (int) ($_POST['id'] ?? 0)]);
echo $status === 'Deleted' ? 'Item deleted successfully' : 'Item is now available';