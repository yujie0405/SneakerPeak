<?php

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/ProductModel.php';

$pn = (string) ($_GET['prodName'] ?? $_SESSION['prodName'] ?? '');
$find = $conn->prepare(\SneakerPeak\App\Models\ProductModel::CATALOG . ' WHERE p.Name = ? ORDER BY p.Id LIMIT 1');
$find->execute([$pn]);
$row = $find->get_result()->fetch_assoc();
if (!$row) {
    http_response_code(404);
    exit('Product not found.');
}
$catalogToken = $_SESSION['catalog_admin_token'] ??= bin2hex(random_bytes(32));
$message = null;
if (isset($_POST['Update'])) {
    if (!hash_equals($catalogToken, (string) ($_POST['catalog_token'] ?? ''))) {
        http_response_code(403);
        exit('Please reload the product page and try again.');
    }

    $name = trim((string) ($_POST['productName'] ?? ''));
    $price = filter_var($_POST['productPrice'] ?? null, FILTER_VALIDATE_FLOAT);
    $details = (string) ($_POST['productDetails'] ?? '');
    if ($name === '' || $price === false || $price <= 0) {
        $message = 'Enter a product name and a positive price.';
    } else {
        $conn->begin_transaction();
        try {
            $duplicate = $conn->prepare('SELECT Id FROM T_Product WHERE Name = ? AND Id <> ? LIMIT 1');
            $duplicate->execute([$name, $row['Prod_ID']]);
            if ($duplicate->get_result()->fetch_row()) {
                throw new DomainException('A product already uses this name.');
            }
            $update = $conn->prepare('UPDATE T_Product SET Name = ?, Price = ?, Details = ? WHERE Id = ?');
            $update->execute([$name, $price, $details, $row['Prod_ID']]);
            $conn->commit();
            $_SESSION['prodName'] = $name;
            header('Location: admin_productdetails.php?prodName=' . rawurlencode($name));
            exit;
        } catch (DomainException $error) {
            $conn->rollback();
            $message = $error->getMessage();
        } catch (Throwable $error) {
            $conn->rollback();
            error_log('SneakerPeak admin product update failed.');
            $message = 'The product could not be updated.';
        }
    }
}

$inventory = $conn->prepare(
    "SELECT p.Name AS Prod_Name, z.SizeLabel AS Prod_Size, i.Qty AS Prod_Qty,
       IF(i.Qty > 0 AND i.IsAvailable = 1, 'Available', 'UnAvailable') AS Display_status
     FROM T_ProductSizeAndCategory i JOIN E_Size z ON z.Id = i.SizeId JOIN T_Product p ON p.Id = i.ProductId
     WHERE i.ProductId = ? ORDER BY z.SortOrder, z.Id"
);
$inventory->execute([$row['Prod_ID']]);
$sizes = $inventory->get_result()->fetch_all(MYSQLI_ASSOC);
require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_productdetails.php';