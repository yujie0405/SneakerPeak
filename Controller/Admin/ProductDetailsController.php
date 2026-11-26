<?php

use SneakerPeak\App\Models\ProductModel;
use SneakerPeak\App\Services\CatalogInput;
use SneakerPeak\Manager\EncryptionManager;

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();
require_once SNEAKERPEAK_ROOT . '/src/App/Models/ProductModel.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/CatalogInput.php';

$idToken = $_GET['id'] ?? null;
$productId = CatalogInput::id(EncryptionManager::decrypt($idToken, EncryptionManager::PRODUCT_DETAILS));
if ($productId === null) {
    http_response_code(400);
    exit($idToken === null ? 'A product link is required.' : 'The product link is invalid.');
}
$row = $conn->execute_query(ProductModel::CATALOG . ' WHERE p.Id = ?', [$productId])->fetch_assoc();
if (!$row) {
    http_response_code(404);
    exit('Product not found.');
}
$productUrl = 'admin_productdetails.php?id=' . $idToken;
$catalogToken = $_SESSION['catalog_admin_token'] ??= bin2hex(random_bytes(32));
$notice = $_SESSION['catalog_product_notice'][$productId] ?? null;
$highlightRow = $_SESSION['catalog_inventory_row'][$productId] ?? null;
unset($_SESSION['catalog_product_notice'][$productId], $_SESSION['catalog_inventory_row'][$productId]);

