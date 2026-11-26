<?php

require SNEAKERPEAK_ROOT . '/database/config.php';

$pn = (string) ($_GET['prodName'] ?? $_SESSION['prodName'] ?? '');
$find = $conn->prepare('SELECT * FROM product WHERE Prod_Name = ? LIMIT 1');
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
            $duplicate = $conn->prepare('SELECT Prod_ID FROM product WHERE Prod_Name = ? AND Prod_ID <> ? LIMIT 1');
            $duplicate->execute([$name, $row['Prod_ID']]);
            if ($duplicate->get_result()->fetch_row()) {
                throw new DomainException('A product already uses this name.');
            }
            $update = $conn->prepare('UPDATE product SET Prod_Name = ?, Prod_Price = ?, Prod_Details = ? WHERE Prod_ID = ?');
            $update->execute([$name, $price, $details, $row['Prod_ID']]);
            if ($name !== $pn) {
                $inventory = $conn->prepare('UPDATE sizeandqty SET Prod_Name = ? WHERE Prod_Name = ?');
                $inventory->execute([$name, $pn]);
                $cart = $conn->prepare('UPDATE cart SET Product_Name = ? WHERE Product_Name = ?');
                $cart->execute([$name, $pn]);
                $wishlist = $conn->prepare('UPDATE wishlist SET prod_name = ? WHERE prod_name = ?');
                $wishlist->execute([$name, $pn]);
            }
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
    "SELECT *, CASE WHEN CAST(Prod_Qty AS SIGNED) = 0 THEN 'UnAvailable' ELSE Qty_status END AS Display_status
     FROM sizeandqty WHERE Prod_Name = ? ORDER BY CAST(Prod_Size AS DECIMAL(10, 2))"
);
$inventory->execute([$pn]);
$sizes = $inventory->get_result()->fetch_all(MYSQLI_ASSOC);
require SNEAKERPEAK_ROOT . '/resources/views/admin/admin_productdetails.php';