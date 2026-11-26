<div id="password">
    <h2>Password settings</h2>
    <form method="post" action="admin_profile.php">
                <input type="hidden" name="profile_token" value="<?= htmlspecialchars($token) ?>">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="old-password">Old password</label>
                    <input type="password" class="form-control" name="old_password" id="old-password" autocomplete="current-password" aria-describedby="old-password-error" required>
                    <span id="old-password-error" aria-live="polite" class="text-danger"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="new-password">New password</label>
                    <input type="password" class="form-control"  name="password" id="new-password" autocomplete="new-password" aria-describedby="new-password-error requirement_1 requirement_2 requirement_3 requirement_4 requirement_5" required>
                    <span id="new-password-error" aria-live="polite" class="text-danger"></span>
                    <span id="requirement_1" class="text-danger"></span>
                    <span id="requirement_2" class="text-danger"></span>
                    <span id="requirement_3" class="text-danger"></span>
                    <span id="requirement_4" class="text-danger"></span>
                    <span id="requirement_5" class="text-danger"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="confirm-password">Confirm new password</label>
                    <input type="password" class="form-control" name="cpassword" id="confirm-password" autocomplete="new-password" aria-describedby="confirm-password-error" required>
                    <span id="confirm-password-error" aria-live="polite" class="text-danger"></span>
                </div>
            </div>
        </div>
        <div>
            <button class="btn btn-primary" type="submit" name="change" onclick="return validate()">Update</button>
            <button class="btn btn-light" type="button" onclick="redirectToIndex()">Cancel</button>
        </div>
    </form>
</div>

<script>
function validate() {
    


var oldPassword = document.getElementById("old-password").value;
var newPassword = document.getElementById("new-password").value;
var confirmPassword = document.getElementById("confirm-password").value;

var oldPasswordError = document.getElementById("old-password-error");
var newPasswordError = document.getElementById("new-password-error");
var confirmPasswordError = document.getElementById("confirm-password-error");
var requirement1Error = document.getElementById("requirement_1");
var requirement2Error = document.getElementById("requirement_2");
var requirement3Error = document.getElementById("requirement_3");
var requirement4Error = document.getElementById("requirement_4");
var requirement5Error = document.getElementById("requirement_5");

    // Reset error messages
    oldPasswordError.textContent = "";
newPasswordError.textContent = "";
confirmPasswordError.textContent = "";
requirement1Error.innerHTML = "";
requirement2Error.innerHTML = "";
requirement3Error.innerHTML = "";
requirement4Error.innerHTML = "";
requirement5Error.innerHTML = "";


    // Check if the fields are empty
    var isValid = true;

    if (oldPassword === "")
    {
        oldPasswordError.textContent = "Please enter your old password.";
        isValid = false;
    } 
    

    if (newPassword === "") {
    newPasswordError.innerHTML = "Please enter a new password.";
    isValid = false;
} else {
    // Check individual requirements and display corresponding error messages
    if (newPassword.length < 8) {
        requirement1Error.innerHTML = "New password must be at least 8 characters.<br>";
        isValid = false;
    }
    if (!/[A-Z]/.test(newPassword)) {
        requirement2Error.innerHTML = "New password must contain at least 1 uppercase letter.<br>";
        isValid = false;
    }
    if (!/[a-z]/.test(newPassword)) {
        requirement3Error.innerHTML = "New password must contain at least 1 lowercase letter.<br>";
        isValid = false;
    }
    if (!/[0-9]/.test(newPassword)) {
        requirement4Error.innerHTML = "New password must contain at least 1 digit.<br>";
        isValid = false;
    }
    if (!/[!@#$%^&*()_+\-=[\]{};:'\"|,.<>/?`~]/.test(newPassword)) {
        requirement5Error.innerHTML = "New password must contain at least 1 special character.<br>";
        isValid = false;
    }
    if (newPassword === oldPassword) {
    newPasswordError.innerHTML = "New password must be different from the old password.<br>";
    isValid = false;
}
}


if (confirmPassword === "") {
    confirmPasswordError.textContent = "Please confirm your new password.";
    isValid = false;
    return false; // Add this line to return false immediately if confirm password is empty
}

if (confirmPassword !== newPassword) {
    confirmPasswordError.textContent = "Confirm password and new password do not match.";
    isValid = false;
}

    if (!isValid) {
        return false;
    }

    return true;

}
    
    function redirectToIndex() 
    {
        window.location.href = 'admin_productlist.php';
    }
</script>
