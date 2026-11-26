<div id="account">
            <h2>Account settings</h2>
            <form method="post" action="admin_profile.php">
                <input type="hidden" name="profile_token" value="<?= htmlspecialchars($token) ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="admin-name">Name</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars((string) $admin_name) ?>" name="admin_name" id="admin-name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="admin-email">Email</label>
                            <input class="form-control" id="admin-email" type="email" value="<?= htmlspecialchars((string) $admin_email) ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="admin-phone">Phone number</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars((string) $admin_phone) ?>" name="admin_number" id="admin-phone" aria-describedby="admin-phone-example" pattern="^(\+?6?01)[0-46-9]-*[0-9]{7,8}$" title="Please enter a valid Malaysia phone number">
                            <small id="admin-phone-example" class="form-text text-muted">Example: +6012-3456789 or 012-3456789</small>
                        </div>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary" type="submit" name="update">Update</button>
                    <button class="btn btn-light" type="button" onclick="redirectToIndex()">Back</button>
                </div>
            </form>
        </div>
<script>
    function redirectToIndex() 
    {
        window.location.href = 'DashBoad.php';
    }
</script>

