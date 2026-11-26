<?php
$currentAdminPage = strtolower(basename((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH)));
$adminNavigation = [
    '' => ['DashBoad.php' => 'Dashboard'],
    'Catalog' => [
        'admin-ProductDashboard.php' => 'Product overview',
        'admin_productlist.php' => 'Products',
        'admin_addproduct.php' => 'Add product',
        'admin_addbrand.php' => 'Brands and categories',
    ],
    'Sales' => [
        'admin-OrderDashboard.php' => 'Order overview',
        'admin-Orderlist.php' => 'Orders',
        'admin_promote.php' => 'Promotions',
    ],
    'Accounts' => [
        'viewCustomers.php' => 'Customers',
        'add_newAdmin.php' => 'Administrators',
        'admin_profile.php' => 'Profile and password',
    ],
];
$adminNavigationParent = [
    'admin_productdetails.php' => 'admin_productlist.php',
    'admin_orderdetails.php' => 'admin-orderlist.php',
    'admin_account_setting.php' => 'admin_profile.php',
    'admin_password_setting.php' => 'admin_profile.php',
];
?>
<aside class="admin-sidebar">
    <div class="admin-brand">
        <a href="DashBoad.php">Sneaker Peak</a>
        <button type="button" class="admin-nav-toggle" aria-controls="admin-navigation" aria-expanded="true" hidden>Menu</button>
    </div>
    <nav id="admin-navigation" aria-label="Admin navigation">
        <?php foreach ($adminNavigation as $group => $links) { ?>
            <?php if ($group !== '') { ?><p class="admin-nav-heading"><?= htmlspecialchars($group) ?></p><?php } ?>
            <ul>
                <?php foreach ($links as $url => $label) {
                    $here = strtolower($url) === $currentAdminPage;
                    $within = strtolower($url) === ($adminNavigationParent[$currentAdminPage] ?? null);
                    ?>
                    <li><a href="<?= htmlspecialchars($url) ?>"<?= $here ? ' aria-current="page"' : ($within ? ' aria-current="location"' : '') ?>><?= htmlspecialchars($label) ?></a></li>
                <?php } ?>
            </ul>
        <?php } ?>
        <div class="admin-nav-account">
            <span><?= htmlspecialchars((string) ($_SESSION['name'] ?? 'Admin')) ?></span>
            <a href="logout.php">Log out</a>
        </div>
    </nav>
</aside>
<script src="assets/js/admin-navigation.js" defer></script>
