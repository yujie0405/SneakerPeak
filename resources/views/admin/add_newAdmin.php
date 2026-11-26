<?php
$firstError = array_key_first($errors);
$errorAttributes = static fn(string $field): string => (isset($errors[$field]) ? ' aria-invalid="true" aria-describedby="' . $field . '-error"' : '')
    . ($firstError === $field ? ' autofocus' : '');
$showError = static function (string $field) use ($errors): void {
    if (isset($errors[$field])) { ?>
        <span id="<?= $field ?>-error" class="admin-field-error"><?= htmlspecialchars($errors[$field]) ?></span>
    <?php }
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrators | Sneaker Peak</title>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
<body class="admin-workspace">
<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
<main class="home-section">
    <header class="home-content"><h1>Administrators</h1></header>
    <section class="table">
        <header class="table__header"><h2>Add new admin</h2></header>
        <?php if ($notice !== null) { ?><p class="admin-catalog-notice" role="status"><?= htmlspecialchars($notice) ?></p><?php } ?>
        <?php if ($errors) { ?><p class="admin-field-error" role="alert"><?= htmlspecialchars($errors['form'] ?? 'Review the fields marked below.') ?></p><?php } ?>
        <form action="add_newAdmin.php" method="post" novalidate>
            <input type="hidden" name="admin_token" value="<?= htmlspecialchars($token) ?>">
            <fieldset class="admin-form-group">
                <legend>Account details</legend>
                <div class="admin-catalog-fields">
                    <label>Name
                        <input type="text" name="name" autocomplete="name" maxlength="255" value="<?= htmlspecialchars($name) ?>" required<?= $errorAttributes('name') ?>>
                        <?php $showError('name'); ?>
                    </label>
                    <label>Email
                        <input type="email" name="email" autocomplete="email" maxlength="255" value="<?= htmlspecialchars($email) ?>" required<?= $errorAttributes('email') ?>>
                        <?php $showError('email'); ?>
                    </label>
                </div>
            </fieldset>
            <fieldset class="admin-form-group">
                <legend>Credentials</legend>
                <div class="admin-catalog-fields">
                    <label>Password
                        <input type="password" name="password" autocomplete="new-password" minlength="8" maxlength="72" required<?= $errorAttributes('password') ?>>
                        <?php $showError('password'); ?>
                    </label>
                    <label>Confirm password
                        <input type="password" name="cpassword" autocomplete="new-password" minlength="8" maxlength="72" required<?= $errorAttributes('cpassword') ?>>
                        <?php $showError('cpassword'); ?>
                    </label>
                </div>
            </fieldset>
            <div class="admin-actions">
                <button type="submit" name="add">Create admin</button>
                <a class="admin-button secondary" href="DashBoad.php">Cancel</a>
            </div>
        </form>
    </section>
    <section class="table">
        <header class="table__header"><h2>All administrators</h2></header>
        <div class="admin-table-scroll" tabindex="0" role="region" aria-label="Administrators">
            <table>
                <thead><tr><th scope="col">Name</th><th scope="col">Email</th></tr></thead>
                <tbody>
                <?php foreach ($admins as $admin) { ?>
                    <tr><th scope="row"><?= htmlspecialchars($admin['DisplayName']) ?></th><td><?= htmlspecialchars($admin['Email']) ?></td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
</body>
</html>
