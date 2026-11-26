<?php 
    require SNEAKERPEAK_ROOT . '/database/Database_Connection.php';
    require SNEAKERPEAK_ROOT . '/Controller/AuthController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Forgot Password</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
@import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
*
{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}
body
{
    margin: 0;
    padding: 0;
    background-image: url("background.png");
    background-position-x: -120px;
    background-position-y:  100px;
    height: 100vh;
    /* overflow: hidden; */
}
::selection{
    color: var(--color-surface);
    background: #6665ee;
}
.container{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.container .form{
    background: whitesmoke;
    padding: 30px 35px;
    border-radius: 5px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
.container .form form .form-control{
    height: 40px;
    font-size: 15px;
}
.container .form form .forget-pass{
    margin: -15px 0 15px 0;
}
.container .form form .forget-pass a{
   font-size: 15px;
}
.container .form form .button{
    background: #2691d9;
    color: var(--color-surface);
    font-size: 17px;
    font-weight: 500;
    transition: all 0.3s ease;
}
.container .form form .button:hover{
    background: #5757d1;
}
.container .form form .link{
    padding: 5px 0;
}
.container .form form .link a{
    color: #6665ee;
}
.container .login-form form p{
    font-size: 14px;
}
.container .row .alert{
    font-size: 14px;
}
.email_required
    {
        display:none;
    }
    .email_required ul
    {
        padding: 0;
        margin: 0 0 15px;
        list-style: none;
    }
    .email_required ul li
    {
        margin-bottom: 8px;
        color:red;
        font-weight: 700;
    }
    .email_required ul li.active
    {
        color: #02af02;
    }
    .email_required ul li span:before
    {
        content :'❌';
    }
    .email_required ul li.active span:before
    {
        content :'✅';
    }
</style>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="Forgot_pwd.php" method="POST" autocomplete="">
                    <h2 class="text-center">Forgot Password</h2>
                    <p class="text-center">Enter your email address</p>
                    <?php
                        if(count($errors) > 0)
                        {
                            ?>
                            <div class="alert alert-danger text-center">
                                <?php 
                                    foreach($errors as $error)
                                    {
                                        echo $error;
                                    }
                                ?>
                            </div>
                            <?php
                        }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" id="email_validation" placeholder="Enter email address" required value="<?php echo $email ?>">
                    </div>
                    <div class="email_required">
                        <ul>
                            <li class="email"><span></span>Valid Email Address</li>
                        </ul>
                    </div>  
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="check-email" value="Continue">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // email validation
    $('#email_validation').on('focus',function()
    {
        $('.email_required').slideDown();
    })
    $('#email_validation').on('blur',function()
    {
        $('.email_required').slideUp();
    })

    $('#email_validation').on('keyup',function()
    {
        emailValue = $(this).val();

        if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue))
        {
            $('.email').addClass('active');
            email_flag = true;
        }
        else
        {
            $('.email').removeClass('active');
            email_flag = false;
        }
    })
</script>
</body>
</html>