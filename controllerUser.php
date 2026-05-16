<?php 
session_start();
include ("database/Database_Connection.php");
$email = "";
$name = "";
$errors = array();

//if user signup button
//if user signup button
if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $rphone = mysqli_real_escape_string($conn, $_POST['phone']);

    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    }

    // Check if email exists in customer table
    $customer_email_check = "SELECT * FROM customer WHERE email = '$email'";
    $customer_res = mysqli_query($conn, $customer_email_check);

    // Check if email exists in admin table
    $admin_email_check = "SELECT * FROM admin WHERE email = '$email'";
    $admin_res = mysqli_query($conn, $admin_email_check);

    if (mysqli_num_rows($customer_res) > 0 || mysqli_num_rows($admin_res) > 0) {
        $errors['email'] = "Email that you have entered is already exist!";
    }

    if(count($errors) === 0)
    {
        $code = rand(999999, 111111);
        $status = "notverified";
        $insert_data = "INSERT INTO customer (name, Customer_Address, Customer_ContractNumber, email, password, code, status)
                        values('$name', '$address', '$rphone', '$email', '$password', '$code', '$status')";
        $data_check = mysqli_query($conn, $insert_data);

        if($data_check)
        {
            $subject = "Email Verification Code - Account Registration";
            $message = "Dear $name,\n\nThank you for registering an account with our website. Please use the following verification code to complete your registration:\n\nVerification Code: $code\n\nPlease enter this code on the verification page to activate your account.\n\nIf you have any questions or need further assistance, please feel free to contact our support team.\n\nBest regards,\nSneakerPeak";
            $sender = "From: 1211202710@student.mmu.edu.my";

            
            if(mail($email, $subject, $message, $sender))
            {
                $info = "We've sent a verification code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                
                echo "<script>alert('Registration successful.');</script>";
                echo "<script>(window.location.href='otp_user.php');</script>"; 

                exit();
            }
            else
            {
                $errors['otp-error'] = "Failed while sending code!";
            }
        }
        else
        {
            $errors['db-error'] = "Failed while inserting data into database!";
        }
    }
}

