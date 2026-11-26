<?php
use SneakerPeak\Manager\CatalogImageUpload;

$sizeLabel = static fn(array $size): string => $size['SizeLabel']
    . ($size['SizingSystem'] === 'UNSPECIFIED' ? '' : ' (' . $size['SizingSystem'] . ')');
$images = array_values(array_filter(array_map(
    static fn(string $field): string => CatalogImageUpload::url($row[$field]),
    ['Prod_img1', 'Prod_img2', 'Prod_img3']
)));
$token = htmlspecialchars($catalogToken);
$productAction = htmlspecialchars($productUrl);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Details | Sneaker Peak</title>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
    <script src="assets/js/admin-catalog.js" defer></script>
    <script src="assets/js/admin-product-inventory.js" defer></script>
</head>
<body class="admin-workspace">
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
<main class="home-section">
    <header class="home-content"><h1>Product details</h1></header>
    <?php if ($notice !== null) { ?><p class="admin-catalog-notice" role="status"><?= htmlspecialchars($notice) ?></p><?php } ?>
    <?php if ($error !== null) { ?><p class="admin-catalog-notice admin-field-error" role="alert"><?= htmlspecialchars($error) ?></p><?php } ?>
    <section class="table admin-product-detail">
        <?php if ($images) { ?>
            <div class="admin-product-photo">
                <img data-product-main-image src="<?= htmlspecialchars($images[0]) ?>" alt="<?= htmlspecialchars($row['Prod_Name']) ?>">
                <?php if (count($images) > 1) { ?>
                    <div class="admin-actions">
                        <?php foreach ($images as $index => $image) { ?>
                            <button class="btn-light" type="button" data-product-image="<?= htmlspecialchars($image) ?>"
                                    aria-label="View image <?= $index + 1 ?>" aria-pressed="<?= $index === 0 ? 'true' : 'false' ?>">
                                <img src="<?= htmlspecialchars($image) ?>" alt="">
                            </button>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <div>
            <h2><?= htmlspecialchars($row['Prod_Name']) ?></h2>
            <p>RM <?= number_format((float) $row['Prod_Price'], 2) ?></p>
            <p><?= htmlspecialchars($row['Prod_Brand'] . ' / ' . $row['Prod_Cetegory'] . ' / ' . $row['Prod_SubC']) ?></p>
            <p><?= nl2br(htmlspecialchars($row['Prod_Details'])) ?></p>
            <p><strong>Product status</strong> <?= htmlspecialchars(ucfirst(str_replace('-', ' ', $row['Prod_Status']))) ?></p>
            <div class="admin-actions">
                <button type="button" data-open-catalog-dialog="product-editor">Edit product</button>
                <?php if ($row['Prod_Status'] !== 'Deleted') { ?>
                    <form method="post" action="delete_item.php" class="admin-inline-form" data-product-action="Delete this product?">
                        <input type="hidden" name="catalog_token" value="<?= $token ?>">
                        <input type="hidden" name="id" value="<?= $productId ?>">
                        <button class="delete" type="submit">Delete</button>
                    </form>
                <?php } ?>
                <?php if ($row['Prod_Status'] !== 'available') { ?>
                    <form method="post" action="Available_item.php" class="admin-inline-form" data-product-action="Make this product available?">
                        <input type="hidden" name="catalog_token" value="<?= $token ?>">
                        <input type="hidden" name="id" value="<?= $productId ?>">
                        <button type="submit">Make available</button>
                    </form>
                <?php } ?>
                <a class="admin-button secondary" href="admin_productlist.php">Back to products</a>
            </div>
        </div>
    </section>

    <section class="table">
        <header class="table__header">
            <h2>Size inventory</h2>
            <?php if ($newSizes) { ?><button type="button" data-open-catalog-dialog="size-add-editor">Add size</button><?php } ?>
        </header>
        <?php if (!$sizes) { ?>
            <p>No sizes have been added.</p>
        <?php } else { ?>
            <div class="admin-table-scroll" tabindex="0" role="region" aria-label="Size inventory">
                <table>
                    <thead><tr><th scope="col">Size</th><th scope="col">Quantity</th><th scope="col">Status</th><th scope="col">Actions</th></tr></thead>
                    <tbody>
                    <?php foreach ($sizes as $size) {
                        $rowId = (string) $size['Id'];
                        $currentStatus = $size['IsAvailable'] ? 'Available' : 'Unavailable';
                        ?>
                        <tr id="inventory-<?= $rowId ?>" data-inventory-id="<?= $rowId ?>" data-size="<?= htmlspecialchars($sizeLabel($size)) ?>"
                            data-quantity="<?= $size['Qty'] ?>" data-availability="<?= (int) $size['IsAvailable'] ?>"
                            <?= (string) $highlightRow === $rowId ? 'class="admin-updated-row"' : '' ?>>
                            <th scope="row"><?= htmlspecialchars($sizeLabel($size)) ?></th>
                            <td><?= $size['Qty'] ?></td>
                            <td>
                                <span class="admin-size-status <?= $size['IsAvailable'] ? 'available' : 'unavailable' ?>"><?= $currentStatus ?></span>
                                <?php if ((int) $size['Qty'] === 0) { ?><small>No stock</small><?php } ?>
                            </td>
                            <td class="admin-row-actions">
                                <button type="button" data-open-catalog-dialog="size-status-editor">Update status</button>
                                <button type="button" class="btn-light" data-open-catalog-dialog="size-quantity-editor">Adjust quantity</button>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </section>

    <dialog class="admin-catalog-dialog" id="product-editor" aria-labelledby="product-editor-label" <?= $error !== null && $action === 'product' ? 'data-reopen' : '' ?>>
        <h2 id="product-editor-label">Edit product</h2>
        <?php if ($error !== null && $action === 'product') { ?><p class="admin-field-error" role="alert"><?= htmlspecialchars($error) ?></p><?php } ?>
        <form method="post" action="<?= $productAction ?>">
            <input type="hidden" name="catalog_token" value="<?= $token ?>">
            <input type="hidden" name="action" value="product">
            <div class="admin-catalog-fields">
                <label>Product name <input type="text" name="productName" value="<?= htmlspecialchars($edit['productName']) ?>" maxlength="255" required></label>
                <label>Price (RM) <input type="text" name="productPrice" inputmode="decimal" value="<?= htmlspecialchars($edit['productPrice']) ?>" required></label>
                <?php foreach (['brand_id' => 'Brand', 'category_id' => 'Category', 'subcategory_id' => 'Subcategory'] as $field => $label) { ?>
                    <label><?= $label ?>
                        <select name="<?= $field ?>" required>
                            <?php foreach ($lookups[$field] as $choice) { ?>
                                <option value="<?= $choice['Id'] ?>" <?= $edit[$field] === (string) $choice['Id'] ? 'selected' : '' ?>><?= htmlspecialchars($choice['Name']) ?></option>
                            <?php } ?>
                        </select>
                    </label>
                <?php } ?>
                <label class="admin-full-field">Description <textarea name="productDetails" required><?= htmlspecialchars($edit['productDetails']) ?></textarea></label>
            </div>
            <div class="admin-actions"><button type="submit">Save product</button><button type="button" class="btn-light" data-close-catalog-dialog>Cancel</button></div>
        </form>
    </dialog>

    <?php foreach (['status' => 'Update size status', 'quantity' => 'Adjust size quantity'] as $mode => $title) {
        $reopen = $error !== null && $action === $mode && $inventoryId !== null;
        ?>
        <dialog class="admin-catalog-dialog" id="size-<?= $mode ?>-editor" aria-labelledby="size-<?= $mode ?>-label"
                <?= $reopen ? 'data-reopen data-inventory-id="' . htmlspecialchars($inventoryId) . '"' : '' ?>>
            <h2 id="size-<?= $mode ?>-label"><?= $title ?></h2>
            <?php if ($reopen) { ?><p data-dialog-error class="admin-field-error" role="alert"><?= htmlspecialchars($error) ?></p><?php } ?>
            <dl class="admin-inventory-summary">
                <dt>Product</dt><dd><?= htmlspecialchars($row['Prod_Name']) ?></dd>
                <dt>Size</dt><dd data-dialog-size></dd>
                <dt>Current status</dt><dd data-dialog-status></dd>
                <dt>Current quantity</dt><dd data-dialog-quantity></dd>
            </dl>
            <form method="post" action="<?= $productAction ?>">
                <input type="hidden" name="catalog_token" value="<?= $token ?>">
                <input type="hidden" name="action" value="<?= $mode ?>">
                <input type="hidden" name="inventory_id">
                <?php if ($mode === 'status') { ?>
                    <label>New status
                        <select name="availability" required data-posted="<?= htmlspecialchars($reopen ? $submittedStatus : '') ?>">
                            <option value="1">Available</option><option value="0">Unavailable</option>
                        </select>
                    </label>
                    <p data-zero-stock-note hidden>Restore the quantity before marking this size available.</p>
                <?php } else { ?>
                    <label>New quantity
                        <input type="number" name="quantity" min="0" max="4294967295" step="1" required data-posted="<?= htmlspecialchars($reopen ? $submittedQuantity : '') ?>">
                    </label>
                <?php } ?>
                <div class="admin-actions"><button type="submit"><?= $mode === 'status' ? 'Confirm status' : 'Save quantity' ?></button><button type="button" class="btn-light" data-close-catalog-dialog>Cancel</button></div>
            </form>
        </dialog>
    <?php } ?>

    <?php if ($newSizes) { ?>
        <dialog class="admin-catalog-dialog" id="size-add-editor" aria-labelledby="size-add-label" <?= $error !== null && $action === 'add-size' ? 'data-reopen' : '' ?>>
            <h2 id="size-add-label">Add size</h2>
            <?php if ($error !== null && $action === 'add-size') { ?><p class="admin-field-error" role="alert"><?= htmlspecialchars($error) ?></p><?php } ?>
            <form method="post" action="<?= $productAction ?>">
                <input type="hidden" name="catalog_token" value="<?= $token ?>">
                <input type="hidden" name="action" value="add-size">
                <label>Size
                    <select name="size_id" required>
                        <option value="">Choose size</option>
                        <?php foreach ($newSizes as $size) { ?>
                            <option value="<?= $size['Id'] ?>" <?= $action === 'add-size' && ($_POST['size_id'] ?? null) === (string) $size['Id'] ? 'selected' : '' ?>><?= htmlspecialchars($sizeLabel($size)) ?></option>
                        <?php } ?>
                    </select>
                </label>
                <label>Quantity <input type="number" name="quantity" min="0" max="4294967295" step="1" value="<?= htmlspecialchars($action === 'add-size' ? $submittedQuantity : '') ?>" required></label>
                <div class="admin-actions"><button type="submit">Save size</button><button type="button" class="btn-light" data-close-catalog-dialog>Cancel</button></div>
            </form>
        </dialog>
    <?php } ?>
</main>
</body>
</html>
