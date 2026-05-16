<?php 
    require_once "controllerUser.php"; 
?>
<?php 
$email = $_SESSION['email'];
    if($email == false)
    {
        header('Location: Login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Document</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
}
.mainDiv 
{
    display: flex;
    min-height: 100%;
    align-items: center;
    justify-content: center;
    font-family: 'Open Sans', sans-serif;
}
 .cardStyle 
{
    width: 500px;
    border-color: white;
    background: whitesmoke;
    padding: 36px 0;
    border-radius: 10px;
    margin: 30px 0;
    box-shadow: 0px 0 2px 0 rgba(0,0,0,0.25);
}
#signupLogo 
{
    max-height: 100px;
    margin: auto;
    display: flex;
    flex-direction: column;
}
.formTitle
{
    font-weight: 600;
    margin-top: 20px;
    color: #2F2D3B;
    text-align: center;
}
.inputLabel 
{
    font-size: 14px;
    color: #555;
    margin-bottom: 6px;
    margin-top: 24px;
}
.inputDiv 
{
    width: 70%;
    display: flex;
    flex-direction: column;
    margin: auto;
   
}
input 
{
    height: 40px;
    font-size: 16px;
    border-radius: 4px;
    border: none;
    border: solid 1px #ccc;
    padding: 0 11px;
}
input:disabled 
{
    cursor: not-allowed;
    border: solid 1px #eee;
}
.buttonWrapper 
{
    margin-top: 40px;
}
.submitButton
{
    width: 70%;
    height: 40px;
    margin: auto;
    display: block;
    color: #fff;
    background-color: #065492;
    border-color: #065492;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.12);
    box-shadow: 0 2px 0 rgba(0, 0, 0, 0.035);
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
}
.submitButton:disabled, button[disabled] 
{
    border: 1px solid #cccccc;
    background-color: #cccccc;
    color: #666666;
}
#eye
{
    cursor: pointer;
    float:right;
    position: relative;
    right: 15px;
    top: -20px;
}
.hidden 
{
    display: none;
}
.password_required, .cpassword_required
{
    display:none;
}
.password_required ul, .cpassword_required ul
{
    padding: 10px 0 0 72px;
    margin: 0 0 15px;
    list-style: none;
}
.password_required ul li, .cpassword_required ul li
{
    margin-bottom: 8px;
    color:red;
    font-weight: 700;
}
.password_required ul li.active, .cpassword_required ul li.active
{
    color: #02af02;
}
.password_required ul li span:before, .cpassword_required ul li span:before
{
    content :'❌';
}
.password_required ul li.active span:before, .cpassword_required ul li.active span:before
{
    content :'✅';
}
</style>
</head>
<body>
    <div class="mainDiv">
      <div class="cardStyle">
        <form action="chg_pwd.php" method="post" autocomplete="off" name="signupForm" id="signupForm">
          
          <img src="" id="signupLogo"/>
          
          <h2 class="formTitle">
            Login to your account
          </h2>
          <?php 
                    if(isset($_SESSION['info']))
                    {
                        ?>
                        <div class="alert alert-success text-center">
                            <?php 
                                echo $_SESSION['info']; 
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if(count($errors) > 0)
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
                    ?>  
        <div class="inputDiv">
          <label class="inputLabel" for="password">New Password</label>
          <input type="password" name="password" id="password_validation" required>
          <span><i class="fa-sharp fa-regular fa-eye-slash fa-lg toggle-password" id="eye" data-target="password"></i></span>
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
          
        <div class="inputDiv">
          <label class="inputLabel" for="confirmPassword">Confirm Password</label>
          <input type="password" name="cpassword" id="cpassword_validation" required>
          <span><i class="fa-sharp fa-regular fa-eye-slash fa-lg toggle-password" id="eye" data-target="cpassword"></i></span>
        </div>
        <div class="cpassword_required">
                <ul>
                    <li class="match"><span></span>Passwords Match</li>
                <ul>
            </div>
        
        <div class="buttonWrapper">
        <button type="submit" id="submitButton" name="change-password" class="submitButton pure-button pure-button-primary" onclick="return validate();">
            <span>Continue</span>
          </button>
        </div>
          
      </form>
      </div>
    </div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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

    function validate() {
    if ($('.lowercase').hasClass('active') &&
        $('.capital').hasClass('active') &&
        $('.number').hasClass('active') &&
        $('.special').hasClass('active') &&
        $('.characters').hasClass('active') &&
        $('.match').hasClass('active')) {
        return true; // Allow form submission
    } else {
        return false; // Prevent form submission
    }
}
</script>
</body>
</html>