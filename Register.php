<?php
    include("database/Database_Connection.php");
    require_once("controllerUser.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css%22%3E">
    <!-- <link rel="stylesheet" type="text/css" href="Registration.css"> -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@700&family=Poppins:wght@400;500;600&display=swap');
*{
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
.center 
{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 400px;
    background: whitesmoke;
    border-radius: 10px;
    box-shadow: 10px 10px 15px rgba(0,0,0,0.05);
}
.center h1 
{
    font-size: 32px;
    font-weight: bold;  
    text-align: center;
    padding: 20px 0;
    border-bottom: 1px solid silver;
}
.center form 
{
    padding: 0 40px;
    box-sizing: border-box;
}
form .txt_field 
{
    position: relative;
    border-bottom: 2px solid #adadad;
    margin: 30px 0;
}
.txt_field input 
{
    width: 100%;
    padding: 0 50px;
    height: 40px;
    font-size: 16px;
    border: none;
    background: none;
    outline: none;
}
.txt_field label 
{
    position: absolute;
    top: 50%;
    left: 55px;
    color: #adadad;
    transform: translateY(-50%);
    font-size: 16px;
    pointer-events: none;
    transition: .5s;
}
.txt_field span::before 
{
    content: '';
    position: absolute;
    top: 40px;
    left: 0;
    width: 0%;
    height: 2px;
    background: #2691d9;
    transition: .5s;
}
.txt_field input:focus ~ label,
.txt_field input:valid ~ label 
{
    top: -5px;
    left: 50px;
    color: #2691d9;
}
.txt_field input:focus ~ span::before,
.txt_field input:valid ~ span::before 
{
    width: 100%;
}
.pass 
{
    margin: -5px 0 20px 5px;
    color: #a6a6a6;
    cursor: pointer;
}
.pass:hover 
{
    text-decoration: underline;
}
input[type="submit"]
{
    width: 100%;
    height: 50px;
    border: 1px solid;
    background: #2691d9;
    border-radius: 25px;
    font-size: 18px;
    color: #e9f4fb;
    font-weight: 700;
    cursor: pointer;
    outline: none;
}
input[type="submit"]:hover 
{
    border-color: #2691d9;
    transition: .5s;
}
.login_link 
{
    margin: 30px 0;
    text-align: center;
    font-size: 16px;
    color: #666666;
}
.login_link a 
{
    color: #2691d9;
    text-decoration: none;
}
.login_link a:hover 
{
    text-decoration: underline;
}
#eye
{
    cursor: pointer;
    position: absolute;
    top: 20px;
    right: 30px;
    width: 0%;
    height: 2px;
    color: #2691d9;
}
#phone, #location, #name, #lock, #mail
{
    position: absolute;
    top: 20px;
    left:5px;
    width: 0%;
    height: 2px;
    color: #2691d9;
}
.hidden 
{
    display: none;
}
.password_required, .cpassword_required, .email_required, .phone_required
{
    display:none;
}
.password_required ul, .cpassword_required ul, .email_required ul, .phone_required ul
{
    padding: 0;
    margin: 0 0 15px;
    list-style: none;
}
.password_required ul li, .cpassword_required ul li, .email_required ul li, .phone_required ul li
{
    margin-bottom: 8px;
    color:red;
    font-weight: 700;
}
.password_required ul li.active, .cpassword_required ul li.active, .email_required ul li.active, .phone_required ul li.active
{
    color: #02af02;
}
.password_required ul li span:before, .cpassword_required ul li span:before, .email_required ul li span:before, .phone_required ul li span:before
{
    content :'❌';
}
.password_required ul li.active span:before, .cpassword_required ul li.active span:before, .email_required ul li.active span:before, .phone_required ul li.active span:before
{
    content :'✅';
}
.close {
  position: relative;
  z-index: 9999; /* Adjust the value as needed, higher values appear above lower values */
  margin: 0.9rem 0 0 0;
}
</style>
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