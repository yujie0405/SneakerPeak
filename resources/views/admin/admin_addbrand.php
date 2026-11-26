<?php use SneakerPeak\Manager\CatalogImageUpload; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Brands and Categories | Sneaker Peak</title>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
<body class="admin-workspace">
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
<main class="home-section">
    <header class="home-content"><h1>Brands and categories</h1></header>
    <?php if ($notice !== null) { ?><p class="admin-catalog-notice" role="status"><?= htmlspecialchars($notice) ?></p><?php } ?>
    <?php if ($error !== null) { ?><p class="admin-catalog-notice admin-field-error" role="alert"><?= htmlspecialchars($error) ?></p><?php } ?>
    <?php foreach ($lookups as $type => $lookup) { ?>
        <section class="table" id="<?= $type ?>">
            <header class="table__header">
                <h2><?= $lookup['heading'] ?></h2>
                <a class="admin-button primary" href="?type=<?= $type ?>#<?= $type ?>-editor">Add <?= strtolower($lookup['label']) ?></a>
            </header>
            <?php if ($editing === $type) { ?>
                <form id="<?= $type ?>-editor" class="admin-lookup-form" method="post" action="admin_addbrand.php#<?= $type ?>-editor" enctype="multipart/form-data">
                    <h3><?= $editId === null ? 'Add' : 'Edit' ?> <?= strtolower($lookup['label']) ?></h3>
                    <input type="hidden" name="catalog_token" value="<?= htmlspecialchars($catalogToken) ?>">
                    <input type="hidden" name="type" value="<?= $type ?>">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($editId ?? '') ?>">
                    <input type="hidden" name="action" value="<?= $editId === null ? 'add' : 'edit' ?>">
                    <div class="admin-catalog-fields">
                        <label>Name
                            <input type="text" name="name" maxlength="255" value="<?= htmlspecialchars($editName ?? '') ?>" required>
                        </label>
                        <?php if ($type === 'brand') { ?>
                            <label><?= $editId === null ? 'Logo (optional)' : 'Replace logo (optional)' ?>
                                <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/gif">
                            </label>
                        <?php } ?>
                    </div>
                    <div class="admin-actions">
                        <button type="submit">Save</button>
                        <a class="admin-button secondary" href="admin_addbrand.php#<?= $type ?>">Cancel</a>
                    </div>
                </form>
            <?php } ?>
            <?php if (!$lookup['rows']) { ?>
                <p>No <?= strtolower($lookup['heading']) ?> yet.</p>
            <?php } else { ?>
                <div class="admin-table-scroll" tabindex="0" role="region" aria-label="<?= $lookup['heading'] ?>">
                    <table>
                        <thead><tr><th scope="col">Name</th><th scope="col">Products</th><th scope="col">Actions</th></tr></thead>
                        <tbody>
                        <?php foreach ($lookup['rows'] as $item) { ?>
                            <tr>
                                <td><div class="admin-product-cell">
                                    <?php if (!empty($item['LogoPath'])) { ?>
                                        <img src="<?= htmlspecialchars(CatalogImageUpload::url($item['LogoPath'], CatalogImageUpload::LOGO)) ?>" alt="">
                                    <?php } ?>
                                    <?= htmlspecialchars($item['Name']) ?>
                                </div></td>
                                <td>
                                    <?php if ($item['Products'] > 0) {
                                        $filter = $type === 'brand' ? $item['Name'] : $item['Id'];
                                        ?>
                                        <a href="admin_productlist.php?<?= htmlspecialchars(http_build_query([$type => $filter])) ?>"><?= (int) $item['Products'] ?></a>
                                    <?php } else { ?>
                                        0
                                    <?php } ?>
                                </td>
                                <td class="admin-row-actions">
                                    <a class="admin-button secondary" href="?type=<?= $type ?>&amp;id=<?= $item['Id'] ?>#<?= $type ?>-editor">Edit</a>
                                    <form class="admin-inline-form" action="admin_addbrand.php" method="post" onsubmit="return confirm('Delete this <?= strtolower($lookup['label']) ?>?');">
                                        <input type="hidden" name="catalog_token" value="<?= htmlspecialchars($catalogToken) ?>">
                                        <input type="hidden" name="type" value="<?= $type ?>">
                                        <input type="hidden" name="id" value="<?= $item['Id'] ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button class="delete" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </section>
    <?php } ?>
</main>
</body>
</html>