$lookups = [
    'brand_id' => $conn->query('SELECT Id, Name FROM E_Brand ORDER BY Name')->fetch_all(MYSQLI_ASSOC),
    'category_id' => $conn->query('SELECT Id, Name FROM E_Category ORDER BY Name')->fetch_all(MYSQLI_ASSOC),
    'subcategory_id' => $conn->query('SELECT Id, Name FROM E_SubCategory ORDER BY Name')->fetch_all(MYSQLI_ASSOC),
];
$edit = [
    'productName' => $row['Prod_Name'], 'productPrice' => $row['Prod_Price'], 'productDetails' => $row['Prod_Details'],
    'brand_id' => (string) $row['BrandId'], 'category_id' => (string) $row['CategoryId'],
    'subcategory_id' => (string) $row['SubCategoryId'],
];
$error = null;
$action = '';
$inventoryId = null;
$submittedQuantity = null;
$submittedStatus = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($catalogToken, CatalogInput::text($_POST, 'catalog_token'))) {
        http_response_code(403);
        exit('Please reload the product page and try again.');
    }

    $action = isset($_POST['Update']) ? 'product' : CatalogInput::text($_POST, 'action');
    $inventoryId = CatalogInput::id($_POST['inventory_id'] ?? null);
    $submittedQuantity = CatalogInput::text($_POST, 'quantity');
    $submittedStatus = CatalogInput::text($_POST, 'availability');
    $conn->begin_transaction();
    try {
        if ($action === 'product') {
            foreach ($edit as $field => $current) {
                if (array_key_exists($field, $_POST)) $edit[$field] = CatalogInput::text($_POST, $field);
            }
            if ($edit['productName'] === '' || mb_strlen($edit['productName']) > 255
                || !CatalogInput::validPrice($edit['productPrice'])
                || $edit['productDetails'] === '' || strlen($edit['productDetails']) > 65535) {
                throw new DomainException('Enter a product name, a valid nonnegative price, and product details.');
            }
            foreach ($lookups as $field => $options) {
                if (!in_array($edit[$field], array_map('strval', array_column($options, 'Id')), true)) {
                    throw new DomainException('Choose a current brand, category, and subcategory.');
                }
            }
            $conn->execute_query(
                'UPDATE T_Product SET Name = ?, Price = ?, Details = ?, BrandId = ?, CategoryId = ?, SubCategoryId = ? WHERE Id = ?',
                [...array_values($edit), $productId]
            );
            $notice = 'Product updated.';
        } elseif ($action === 'add-size') {
            $sizeId = CatalogInput::id($_POST['size_id'] ?? null);
            $qty = CatalogInput::quantity($submittedQuantity);
            if ($qty === false || $sizeId === null
                || !$conn->execute_query('SELECT 1 FROM E_Size WHERE Id = ?', [$sizeId])->fetch_row()) {
                throw new DomainException('Choose an existing size and enter a whole-number quantity from 0 to 4,294,967,295.');
            }
            $conn->execute_query(
                'INSERT INTO T_ProductSizeAndCategory (ProductId, SizeId, Qty, IsAvailable) VALUES (?, ?, ?, ?)',
                [$productId, $sizeId, $qty, (int) ($qty > 0)]
            );
            $inventoryId = (string) $conn->insert_id;
            $notice = 'Size added.';
        } elseif (in_array($action, ['status', 'quantity'], true)) {
            $inventory = $inventoryId === null ? null : $conn->execute_query(
                'SELECT Qty FROM T_ProductSizeAndCategory WHERE Id = ? AND ProductId = ? FOR UPDATE',
                [$inventoryId, $productId]
            )->fetch_assoc();
            if (!$inventory) throw new DomainException('This size row no longer exists for this product.');

            if ($action === 'status') {
                if (!in_array($submittedStatus, ['0', '1'], true)) {
                    throw new DomainException('Choose Available or Unavailable.');
                }
                if ($submittedStatus === '1' && (int) $inventory['Qty'] === 0) {
                    throw new DomainException('Restore the quantity before marking this size available.');
                }
                $conn->execute_query('UPDATE T_ProductSizeAndCategory SET IsAvailable = ? WHERE Id = ?', [$submittedStatus, $inventoryId]);
                $notice = 'Size status updated.';
            } else {
                $qty = CatalogInput::quantity($submittedQuantity);
                if ($qty === false) {
                    throw new DomainException('Enter a whole-number quantity from 0 to 4,294,967,295.');
                }
                $conn->execute_query('UPDATE T_ProductSizeAndCategory SET Qty = ? WHERE Id = ?', [$qty, $inventoryId]);
                $notice = 'Size quantity updated.';
            }
        } else {
            throw new DomainException('Choose a product or inventory action.');
        }

        $conn->commit();
        $_SESSION['catalog_product_notice'][$productId] = $notice;
        if ($inventoryId !== null) $_SESSION['catalog_inventory_row'][$productId] = $inventoryId;
        header('Location: ' . $productUrl . ($inventoryId !== null ? '#inventory-' . $inventoryId : ''));
        exit;
    } catch (Throwable $exception) {
        $conn->rollback();
        $error = match (true) {
            $exception instanceof DomainException => $exception->getMessage(),
            $exception->getCode() === 1062 && $action === 'add-size' => 'This size already exists. Adjust its quantity in the inventory list.',
            $exception->getCode() === 1062 => 'A product with this name already exists.',
            $exception->getCode() === 1452 => 'A lookup changed while saving. Reload the page and try again.',
            default => 'The change could not be saved.',
        };
        http_response_code(422);
    }
}

$sizes = $conn->execute_query(
    'SELECT i.Id, z.SizeLabel, z.SizingSystem, i.Qty, i.IsAvailable FROM T_ProductSizeAndCategory i
     JOIN E_Size z ON z.Id = i.SizeId WHERE i.ProductId = ? ORDER BY z.SortOrder, z.SizeLabel, z.Id',
    [$productId]
)->fetch_all(MYSQLI_ASSOC);
$newSizes = $conn->execute_query(
    'SELECT z.Id, z.SizeLabel, z.SizingSystem FROM E_Size z
     WHERE NOT EXISTS (SELECT 1 FROM T_ProductSizeAndCategory i WHERE i.ProductId = ? AND i.SizeId = z.Id)
     ORDER BY z.SortOrder, z.SizeLabel, z.Id',
    [$productId]
)->fetch_all(MYSQLI_ASSOC);
require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_productdetails.php';
