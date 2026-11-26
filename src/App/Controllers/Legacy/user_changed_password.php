<?php 
    require_once SNEAKERPEAK_ROOT . '/src/App/Controllers/Legacy/controllerUser.php'; 
?>
<?php
    if($_SESSION['info'] == false)
    {
        header('Location: Login.php');  
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}
body{
    margin: 0;
    padding: 0;
    background-image: url("background.png");
    background-position-x: -120px;
    background-position-y:  100px;
    height: 100vh;
    /* overflow: hidden; */
}
input[type="submit"]{
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
input[type="submit"]:hover{
  border-color: #2691d9;
  transition: .5s;
}
    </style>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form login-form">
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
                <form action="Login.php" method="POST">
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="login_for_Now" value="Login Now">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>