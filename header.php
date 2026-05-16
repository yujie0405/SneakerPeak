<?php
include('database/Database_Connection.php');

if(!isset($_SESSION["CustomerID"]))
{
    $totalProducts = 0;
}
else
{
    $UID=$_SESSION["CustomerID"] ; 
    $cal = "SELECT COUNT(*) AS total_products FROM cart WHERE UID = $UID";
    $Ans= mysqli_query($conn, $cal);
    $rowr = mysqli_fetch_assoc($Ans);
    $totalProducts = $rowr['total_products'];
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Header</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
</head>
<body>
    <div class="py-1 bg-black">
        <div class="container">
            <div class="row no-gutters d-flex align-items-start align-items-center px-md-0">
                <div class="col-lg-12 d-block">
                    <div class="row d-flex">
                        <div class="col-md pr-4 d-flex topper align-items-center">
                            <div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-phone2"></span></div>
                            <span class="text">+606 - 252 3443</span>
                        </div>
                        <div class="col-md pr-4 d-flex topper align-items-center">
                            <div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-paper-plane"></span></div>
                            <span class="text">SneakerPeak2023@gmail.com</span>
                        </div>
                        <div class="col-md-4 pr-4 d-flex topper align-items-center text-lg-right">
                            <span class="text">3-5 Business days delivery </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">Sneaker Peak</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Catalog</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown04">
                        <a class="dropdown-item" href="menu.php">Shop</a>
                        <a class="dropdown-item" href="sizecar.php">Size Chart</a>
                        <a class="dropdown-item" href="cart.php">Cart</a>
                        
                    </div>
                </li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                <li class="nav-item"><a href="wishlist.php" class="nav-link">WishList</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                <li class="nav-item cta cta-colored"><a href="cart.php" class="nav-link"><span class="icon-shopping_cart"></span>[<?php echo $totalProducts ?>]</a></li>
            </ul>
        </div>
    </div>
    <ul class="navbar-nav ml-auto">
    <?php if (isset($_SESSION['name'])) { ?>
        <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-circle-user fa-2xl" style="color: #000000; margin-right: 50px; font-size: 12px;">
                    <?php echo $_SESSION['name']; ?>
                </i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdown04">
                <a class="dropdown-item" href="profile_1.php">Profile</a>
                <a class="dropdown-item" href="OrderHistory.php">Order History</a>
                <a class="dropdown-item" href="#" onclick="confirmLogout()">Logout</a>
            </div>
        </li>
    <?php } else { ?>
        <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-circle-user fa-2xl" style="color: #000000; margin-right: 80px; font-size: 18px;"></i>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdown04">
                <a class="dropdown-item" href="Login.php">Login</a>
                <a class="dropdown-item" href="Register.php">Register</a>
            </div>
        </li>
    <?php } ?>
</ul>

</nav>
    <!-- END nav -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout() 
      {
		Swal.fire(
        {
			title: 'Are you sure?',
			text: "You will be logged out.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, logout!'
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = "Logout.php";
			}
		});
	}   
</script>
</body>
</html>