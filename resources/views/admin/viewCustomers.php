<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customers | Sneaker Peak</title>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
<body class="admin-workspace">
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
<main class="home-section">
    <header class="home-content"><h1>Customers</h1></header>
    <section class="table">
        <form class="admin-catalog-filters admin-list-filters" method="get" action="viewCustomers.php" aria-label="Customer filters">
            <label>Name <input type="search" name="name" value="<?= htmlspecialchars($name) ?>"></label>
            <div class="admin-actions">
                <button type="submit">Search</button>
                <a class="admin-button secondary" href="viewCustomers.php">Clear filter</a>
            </div>
        </form>
        <?php if ($notice !== null) { ?><p class="admin-catalog-notice" role="status"><?= htmlspecialchars($notice) ?></p><?php } ?>
        <p><?= $total ?> <?= $total === 1 ? 'customer' : 'customers' ?> found</p>
        <?php if (!$customers) { ?>
            <p>No customers match your filter.</p>
        <?php } else { ?>
            <div class="admin-table-scroll" tabindex="0" role="region" aria-label="Customer results">
                <table>
                    <thead><tr>
                        <th scope="col">Name</th><th scope="col">Email</th><th scope="col">Phone</th>
                        <th scope="col">Status</th><th scope="col">Actions</th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($customers as $customer) { ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($customer['DisplayName']) ?></th>
                            <td><?= htmlspecialchars($customer['Email']) ?></td>
                            <td><?= htmlspecialchars($customer['Phone'] ?? '—') ?></td>
                            <td><span class="admin-size-status<?= $customer['IsActive'] ? '' : ' unavailable' ?>"><?= $customer['IsActive'] ? 'Active' : 'Inactive' ?></span></td>
                            <td class="admin-row-actions">
                                <a class="admin-button secondary" href="admin-Orderlist.php?customer=<?= $customer['Id'] ?>">Order history</a>
                                <?php if ($customer['IsActive']) { ?>
                                    <form class="admin-inline-form" method="post" action="<?= htmlspecialchars($pageUrl($page)) ?>"
                                          onsubmit="return confirm('Terminate this customer account? They will lose access to their account.');">
                                        <input type="hidden" name="customer_token" value="<?= htmlspecialchars($token) ?>">
                                        <input type="hidden" name="customer_id" value="<?= $customer['Id'] ?>">
                                        <button type="submit" class="delete">Terminate</button>
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
            <nav class="admin-pagination" aria-label="Customer results pages">
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
    </section>
</main>
</body>
</html>
