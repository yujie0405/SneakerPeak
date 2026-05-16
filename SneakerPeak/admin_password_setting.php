<?php
    include("database/Database_Connection.php");
    
?>
<div id="password">
    <h3 class="mb-4">Password Settings</h3>
    <form method="post" action="admin_profile.php">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Old password</label>
                    <input type="password" class="form-control" name="old_password" id="old-password" required>
                    <span id="old-password-error" class="text-danger"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>New password</label>
                    <input type="password" class="form-control"  name="password" id="new-password" required>
                    <span id="new-password-error" class="text-danger"></span>
                    <span id="requirement_1" class="text-danger"></span>
                    <span id="requirement_2" class="text-danger"></span>
                    <span id="requirement_3" class="text-danger"></span>
                    <span id="requirement_4" class="text-danger"></span>
                    <span id="requirement_5" class="text-danger"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Confirm new password</label>
                    <input type="password" class="form-control" name="cpassword" id="confirm-password" required>
                    <span id="confirm-password-error" class="text-danger"></span>
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
    <?php
        $email = $_SESSION['email'];
        $get_old_pass_query = "SELECT password FROM admin WHERE email = '$email'";
        $get_old_pass_result = mysqli_query($conn, $get_old_pass_query);
        $get_old_pass_row = mysqli_fetch_assoc($get_old_pass_result);
        $oldPasswordFromPHP = $get_old_pass_row['password'];
        
    ?>


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
    else if (oldPassword !== "<?php echo $oldPasswordFromPHP; ?>")
    {
        oldPasswordError.textContent = "Old password is incorrect.";
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
<?php
//Password Setting
if(isset($_POST['change']))
{
    // Get user inputs
    $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    // Get user's old password from the database
    $email = $_SESSION['email'];
    $get_old_pass_query = "SELECT password FROM admin WHERE email = '$email'";
    $get_old_pass_result = mysqli_query($conn, $get_old_pass_query);
    $get_old_pass_row = mysqli_fetch_assoc($get_old_pass_result);

    // Update user's password
    $code = 0;
    $update_pass = "UPDATE admin SET password = '$password' WHERE email = '$email'";
    $run_query = mysqli_query($conn, $update_pass);

    if($run_query)
        {
            // Password changed successfully, redirect to confirmation page
            ?>
			<script>
				alert ("Your password changed. Next time you can login with your new password.");
			</script>
			<?php
            echo "<script>(window.location.href='admin_profile.php');</script>";
            exit();
        }
        else
        {
            // Error updating password in database
			?>
			<script>
				alert ("Failed to change your password!");
			</script>
			<?php
        }
}
?>
</body>
</html>