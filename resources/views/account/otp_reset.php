<?php 
    require_once SNEAKERPEAK_ROOT . '/Controller/AuthController.php';
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Code Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@700&family=Poppins:wght@400;500;600&display=swap');
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
            <div class="col-md-4 offset-md-4 form">
                <form action="otp_reset.php" method="POST" autocomplete="off">
                    <h2 class="text-center" style="color: white;">Code Verification</h2>
                    <?php 
                    if(isset($_SESSION['info']))
                    {
                        ?>
                        <div class="alert alert-success text-center" style="padding: 0.4rem 0.4rem">
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
                    <div class="center">
                      <div class="form-group">
                          <input class="form-control" type="number" name="otp" placeholder="Enter code" required>
                      </div>
                      <div class="form-group">
                          <input class="form-control button" type="submit" name="check-reset-otp" value="Submit">
                      </div>
                  </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>