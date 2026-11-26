<?php
    include SNEAKERPEAK_ROOT . '/database/Database_Connection.php';
    require_once SNEAKERPEAK_ROOT . '/src/App/Controllers/AuthController.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css%22%3E">
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
    /* background: linear-gradient(120deg,#2980b9, #8e44ad); */
    background-image: url("background.png");
    background-position-x: -120px;
    background-position-y:  100px;
    height: 100vh;
    overflow: hidden;
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
.pass a
{
    text-decoration: none;
    color: #adadad;
}
.pass a:hover
{
    color: #2691d9;
}
.pass:hover
{
    text-decoration: underline;
    color:  #2691d9;
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
.signup_link
{
    margin: 30px 0;
    text-align: center;
    font-size: 16px;
    color: #666666;
}
.signup_link a
{
    color: #2691d9;
    text-decoration: none;
}
.signup_link a:hover
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
#mail, #lock
{
    position: absolute;
    top: 20px;
    left:5px;
    width: 0%;
    height: 2px;
    color: #2691d9;
}
.remember_me {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.remember_me input[type="checkbox"] {
    margin-right: 10px;
}

.remember_me label {
    font-size: 16px;
    color: #666666;
}
.hidden 
{
    display: none;
}
.email_required, .password_required
{
     display:none;
}
.email_required ul, .password_required ul
{
    padding: 0;
    margin: 0 0 15px;
    list-style: none;
}
.email_required ul li, .password_required ul li
{
    margin-bottom: 8px;
    color:red;
    font-weight: 700;
}
.email_required ul li.active, .password_required ul li.active
{
    color: #02af02;
}
.email_required ul li span:before, .password_required ul li span:before
{
    content :'❌';
}
.email_required ul li.active span:before, .password_required ul li.active span:before
{
    content :'✅';
}
.close {
  position: relative;
  z-index: 9999; /* Adjust the value as needed, higher values appear above lower values */
  margin: 0.9rem 0 0 0;
}

</style>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
    
    <div class="center">
    <a href="index.php" class="color"><i class="close fa-solid fa-rectangle-xmark fa-2xl"></i></a>
        <h1>Login</h1>
        <form action="Login.php" method="post" autocomplete="">
            <?php
                if(count($errors) > 0) {
                    ?>
                    <div class="alert alert-danger text-center">
                        <?php
                        foreach($errors as $showerror) {
                            echo $showerror;
                        }
                        ?>
                    </div>
                    <?php
                }
            ?>

            <div class="txt_field">
                <input type="text" name="email" id="email_validation" required value="<?= htmlspecialchars($email ?: (string) ($_COOKIE['remember_email'] ?? '')) ?>">
                <span><i class="fa-solid fa-envelope fa-xl" id="mail"></i></span>
                <label>Email Address</label>
            </div>
            <div class="email_required">
                <ul>
                    <li class="email"><span></span>Valid Email Address</li>
                </ul>
            </div>  

            <div class="txt_field">
                <input type="password" name="password" id="password" required>
                <span><i class="fa-solid fa-lock fa-xl" id="lock"></i><i class="fa-sharp fa-regular fa-eye-slash fa-lg" id="eye"></i></span>
                <label>Password</label>
            </div>
            <div class="password_required">
                <ul>
                    <li class="characters"><span></span>At least 8 Characters</li>
                </ul>
            </div>  
            <div class="pass">
                <a href="Forgot_pwd.php">Forgot Password?</a>
            </div>
            <label class="remember_me">
                <input type="checkbox" name="remember"> Remember my email
            </label>

            <input type="submit" value="Login" name="login" id="login-btn" onclick="return validate();">

            <div class="signup_link">
                Not a member? <a href="Register.php">Sign up</a>
            </div>
        </form>
    </div>
</body>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
let name_flag = true;
let email_flag = true;
let password_flag = true;

const togglePassword = document.querySelector('#eye');
const password = document.querySelector('#password');

// Hide the eye icon initially
togglePassword.classList.add('hidden');

password.addEventListener('input', function (e) {
    // Show the eye icon if the password field has a value, otherwise hide it
    if (password.value) {
        togglePassword.classList.remove('hidden');
    } else {
        togglePassword.classList.add('hidden');
    }
});

togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
    // add or remove the fa-eye class depending on whether the eye slash icon is present
    if (this.classList.contains('fa-eye-slash')) {
        this.classList.remove('fa-eye');
    } else {
        this.classList.add('fa-eye');
    }
});

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

// password validation
$('#password').on('focus',function()
{
    $('.password_required').slideDown();
})
$('#password').on('blur',function()
{
    $('.password_required').slideUp();
})

$('#password').on('keyup',function()
{
    passValue = $(this).val();

    if(passValue.length == 8 || passValue.length > 8)
    {
        $('.characters').addClass('active');
        password_flag = true;
    }
    else
    {
        $('.characters').removeClass('active');
        password_flag = false;
    }

})

function validate()
{
    if(!email_flag || !password_flag)
    {
        return false;
    } 
    else
    {
        return true;
    }
}
</script>

</body>
</html>
