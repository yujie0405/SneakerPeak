<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
	border: 5px solid var(--color-surface);
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
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
	<section class="py-2 my-5">
		<div class="container">
			<h1 class="mb-5" style="text-align: center;">Profile</h1>
				<div class="bg-white shadow rounded-lg d-block d-sm-flex">
					<?php
						include SNEAKERPEAK_ROOT . '/resources/views/partials/side_bar.php';
					?>
					<div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
					<?php if ($message !== null) { ?>
                    <p role="status"><?= htmlspecialchars($message) ?></p>
                <?php } ?>
                <?php
                require SNEAKERPEAK_ROOT . ($page === 1
                    ? '/resources/views/account/Account_setting.php'
                    : '/resources/views/account/Password_setting.php');
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
</body>
</html>
