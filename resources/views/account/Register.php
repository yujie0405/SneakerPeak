<?php
    require_once SNEAKERPEAK_ROOT . '/Controller/AuthController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css%22%3E">
    <link rel="stylesheet" href="css/Register.css">
    <!-- <link rel="stylesheet" type="text/css" href="Registration.css"> -->
<style>

</style>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
    <div class="center">
        <a href="index.php" class="color"><i class="close fa-solid fa-rectangle-xmark fa-2xl"></i></a>
        <h1>Registration</h1>
        <form action="Register.php" method="POST" autocomplete="">
            <?php
                if(count($errors) == 1)
                {
            ?>
                    <div class="alert alert-danger text-center">
            <?php
                foreach($errors as $showerror)
                {
                    echo $showerror;
                }
            ?>
            </div>
            <?php
                }
                else if(count($errors) > 1)
                {
            ?>
                    <div class="alert alert-danger">
                    <?php
                        foreach($errors as $showerror)
                        {
                    ?>
                            <li><?php echo $showerror; ?></li>
                    <?php
                        }
                    ?>
                        </div>
                <?php
                }
                ?>

            <div class="txt_field">
                <input type="text" name="name" required value="<?php echo $name ?>">
                <span><i class="fa-solid fa-user fa-xl" id="name"></i></i></span>
                <label>Full Name</label>
            </div>


            <div class="txt_field">
                <input type="text" name="email" id="email_validation" required value="<?php echo $email ?>"> 
                <span><i class="fa-solid fa-envelope fa-xl" id="mail"></i></span>
                <label>Email</label>
            </div>
            <!-- <span id="email-validation-msg"></span> -->
            <div class="email_required">
                <ul>
                    <li class="email"><span></span>Valid Email Address</li>
                </ul>
            </div>  

            <div class="txt_field">
                <input type="password" name="password" id="password_validation" required>
                <span><i class="fa-solid fa-lock fa-xl" id="lock"></i><i class="fa-sharp fa-regular fa-eye-slash fa-lg toggle-password" id="eye" data-target="password"></i></span>
                <label>Password</label>
            </div>
            <div class="password_required">
                <ul>
                    <li class="lowercase"><span></span>One Lowercase Letter</li>
                    <li class="capital"><span></span>One Capital Letter</li>
                    <li class="number"><span></span>One Number</li>
                    <li class="special"><span></span>One Special Characters</li>
                    <li class="characters"><span></span>At least 8 Characters</li>
                </ul>
            </div>  


            <div class="txt_field">
                <input type="password" name="cpassword" id="cpassword_validation" required>
                <span><i class="fa-solid fa-lock fa-xl" id="lock"></i><i class="fa-sharp fa-regular fa-eye-slash fa-lg toggle-password" id="eye" data-target="cpassword"></i></span>
                <label>Confirm Password</label>
            </div>
            <div class="cpassword_required">
                <ul>
                    <li class="match"><span></span>Passwords Match</li>
                <ul>
            </div>

            <div class="txt_field">
                <input type="tel" name="phone" id="phone_validation" required>
                <span><i class="fa-solid fa-phone fa-xl" id="phone"></i></span>
                <label>Phone Number</label>
            </div>
            <div class="phone_required">
                <ul>
                    <li class="phone"><span></span>Valid Phone Number format</li>
                <ul>
            </div>

            <div class="txt_field">
                <input type="text" name="address" required>
                <span><i class="fa-solid fa-location-dot fa-xl" id="location"></i></span>
                <label>Address</label>
            </div>
            
            <input type="submit" class="input_submit" value="Register" name="register" onclick="return validate();">

            <div class="login_link">
                Already a member? <a href="Login.php">Login</a>
            </div>
        </form>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

const togglePasswords = document.querySelectorAll('.toggle-password');
const passwords = document.querySelectorAll('input[type="password"]');

// Hide the eye icons initially
togglePasswords.forEach(function(togglePassword) 
{
    togglePassword.classList.add('hidden');
});

