<?php
$fieldError = static function (string $field) use ($errors): void {
    $error = $errors[$field] ?? '';
    ?>
    <span class="admin-field-error" data-draft-error="<?= htmlspecialchars($field) ?>" <?= $error === '' ? 'hidden' : '' ?>><?= htmlspecialchars($error) ?></span>
    <?php
};
$renderSize = static function (string $index, array $row) use ($sizes, $fieldError): void { ?>
    <div class="admin-size-row" data-size-row>
        <label>Size
            <select name="size_id[<?= $index ?>]" required>
                <option value="">Choose size</option>
                <?php foreach ($sizes as $size) { ?>
                    <option value="<?= $size['Id'] ?>" <?= $row['size_id'] === (string) $size['Id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($size['SizeLabel'] . ($size['SizingSystem'] === 'UNSPECIFIED' ? '' : ' (' . $size['SizingSystem'] . ')')) ?>
                    </option>
                <?php } ?>
            </select>
            <?php $fieldError("size_id[$index]"); ?>
        </label>
        <label>Quantity
            <input type="number" name="qty[<?= $index ?>]" min="0" max="4294967295" step="1" value="<?= htmlspecialchars($row['qty']) ?>" required>
            <?php $fieldError("qty[$index]"); ?>
        </label>
        <button type="button" class="btn-light" data-remove-size>Remove</button>
    </div>
<?php };
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product | Sneaker Peak</title>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
    <script src="assets/js/admin-product-draft.js" defer></script>
</head>
<body class="admin-workspace">
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
<main class="home-section">
    <header class="home-content"><h1>Add product</h1></header>
    <form method="post" action="admin_addproduct.php" enctype="multipart/form-data" data-product-draft data-size-limit="<?= count($sizes) ?>">
        <input type="hidden" name="catalog_token" value="<?= htmlspecialchars($catalogToken) ?>">
        <input type="hidden" name="draft_id" value="<?= htmlspecialchars($draftId) ?>">
        <p class="admin-catalog-notice admin-field-error" role="alert" tabindex="-1" data-draft-summary <?= $errors ? '' : 'hidden' ?>><?= htmlspecialchars($errors['form'] ?? 'Review the fields marked below and save again.') ?></p>
        <ol class="admin-draft-progress" data-draft-progress aria-label="Product creation progress" hidden>
            <li>1. Product details</li><li>2. Images</li><li>3. Size inventory</li>
        </ol>
        <section class="table" id="product-details" data-draft-step>
            <header class="table__header"><h2 tabindex="-1">Product details</h2></header>
            <div class="admin-catalog-fields">
                <label>Product name
                    <input type="text" name="prod_name" maxlength="255" value="<?= htmlspecialchars($fields['prod_name']) ?>" required>
                    <?php $fieldError('prod_name'); ?>
                </label>
                <label>Price (RM)
                    <input type="text" name="prod_price" inputmode="decimal" pattern="(?:[0-9]{1,11}(?:\.[0-9]{0,2})?|\.[0-9]{1,2})" title="Enter a nonnegative price with up to two decimal places." value="<?= htmlspecialchars($fields['prod_price']) ?>" required>
                    <?php $fieldError('prod_price'); ?>
                </label>
                <label>Colour
                    <input type="text" name="prod_colour" maxlength="255" value="<?= htmlspecialchars($fields['prod_colour']) ?>" required>
                    <?php $fieldError('prod_colour'); ?>
                </label>
                <?php foreach (['brand_id' => 'Brand', 'category_id' => 'Category', 'subcategory_id' => 'Subcategory'] as $field => $label) { ?>
                    <label><?= $label ?>
                        <select name="<?= $field ?>" required>
                            <option value="">Choose <?= strtolower($label) ?></option>
                            <?php foreach ($choices[$field] as $choice) { ?>
                                <option value="<?= $choice['Id'] ?>" <?= $fields[$field] === (string) $choice['Id'] ? 'selected' : '' ?>><?= htmlspecialchars($choice['Name']) ?></option>
                            <?php } ?>
                        </select>
                        <?php $fieldError($field); ?>
                    </label>
                <?php } ?>
                <label class="admin-full-field">Description
                    <textarea name="prod_details" required><?= htmlspecialchars($fields['prod_details']) ?></textarea>
                    <?php $fieldError('prod_details'); ?>
                </label>
            </div>
        </section>
        <section class="table" id="product-images" data-draft-step>
            <header class="table__header"><h2 tabindex="-1">Images</h2></header>
            <p id="product-image-help">JPEG, PNG, WebP or GIF. Up to 2 MB per image.</p>
            <div class="admin-catalog-fields">
                <?php foreach ($imageFields as $field => $label) { ?>
                    <label><?= $label ?>
                        <input type="file" name="<?= $field ?>" accept="image/jpeg,image/png,image/webp,image/gif" aria-describedby="product-image-help" required>
                        <?php $fieldError($field); ?>
                    </label>
                <?php } ?>
            </div>
        </section>
        <section class="table" id="product-inventory" data-draft-step>
            <header class="table__header"><h2 tabindex="-1">Size inventory</h2></header>
            <?php $fieldError('inventory'); ?>
            <div data-size-rows>
                <?php foreach ($sizeRows as $index => $row) { $renderSize((string) $index, $row); } ?>
            </div>
            <button type="button" class="btn-light" data-add-size hidden>Add size row</button>
            <template data-size-template><?php $renderSize('INDEX', ['size_id' => '', 'qty' => '']); ?></template>
        </section>
        <div class="admin-actions">
            <button type="button" class="btn-light" data-draft-back hidden>Back</button>
            <button type="button" data-draft-next hidden>Next</button>
            <button type="submit" data-draft-save>Save product</button>
            <a class="admin-button secondary" href="admin_productlist.php">Cancel</a>
        </div>
    </form>
</main>
</body>
</html>
