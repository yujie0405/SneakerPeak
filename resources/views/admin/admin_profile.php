<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Profile</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
<body class="admin-workspace">
	<?php require SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
<section class="home-section">
		<header class="home-content"><h1>Profile and password</h1></header>
        <div class="admin-profile-panel">
					<?php
						include SNEAKERPEAK_ROOT . '/resources/views/partials/admin_profile_sidebar.php';
					?>
					<div class="tab-content" id="v-pills-tabContent">
					<?php if ($message !== null) { ?>
                    <p role="status"><?= htmlspecialchars($message) ?></p>
                <?php } ?>
                <?php
                require SNEAKERPEAK_ROOT . ($page === 1
                    ? '/resources/views/admin/admin_account_setting.php'
                    : '/resources/views/admin/admin_password_setting.php');
                ?>

				</div>
			</div>
	</section>

</body>
</html>
