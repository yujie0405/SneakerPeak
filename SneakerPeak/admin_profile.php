<?php
	session_start();

    include ("database/Database_Connection.php");

    $admin_id = $_SESSION["AdminID"];  
    $admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admin WHERE id = $admin_id")); 
?>
<?php
      $admin_id = $_SESSION["AdminID"]; 
      $sql="select * from admin where id = '$admin_id'";
      $search_user = mysqli_query($conn, $sql);// search for that particular admin
      $row_user = mysqli_fetch_assoc($search_user);

      $admin_name  = $row_user['name'];
      $admin_email = $row_user['email'];
      $admin_password = $row_user['password']; 
      $admin_phone = $row_user['Admin_ContactNumber'];
      $admin_id    = $row_user['id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Account Settings UI Design</title>
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
	@import url("https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap");
body {
	background: #f9f9f9;
	font-family: "Roboto", sans-serif;
}

.shadow {
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
}

.profile-tab-nav {
	min-width: 250px;
}

.tab-content {
	flex: 1;
}

.form-group {
	margin-bottom: 1.5rem;
}

.nav-pills a.nav-link {
	padding: 15px 20px;
	border-bottom: 1px solid #ddd;
	border-radius: 0;
	color: #333;
}
.nav-pills a.nav-link i {
	width: 20px;
}

.img-circle img {
	height: 100px;
	width: 100px;
	border-radius: 100%;
	border: 5px solid #fff;
}
.upload
{
  width: 125px;
  position: relative;
  margin: auto;
}

.upload img{
  border-radius: 50%;
  border: 8px solid #DCDCDC;
}

.upload .round{
  position: absolute;
  bottom: 0;
  right: 0;
  background: #00B4FF;
  width: 32px;
  height: 32px;
  line-height: 33px;
  text-align: center;
  border-radius: 50%;
  overflow: hidden;
}

.upload .round input[type = "file"]{
  position: absolute;
  transform: scale(2);
  opacity: 0;
}

input[type=file]::-webkit-file-upload-button{
    cursor: pointer;
}

.account
{
    border: none;
    background:transparent; 
    display: block;
    padding:0.6rem;
    padding-left:3rem;
    padding-right:7.3rem;
    color: black;
}

.password
{
    border:none;
    background:transparent; 
    display: block;
    padding:0.6rem;
    padding-left:3rem;
    padding-right:6.7rem;
    color: black;
}
.logout
{
    border:none; 
    background:transparent; 
    display: block;
    padding:0.6rem;
    padding-left:3rem;
    padding-right:7.7rem;
    color: black;
}

.distance
{
    padding-right: 0.7rem;
}
.distance1
{
    padding-right: 1.1rem;
}

.active-button {
    background-color: #ddd;
}
.p_name
{
    margin-top:15px;
}
.active-button {
    background-color: #007bff;
    color: white;
}

.active-button-borderless {
    border: none;
    outline: none;
}
</style>
</head>
<body>
	<section class="py-2 my-5">
		<div class="container">
			<h1 class="mb-5" style="text-align: center;">Profile</h1>
				<div class="bg-white shadow rounded-lg d-block d-sm-flex">
					<?php
						include("admin_profile_sidebar.php");
					?>
					<div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
					<?php 
                if(isset($_POST["page"]))
                {
                    $_SESSION["page"]=$_POST["page"];
                }
                if(isset($_SESSION["page"]))
                {
                    if($_SESSION["page"]==1)
                    {
                        include("admin_account_setting.php");
                    }
                    else
                    if($_SESSION["page"]==2)
                    {
                        include("admin_password_setting.php");
                    }
                }
                ?>

				</div>
			</div>
		</div>
	</section>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
     function highlightButton(button) {
    document.getElementById("password-tab").classList.remove("active-button-borderless");
    document.getElementById("password-tab").classList.add("active-button");
    document.getElementById("account-tab").classList.remove("active-button");
    document.getElementById("account-tab").classList.add("account active-button-borderless");
     }
</script>
<?php

// Check if the form has been submitted
if (isset($_POST['update'])) {
    $admin_name = $_POST["admin_name"];
    $admin_number = $_POST["admin_number"];
    $sql = "UPDATE `admin` SET `name` = '$admin_name', `Admin_ContactNumber` = '$admin_number' WHERE `admin`.`id` = '$admin_id'";
    if (mysqli_query($conn, $sql)) {
        ?>
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Your information has been updated',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location = "admin_profile.php";
            });
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("System Error");
            window.location = "admin_profile.php";
        </script>
        <?php
    }
}

?>
</body>
</html>