<?php

use SneakerPeak\Manager\CatalogImageUpload;
use SneakerPeak\App\Services\CatalogInput;
use SneakerPeak\Manager\EncryptionManager;

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();
require_once SNEAKERPEAK_ROOT . '/src/App/Services/CatalogInput.php';

$catalogToken = $_SESSION['catalog_admin_token'] ??= bin2hex(random_bytes(32));
$draftId = bin2hex(random_bytes(16));
$fields = array_fill_keys(['prod_name', 'prod_price', 'prod_colour', 'prod_details', 'brand_id', 'category_id', 'subcategory_id'], '');
$sizeRows = [['size_id' => '', 'qty' => '']];
$errors = [];
$choices = [
    'brand_id' => $conn->query('SELECT Id, Name FROM E_Brand ORDER BY Name')->fetch_all(MYSQLI_ASSOC),
    'category_id' => $conn->query('SELECT Id, Name FROM E_Category ORDER BY Name')->fetch_all(MYSQLI_ASSOC),
    'subcategory_id' => $conn->query('SELECT Id, Name FROM E_SubCategory ORDER BY Name')->fetch_all(MYSQLI_ASSOC),
];
$sizes = $conn->query('SELECT Id, SizeLabel, SizingSystem FROM E_Size ORDER BY SortOrder, SizeLabel, Id')->fetch_all(MYSQLI_ASSOC);
$imageFields = ['prod_img1' => 'Main image', 'prod_img2' => 'Second image', 'prod_img3' => 'Third image'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = ($_SERVER['HTTP_ACCEPT'] ?? '') === 'application/json';
    $draftId = CatalogInput::text($_POST, 'draft_id');
    if (!hash_equals($catalogToken, CatalogInput::text($_POST, 'catalog_token'))
        || !preg_match('/^[a-f0-9]{32}$/D', $draftId)) {
        http_response_code(403);
        exit('Reload the form and try again.');
    }
    $savedId = CatalogInput::id($_SESSION['catalog_saved_product_ids'][$draftId] ?? null);
    $savedUrl = $savedId === null ? null : 'admin_productdetails.php?id='
        . EncryptionManager::encrypt($savedId, EncryptionManager::PRODUCT_DETAILS);

    if ($savedUrl === null) {
        foreach ($fields as $field => $_) {
            $fields[$field] = CatalogInput::text($_POST, $field);
        }
        foreach (['prod_name' => 'product name', 'prod_colour' => 'colour'] as $field => $label) {
            if ($fields[$field] === '' || mb_strlen($fields[$field]) > 255) {
                $errors[$field] = "Enter a $label of up to 255 characters.";
            }
        }
        if (!CatalogInput::validPrice($fields['prod_price'])) {
            $errors['prod_price'] = 'Enter a nonnegative price with up to two decimal places.';
        }
        if ($fields['prod_details'] === '' || strlen($fields['prod_details']) > 65535) {
            $errors['prod_details'] = 'Enter product details up to 65,535 bytes.';
        }
        foreach ($choices as $field => $options) {
            if (!in_array($fields[$field], array_map('strval', array_column($options, 'Id')), true)) {
                $errors[$field] = 'Choose an existing ' . str_replace('_id', '', $field) . '.';
            }
        }
        foreach ($imageFields as $field => $label) {
            if (($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                $errors[$field] = "Choose a file for the " . strtolower($label) . '.';
            }
        }

        $postedSizes = is_array($_POST['size_id'] ?? null) ? $_POST['size_id'] : [];
        $postedQuantities = is_array($_POST['qty'] ?? null) ? $_POST['qty'] : [];
        $validSizes = array_map('strval', array_column($sizes, 'Id'));
        $sizeRows = [];
        $inventory = [];
        if (!$postedSizes || count($postedSizes) > count($validSizes)) {
            $errors['inventory'] = 'Add at least one size and use each size once.';
        }
        foreach (array_slice($postedSizes, 0, count($validSizes), true) as $index => $value) {
            if (!is_int($index) || $index < 0) continue;
            $sizeId = is_string($value) ? $value : '';
            $rawQty = is_string($postedQuantities[$index] ?? null) ? $postedQuantities[$index] : '';
            $qty = CatalogInput::quantity($rawQty);
            $sizeRows[$index] = ['size_id' => $sizeId, 'qty' => $rawQty];
            if (!in_array($sizeId, $validSizes, true) || isset($inventory[$sizeId])) {
                $errors["size_id[$index]"] = 'Choose an existing size that appears once.';
            }
            if ($qty === false) {
                $errors["qty[$index]"] = 'Enter a whole number from 0 to 4,294,967,295.';
            }
            $inventory[$sizeId] = $qty;
        }
        if (!$inventory) $errors['inventory'] = 'Add at least one size.';
        if (!$sizeRows) $sizeRows = [['size_id' => '', 'qty' => '']];

        if (!$errors) {
            $files = [];
            $imageField = null;
            $conn->begin_transaction();
            try {
                foreach ($imageFields as $field => $label) {
                    $imageField = $field;
                    $files[] = CatalogImageUpload::save($_FILES[$field], CatalogImageUpload::PRODUCT);
                }
                $imageField = null;
                $conn->execute_query(
                    "INSERT INTO T_Product (BrandId, CategoryId, SubCategoryId, Name, Price, Colour, Details,
                        Image1Path, Image2Path, Image3Path, ProductStatusId)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, (SELECT Id FROM E_ProductStatus WHERE Code = 'AVAILABLE'))",
                    [$fields['brand_id'], $fields['category_id'], $fields['subcategory_id'], $fields['prod_name'],
                        $fields['prod_price'], $fields['prod_colour'], $fields['prod_details'], ...$files]
                );
                $productId = (string) $conn->insert_id;
                $idToken = EncryptionManager::encrypt($productId, EncryptionManager::PRODUCT_DETAILS);
                $insertSize = $conn->prepare(
                    'INSERT INTO T_ProductSizeAndCategory (ProductId, SizeId, Qty, IsAvailable) VALUES (?, ?, ?, ?)'
                );
                foreach ($inventory as $sizeId => $qty) {
                    $insertSize->execute([$productId, $sizeId, $qty, (int) ($qty > 0)]);
                }
                $conn->commit();
                $savedUrl = 'admin_productdetails.php?id=' . $idToken;
                $_SESSION['catalog_saved_product_ids'][$draftId] = $productId;
                $_SESSION['catalog_saved_product_ids'] = array_slice($_SESSION['catalog_saved_product_ids'], -12, null, true);
                $_SESSION['catalog_product_notice'][$productId] = 'Product added.';
            } catch (Throwable $exception) {
                $conn->rollback();
                foreach ($files as $file) CatalogImageUpload::remove($file);
                if ($imageField !== null && $exception instanceof DomainException) {
                    $errors[$imageField] = $exception->getMessage();
                } elseif ($exception->getCode() === 1062) {
                    $errors['prod_name'] = 'A product with this name already exists.';
                } elseif ($exception->getCode() === 1452) {
                    $errors['form'] = 'A lookup changed while saving. Verify your selections and try again.';
                } else {
                    error_log('SneakerPeak product creation failed: ' . $exception->getCode());
                    $errors['form'] = 'The product could not be saved. Try again.';
                }
            }
        }
    }

    if ($json) {
        if ($errors) http_response_code(422);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['location' => $savedUrl, 'errors' => $errors]);
        exit;
    }
    if ($savedUrl !== null) {
        header('Location: ' . $savedUrl);
        exit;
    }
    http_response_code(422);
}

require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_addproduct.php';
