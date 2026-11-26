<aside class="profile-tab-nav" aria-label="Profile settings">
    <form class="admin-avatar-form" enctype="multipart/form-data" method="post">
        <input type="hidden" name="profile_token" value="<?= htmlspecialchars($token) ?>">
        <img class="admin-avatar" src="<?= htmlspecialchars(\SneakerPeak\Manager\CatalogImageUpload::url($admin['image'], \SneakerPeak\Manager\CatalogImageUpload::PROFILE)) ?>" alt="" width="64" height="64">
        <strong><?= htmlspecialchars((string) $admin_name) ?></strong>
        <label for="admin-profile-image">Change profile photo</label>
        <input type="file" name="image" id="admin-profile-image" accept=".jpg,.jpeg,.png" required>
        <button type="submit" class="btn-light">Upload</button>
    </form>

    <form class="admin-profile-tabs" method="post" action="admin_profile.php">
        <button type="submit" name="page" value="1"<?= $page === 1 ? ' aria-current="page"' : '' ?>>Account</button>
        <button type="submit" name="page" value="2"<?= $page === 2 ? ' aria-current="page"' : '' ?>>Password</button>
    </form>
</aside>
