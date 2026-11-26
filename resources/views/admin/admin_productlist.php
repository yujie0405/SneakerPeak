<?php use SneakerPeak\Manager\CatalogImageUpload; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products | Sneaker Peak</title>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
    <script src="assets/js/admin-catalog.js" defer></script>
</head>
<body class="admin-workspace">
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
<main class="home-section">
    <header class="home-content"><h1>Products</h1></header>
    <section class="table">
        <div class="admin-catalog-toolbar">
            <form class="admin-catalog-filters" action="admin_productlist.php" method="get" aria-label="Product filters">
                <label>Product name
                    <input type="search" name="search" value="<?= htmlspecialchars($filters['search']) ?>">
                </label>
                <label>Brand
                    <select name="brand">
                        <option value="">All brands</option>
                        <?php foreach ($brands as $brand) { $name = $brand['Name']; ?>
                            <option value="<?= htmlspecialchars($name) ?>" <?= $filters['brand'] === $name ? 'selected' : '' ?>><?= htmlspecialchars($name) ?></option>
                        <?php } ?>
                    </select>
                </label>
                <label>Status
                    <select name="status">
                        <option value="">All statuses</option>
                        <?php foreach (['available' => 'Available', 'out-of-stock' => 'Out of stock', 'deleted' => 'Deleted'] as $value => $label) { ?>
                            <option value="<?= $value ?>" <?= $filters['status'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                        <?php } ?>
                    </select>
                </label>
                <?php foreach (['category' => $categories, 'subcategory' => $subcategories] as $field => $choices) { ?>
                    <label><?= ucfirst($field) ?>
                        <select name="<?= $field ?>"<?= isset($errors[$field]) ? ' aria-invalid="true" aria-describedby="' . $field . '-error"' : '' ?>>
                            <option value="">All</option>
                            <?php foreach ($choices as $choice) { ?>
                                <option value="<?= $choice['Id'] ?>" <?= $filters[$field] === (string) $choice['Id'] ? 'selected' : '' ?>><?= htmlspecialchars($choice['Name']) ?></option>
                            <?php } ?>
                        </select>
                        <?php if (isset($errors[$field])) { ?>
                            <span class="admin-field-error" id="<?= $field ?>-error" role="alert"><?= htmlspecialchars($errors[$field]) ?></span>
                        <?php } ?>
                    </label>
                <?php } ?>
                <?php foreach (['min_price' => 'Min price (RM)', 'max_price' => 'Max price (RM)'] as $field => $label) { ?>
                    <label><?= $label ?>
                        <input type="text" inputmode="decimal" name="<?= $field ?>" value="<?= htmlspecialchars($filters[$field]) ?>"<?= isset($errors[$field]) ? ' aria-invalid="true" aria-describedby="' . $field . '-error"' : '' ?>>
                        <?php if (isset($errors[$field])) { ?>
                            <span class="admin-field-error" id="<?= $field ?>-error" role="alert"><?= htmlspecialchars($errors[$field]) ?></span>
                        <?php } ?>
                    </label>
                <?php } ?>
                <div class="admin-actions">
                    <button type="submit">Search</button>
                    <a class="admin-button secondary" href="admin_productlist.php">Clear filters</a>
                </div>
            </form>
            <a class="admin-button primary" href="admin_addproduct.php">Add product</a>
        </div>
        <?php if (!$errors) { ?>
            <p><?= $total ?> <?= $total === 1 ? 'product' : 'products' ?> found</p>
            <?php if (!$products) { ?>
                <p>No products match your filters.</p>
            <?php } else { ?>
                <div class="admin-table-scroll" tabindex="0" role="region" aria-label="Product results">
                    <table>
                        <thead><tr>
                            <th scope="col">Product</th><th scope="col">Brand</th><th scope="col">Price</th>
                            <th scope="col">Colour</th><th scope="col">Category</th><th scope="col">Subcategory</th>
                            <th scope="col">Status</th><th scope="col">Actions</th>
                        </tr></thead>
                        <tbody>
                        <?php foreach ($products as $row) { ?>
                            <tr data-product-id="<?= $row['Prod_ID'] ?>">
                                <td><div class="admin-product-cell">
                                    <?php if ($row['Prod_img1']) { ?><img src="<?= htmlspecialchars(CatalogImageUpload::url($row['Prod_img1'])) ?>" alt="" loading="lazy"><?php } ?>
                                    <?= htmlspecialchars($row['Prod_Name']) ?>
                                </div></td>
                                <td><?= htmlspecialchars($row['Prod_Brand']) ?></td>
                                <td>RM <?= number_format((float) $row['Prod_Price'], 2) ?></td>
                                <td><?= htmlspecialchars($row['Prod_Colour'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['Prod_Cetegory']) ?></td>
                                <td><?= htmlspecialchars($row['Prod_SubC']) ?></td>
                                <td><?= htmlspecialchars(ucfirst(str_replace('-', ' ', $row['Prod_Status']))) ?></td>
                                <td class="admin-row-actions">
                                    <a class="admin-button secondary" href="admin_productdetails.php?prodName=<?= rawurlencode($row['Prod_Name']) ?>">View</a>
                                    <?php if ($row['Prod_Status'] !== 'Deleted') { ?>
                                        <form method="post" action="delete_item.php" data-product-action="Delete this product?" class="admin-inline-form">
                                            <input type="hidden" name="catalog_token" value="<?= htmlspecialchars($catalogToken) ?>">
                                            <input type="hidden" name="id" value="<?= $row['Prod_ID'] ?>">
                                            <button class="delete" type="submit">Delete</button>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
            <?php if ($pageCount > 1) { ?>
                <nav class="admin-pagination" aria-label="Product results pages">
                    <?php if ($page > 1) { ?><a rel="prev" href="<?= htmlspecialchars($pageUrl($page - 1)) ?>">Previous</a><?php } ?>
                    <?php for ($number = 1; $number <= $pageCount; $number++) { ?>
                        <?php if ($number === $page) { ?>
                            <span aria-current="page" aria-label="Page <?= $number ?>"><?= $number ?></span>
                        <?php } else { ?>
                            <a aria-label="Page <?= $number ?>" href="<?= htmlspecialchars($pageUrl($number)) ?>"><?= $number ?></a>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($page < $pageCount) { ?><a rel="next" href="<?= htmlspecialchars($pageUrl($page + 1)) ?>">Next</a><?php } ?>
                </nav>
            <?php } ?>
        <?php } ?>
    </section>
</main>
</body>
</html>