//if user click verification code submit button
if(isset($_POST['check']))
{
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
    $check_code = "SELECT * FROM customer WHERE code = $otp_code";
    $code_res = mysqli_query($conn, $check_code);

    if(mysqli_num_rows($code_res) > 0)
    {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $email = $fetch_data['email'];
        $code = 0;
        $status = 'verified';
        $update_otp = "UPDATE customer SET code = $code, status = '$status' WHERE code = $fetch_code";
        $update_res = mysqli_query($conn, $update_otp);

        if($update_res)
        {
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            echo "<script>alert('Veritification successful, You may login now.');</script>";
            echo "<script>(window.location.href='Login.php');</script>"; 

            exit();
        }
        else
        {
            $errors['otp-error'] = "Failed while updating code!";
        }
    }
    else
    {
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the email exists in the customer table
    $check_customer_email = "SELECT * FROM customer WHERE email = '$email'";
    $customer_res = mysqli_query($conn, $check_customer_email);

    // Check if the email exists in the admin table
    $check_admin_email = "SELECT * FROM admin WHERE email = '$email'";
    $admin_res = mysqli_query($conn, $check_admin_email);

    if (mysqli_num_rows($customer_res) > 0) {
        // Email exists in the customer table
        $fetch = mysqli_fetch_assoc($customer_res);
        $fetch_pass = $fetch['password'];
        $_SESSION["CustomerID"] = $fetch["id"];

        if ($password === $fetch_pass) {
            $_SESSION['email'] = $email;
            $status = $fetch['status'];

            if ($status == 'verified') {
                // Retrieve the customer's name from the database
                $customerName = $fetch['name'];

                // Store the customer's name in the session
                $_SESSION['name'] = $customerName;

                // Redirect to customer page
                if (isset($_POST['remember'])) {
                    // Set a cookie to remember the user
                    setcookie('remember_email', $email, time() + (86400 * 30), "/"); // Cookie expires in 30 days
                    setcookie('remember_password', $password, time() + (86400 * 30), "/"); // Cookie expires in 30 days
                } else {
                    // Remove the remember me cookies if they exist
                    if (isset($_COOKIE['remember_email'])) {
                        setcookie('remember_email', '', time() - 3600, "/");
                    }
                    if (isset($_COOKIE['remember_password'])) {
                        setcookie('remember_password', '', time() - 3600, "/");
                    }
                }

                header('location:index.php');
            } else {
                $info = "It looks like you haven't verified your email yet - $email";
                $_SESSION['info'] = $info;
                header('location: otp_user.php');
            }
        } else {
            $errors['email'] = "Incorrect email or password!";
        }
    } else if (mysqli_num_rows($admin_res) > 0) {
        // Email exists in the admin table
        $fetch = mysqli_fetch_assoc($admin_res);
        $fetch_pass = $fetch['password'];
        $_SESSION["AdminID"] = $fetch["id"];

        if ($password === $fetch_pass) {
            $_SESSION['email'] = $email;

            // Redirect to admin page
           
            echo "<script>(window.location.href='DashBoad.php');</script>";
        } else {
            $errors['email'] = "Incorrect email or password!";
        }
    } else {
        $errors['email'] = "It looks like you're not yet a member! Click on the bottom link to sign up.";
    }
}
//if user click continue button in forgot password form
if(isset($_POST['check-email']))
{
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $check_email = "SELECT * FROM customer WHERE email ='$email'";
    $run_sql = mysqli_query($conn, $check_email);

    if(mysqli_num_rows($run_sql) > 0)
    {
        $code = rand(999999, 111111);
        $insert_code = "UPDATE customer SET code = $code WHERE email = '$email'";
        $run_query =  mysqli_query($conn, $insert_code);

        if($run_query)
        {
            $subject = "Password Reset Code";
            $message = "Dear Customer,\n\nWe have received a request to reset your password for your account. To proceed with the password reset, please use the following code:\n\nCode: $code\n\nIf you did not initiate this request, please ignore this message.\n\nThank you.\n\nBest regards,\n[SneakerPeak]";
            $sender = "From: 1211202710@student.mmu.edu.my";

            if(mail($email, $subject, $message, $sender))
            {
                $info = "We've sent a password reset otp to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;

                header('location: otp_reset.php');

                exit();
            }
            else
            {
                $errors['otp-error'] = "Failed while sending code!";
            }
        }
        else
        {
            $errors['db-error'] = "Something went wrong!";
        }
    }
    else
    {
        $errors['email'] = "This email address does not exist!";
    }
}


//if user click check reset otp button
if(isset($_POST['check-reset-otp']))
{
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
    $check_code = "SELECT * FROM customer WHERE code = $otp_code";
    $code_res = mysqli_query($conn, $check_code);

    if(mysqli_num_rows($code_res) > 0)
    {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $email = $fetch_data['email'];
        $_SESSION['email'] = $email;
        $info = "Please create a new password that you don't use on any other site.";
        $_SESSION['info'] = $info;

        header('location: chg_pwd.php');

        exit();

    }
    else
    {
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

//if user click change password button
if(isset($_POST['change-password']))
{
    $_SESSION['info'] = "";
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    if($password !== $cpassword)
    {
        $errors['password'] = "Confirm password not matched!";
    }
    else
    {
            $code = 0;
            $email = $_SESSION['email']; //getting this email using session
            $update_pass = "UPDATE customer SET code = $code, password = '$password' WHERE email = '$email'";
            $run_query = mysqli_query($conn, $update_pass);

            if($run_query)
            {
                $info = "Your password changed. Next time you can login with your new password .";
                $_SESSION['info'] = $info;

                header('Location: user_changed_password.php');
            }
            else
            {
                $errors['db-error'] = "Failed to change your password!";
            }
    }
}

//if login now button click
if(isset($_POST['login_for_Now']))
{
    header('Location: Login.php');
}

//if cancel button click
if(isset($_POST['cancel']))
{
    header('Location: main_page.php');
}

?>
