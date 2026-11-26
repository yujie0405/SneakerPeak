<?php

use SneakerPeak\Manager\CatalogImageUpload;
use SneakerPeak\App\Services\CatalogInput;

require SNEAKERPEAK_ROOT . '/database/config.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Services/CatalogInput.php';

$lookups = [
    'brand' => ['table' => 'E_Brand', 'key' => 'BrandId', 'label' => 'Brand', 'heading' => 'Brands'],
    'category' => ['table' => 'E_Category', 'key' => 'CategoryId', 'label' => 'Category', 'heading' => 'Categories'],
    'subcategory' => ['table' => 'E_SubCategory', 'key' => 'SubCategoryId', 'label' => 'Subcategory', 'heading' => 'Subcategories'],
];
$catalogToken = $_SESSION['catalog_admin_token'] ??= bin2hex(random_bytes(32));
$notice = $_SESSION['catalog_lookup_notice'] ?? null;
unset($_SESSION['catalog_lookup_notice']);
$error = null;
$editing = CatalogInput::text($_GET, 'type');
$editId = CatalogInput::id($_GET['id'] ?? null);
$editName = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($catalogToken, CatalogInput::text($_POST, 'catalog_token'))) {
        http_response_code(403);
        exit('Please reload the page and try again.');
    }

    $editing = CatalogInput::text($_POST, 'type');
    $action = CatalogInput::text($_POST, 'action');
    $editId = CatalogInput::id($_POST['id'] ?? null);
    $editName = CatalogInput::text($_POST, 'name');
    if (!isset($lookups[$editing]) || !in_array($action, ['add', 'edit', 'delete'], true)
        || ($action !== 'add' && $editId === null)) {
        http_response_code(400);
        exit('Choose a lookup and action.');
    }

    ['table' => $table, 'key' => $key, 'label' => $label] = $lookups[$editing];
    $referenceMessage = "This " . strtolower($label)
        . ' is in use. Find its products in the product list, edit them to select a different '
        . strtolower($label) . ', then try deleting again.';
    $logo = null;
    $previousLogo = null;
    $conn->begin_transaction();
    try {
        if ($action !== 'add') {
            $select = $editing === 'brand' ? 'Name, LogoPath' : 'Name';
            $existing = $conn->execute_query("SELECT $select FROM $table WHERE Id = ? FOR UPDATE", [$editId])->fetch_assoc();
            if (!$existing) throw new DomainException("$label no longer exists.");
            $previousLogo = $existing['LogoPath'] ?? null;
        }

        if ($action === 'delete') {
            if ($conn->execute_query("SELECT 1 FROM T_Product WHERE $key = ? LIMIT 1", [$editId])->fetch_row()) {
                throw new DomainException($referenceMessage);
            }
            $conn->execute_query("DELETE FROM $table WHERE Id = ?", [$editId]);
        } else {
            if ($editName === '' || mb_strlen($editName) > 255) {
                throw new DomainException('Enter a name of up to 255 characters.');
            }
            if ($editing === 'brand' && ($_FILES['logo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $logo = CatalogImageUpload::save($_FILES['logo'], CatalogImageUpload::LOGO);
            }
            if ($action === 'add') {
                if ($editing === 'brand') {
                    $conn->execute_query('INSERT INTO E_Brand (Name, LogoPath) VALUES (?, ?)', [$editName, $logo]);
                } else {
                    $conn->execute_query("INSERT INTO $table (Name) VALUES (?)", [$editName]);
                }
            } elseif ($logo !== null) {
                $conn->execute_query('UPDATE E_Brand SET Name = ?, LogoPath = ? WHERE Id = ?', [$editName, $logo, $editId]);
            } else {
                $conn->execute_query("UPDATE $table SET Name = ? WHERE Id = ?", [$editName, $editId]);
            }
        }

        $conn->commit();
        if ($previousLogo !== null && ($action === 'delete' || $logo !== null)) {
            CatalogImageUpload::remove($previousLogo);
        }
        $verb = ['add' => 'added', 'edit' => 'updated', 'delete' => 'deleted'][$action];
        $_SESSION['catalog_lookup_notice'] = "$label $verb.";
        header('Location: admin_addbrand.php#' . $editing);
        exit;
    } catch (Throwable $exception) {
        $conn->rollback();
        if ($logo !== null) CatalogImageUpload::remove($logo);
        $error = match (true) {
            $exception instanceof DomainException => $exception->getMessage(),
            $exception->getCode() === 1062 => "A " . strtolower($label) . ' with this name already exists.',
            $exception->getCode() === 1451 => $referenceMessage,
            default => "The " . strtolower($label) . ' could not be saved.',
        };
        if ($action === 'delete') {
            $editing = '';
            $editId = null;
        }
    }
}

foreach ($lookups as &$lookup) {
    $logoColumn = $lookup['key'] === 'BrandId' ? ', l.LogoPath' : '';
    $table = $lookup['table'];
    $key = $lookup['key'];
    $lookup['rows'] = $conn->query(
        "SELECT l.Id, l.Name $logoColumn,
            (SELECT COUNT(*) FROM T_Product p WHERE p.$key = l.Id) AS Products
         FROM $table l ORDER BY l.Name, l.Id"
    )->fetch_all(MYSQLI_ASSOC);
}
unset($lookup);
if (isset($lookups[$editing]) && $editId !== null) {
    $current = null;
    foreach ($lookups[$editing]['rows'] as $item) {
        if ((string) $item['Id'] === $editId) {
            $current = $item;
            break;
        }
    }
    if ($current === null) {
        $error ??= 'That lookup no longer exists.';
        $editing = '';
    } else {
        $editName ??= $current['Name'];
    }
}
require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_addbrand.php';
