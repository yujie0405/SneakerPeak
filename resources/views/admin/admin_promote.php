<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Promotions | Sneaker Peak</title>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
<body class="admin-workspace">
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
<main class="home-section">
    <header class="home-content"><h1>Promotions</h1></header>
    <section class="table">
        <header class="table__header"><h2>Add promotion</h2></header>
        <?php if ($notice !== null) { ?><p role="status"><?= htmlspecialchars($notice) ?></p><?php } ?>
        <?php if ($error !== null) { ?><p class="admin-field-error" role="alert"><?= htmlspecialchars($error) ?></p><?php } ?>
        <form method="post" action="<?= htmlspecialchars($listUrl) ?>">
            <input type="hidden" name="promotion_token" value="<?= htmlspecialchars($token) ?>">
            <div class="admin-catalog-fields">
                <div>
                    <label>Promotion code <input type="text" id="promotion-code" name="Promote_Code" maxlength="255" value="<?= htmlspecialchars($fields['Promote_Code']) ?>" required></label>
                    <button class="btn-light" type="button" onclick="document.getElementById('promotion-code').value = String(Math.floor(100000 + Math.random() * 900000))">Generate code</button>
                </div>
                <label>Value <input type="text" name="Promote_CodeValue" inputmode="decimal" value="<?= htmlspecialchars($fields['Promote_CodeValue']) ?>" required></label>
                <label>Expiry date <input type="date" name="ExpireDate" value="<?= htmlspecialchars($fields['ExpireDate']) ?>" required></label>
                <label>Promotion type
                    <select name="promotetype" required>
                        <?php foreach ($promotionTypes as $value => $label) { ?>
                            <option value="<?= $value ?>"<?= $fields['promotetype'] === $value ? ' selected' : '' ?>><?= $label ?></option>
                        <?php } ?>
                    </select>
                </label>
                <label>Description <input type="text" name="CodeDetails" maxlength="255" value="<?= htmlspecialchars($fields['CodeDetails']) ?>" required></label>
            </div>
            <div class="admin-actions admin-form-actions"><button type="submit" name="submit">Add promotion</button></div>
        </form>
    </section>
    <section class="table">
        <header class="table__header"><h2>Promotion list</h2></header>
        <form class="admin-catalog-filters admin-list-filters" method="get" action="admin_promote.php" aria-label="Promotion filters">
            <label>Search promotion code <input type="search" name="search" value="<?= htmlspecialchars($filters['search']) ?>"></label>
            <label>Expiry from <input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from']) ?>"></label>
            <label>Expiry to <input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to']) ?>"></label>
            <div class="admin-actions">
                <button type="submit">Apply filters</button>
                <a class="admin-button secondary" href="admin_promote.php">Clear filters</a>
            </div>
        </form>
        <?php if ($filterError !== null) { ?>
            <p class="admin-field-error" role="alert"><?= htmlspecialchars($filterError) ?></p>
        <?php } elseif (!$promotions) { ?>
            <p>No promotions match your filters.</p>
        <?php } else { ?>
            <div class="admin-table-scroll" tabindex="0" role="region" aria-label="Promotion results">
                <table>
                    <thead><tr>
                        <th scope="col">Code</th><th scope="col">Value</th><th scope="col">Expiry date</th>
                        <th scope="col">Type</th><th scope="col">Description</th><th scope="col">Status</th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($promotions as $promotion) { ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($promotion['Code']) ?></th>
                            <td><?= htmlspecialchars($promotion['PromotionValue']) ?></td>
                            <td><?= htmlspecialchars($promotion['ExpiresOn'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($promotionTypes[$promotion['PromotionType']]) ?></td>
                            <td><?= htmlspecialchars($promotion['Details']) ?></td>
                            <td><?= htmlspecialchars($promotion['Status']) ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </section>
</main>
</body>
</html>
