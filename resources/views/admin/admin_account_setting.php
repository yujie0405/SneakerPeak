<div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
            <h3 class="mb-4">Account Settings</h3>
            <form method="post" action="admin_profile.php">
                <input type="hidden" name="profile_token" value="<?= htmlspecialchars($token) ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars((string) $admin_name) ?>" name="admin_name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <span class="form-control"><?= htmlspecialchars((string) $admin_email) ?></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Phone number</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars((string) $admin_phone) ?>" name="admin_number" pattern="^(\+?6?01)[0-46-9]-*[0-9]{7,8}$" title="Please enter a valid Malaysia phone number">
                            <small class="form-text text-muted">Example: +6012-3456789 or 012-3456789</small>
                        </div>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary" type="submit" name="update">Update</button>
                    <button class="btn btn-light" name="cancel" onclick="redirectToIndex(); return false;">Back</button>
                </div>
            </form>
        </div>
<script>
    function redirectToIndex() 
    {
        window.location.href = 'DashBoad.php';
    }
</script>