passwords.forEach(function(password) 
{
    password.addEventListener('input', function (e) 
    {
        // Show the eye icon if the password field has a value, otherwise hide it
        const togglePassword = document.querySelector(`[data-target="${password.name}"]`);
        if (password.value) 
        {
            togglePassword.classList.remove('hidden');
        } 
        else 
        {
            togglePassword.classList.add('hidden');
        }
    });
});

togglePasswords.forEach(function(togglePassword)
 {
    togglePassword.addEventListener('click', function (e) 
    {
        const targetPasswordName = togglePassword.getAttribute('data-target');
        const targetPassword = document.querySelector(`input[name="${targetPasswordName}"]`);
        
        // toggle the type attribute
        const type = targetPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        targetPassword.setAttribute('type', type);
        // toggle the eye slash icon
        togglePassword.classList.toggle('fa-eye-slash');
        // add or remove the fa-eye class depending on whether the eye slash icon is present
        if (togglePassword.classList.contains('fa-eye-slash')) 
        {
            togglePassword.classList.remove('fa-eye');
        } else 
        {
            togglePassword.classList.add('fa-eye');
        }
    });
});

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

    //password validation
    $('#password_validation').on('focus',function()
    {
        $('.password_required').slideDown();
    })
    $('#password_validation').on('blur',function()
    {
        $('.password_required').slideUp();
    })


    $('#password_validation').on('keyup',function()
    {
        passValue = $(this).val();

        if(passValue.match(/[a-z]/g))
        {
            $('.lowercase').addClass('active');
            lowercase_flag = true;
        }
        else
        {
            $('.lowercase').removeClass('active');
            lowercase_flag = false;
        }

        if(passValue.match(/[A-Z]/g))
        {
            $('.capital').addClass('active');
            capital_flag = true;
        }
        else
        {
            $('.capital').removeClass('active');
            capital_flag = false;
        }

        if(passValue.match(/[0-9]/g))
        {
            $('.number').addClass('active');
            number_flag = true;
        }
        else
        {
            $('.number').removeClass('active');
            number_flag = false;
        }

        if(passValue.match(/[!@#$%^&*()_+\-=[\]{};:'\"|,.<>/?`~]/g))
        {
            $('.special').addClass('active');
            special_flag = true;
        }
        else
        {
            $('.special').removeClass('active');
            special_flag = false;
        }

        if(passValue.length == 8 || passValue.length > 8)
        {
            $('.characters').addClass('active');
            characters_flag = true;
        }
        else
        {
            $('.characters').removeClass('active');
            characters_flag = false;
        }

    })


    //confirm_password validation
    $('#cpassword_validation').on('focus',function()
    {
        $('.cpassword_required').slideDown();
    })
    $('#cpassword_validation').on('blur',function()
    {
        $('.cpassword_required').slideUp();
    })


    $('#cpassword_validation').on('keyup',function()
    {
        passValue = $(this).val();
        
        if(passValue === $('#password_validation').val())
        {
            $('.match').addClass('active');
            match = true;
            
        }
        else
        {
            $('.match').removeClass('active');
            match = false;
        }
       
    })

    $('#phone_validation').on('focus',function()
{
    $('.phone_required').slideDown();
})
$('#phone_validation').on('blur',function()
{
    $('.phone_required').slideUp();
})

$('#phone_validation').on('keyup',function()
{
    phoneValue = $(this).val();

    if (/^01\d\d{7,8}$|^01\d-\d{3}\s\d{4}$/.test(phoneValue))
    {
        $('.phone').addClass('active');
        phone_flag = true;
    }
    else
    {
        $('.phone').removeClass('active');
        phone_flag = false;
    }

})

function validate() {
            if($('.email').hasClass('active') && $('.lowercase').hasClass('active') && 
                $('.capital').hasClass('active') && $('.number').hasClass('active') && 
                $('.special').hasClass('active') && $('.characters').hasClass('active') && 
                $('.match').hasClass('active') && $('.phone').hasClass('active')) {
                return true;
            } else {
                return false;
            }
        }
</script>

</body>
</html>