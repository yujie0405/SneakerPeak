<?php
	session_start();
    ob_start();

    include ("database/Database_Connection.php");

    $cus_id = $_SESSION["CustomerID"];  
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM customer WHERE id = $cus_id")); 
?>
<?php
      $cus_id = $_SESSION["CustomerID"];
      $sql="select * from customer where id = '$cus_id'";
      $search_user = mysqli_query($conn, $sql);// search for that particular user
      $row_user = mysqli_fetch_assoc($search_user);

      $cus_name  = $row_user['name'];
      $cus_email = $row_user['email'];
      $cus_password = $row_user['password'];
      $cus_address = $row_user['Customer_Address'];
	  $cus_address2 = $row_user['Customer_Address_2'];
      $cus_phone = $row_user['Customer_ContractNumber'];
      $cus_id    = $row_user['id'];

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
						include("side_bar.php");
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
                        include("Account_setting.php");
                    }
                    else
                    if($_SESSION["page"]==2)
                    {
                        include("Password_setting.php");
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
    $cus_name = $_POST["cus_name"];
    $cus_address = $_POST["cus_address"];
    $cus_address2 = $_POST["cus_address_2"];
    $cus_number = $_POST["cus_number"];
    $sql = "UPDATE `customer` SET `name` = '$cus_name', `Customer_Address` = '$cus_address', `Customer_Address_2` = '$cus_address2', `Customer_ContractNumber` = '$cus_number' WHERE `customer`.`id` = '$cus_id'";
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
                window.location = "profile_1.php";
            });
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("System Error");
            window.location = "profile_1.php";
        </script>
        <?php
    }
}

?>
</body>
</html>