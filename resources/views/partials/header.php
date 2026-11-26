<?php

require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
require_once SNEAKERPEAK_ROOT . '/src/App/Models/CartModel.php';

$customerId = $_SESSION['CustomerID'] ?? null;
$totalProducts = $customerId === null ? 0 : \SneakerPeak\App\Models\CartModel::count(
    \SneakerPeak\Core\Database::getInstance()->getConnection(), (string) $customerId
);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <li class="nav-item dropdown storefront-dropdown" data-storefront-dropdown>
                    <button type="button" class="nav-link dropdown-toggle" id="catalog-dropdown-trigger" aria-controls="catalog-dropdown-menu" aria-expanded="false">Catalog</button>
                    <div class="dropdown-menu" id="catalog-dropdown-menu" aria-labelledby="catalog-dropdown-trigger" hidden>
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
    <ul class="navbar-nav ml-auto storefront-account">
        <li class="nav-item dropdown storefront-dropdown" data-storefront-dropdown>
            <button type="button" class="nav-link" id="account-dropdown-trigger" aria-label="Account" aria-controls="account-dropdown-menu" aria-expanded="false">
                <i class="fa-solid fa-circle-user" aria-hidden="true"></i>
                <?php if ($customerId !== null) { ?>
                    <span><?= htmlspecialchars((string) ($_SESSION['name'] ?? 'Account')) ?></span>
                <?php } ?>
            </button>
            <div class="dropdown-menu dropdown-menu-right" id="account-dropdown-menu" aria-labelledby="account-dropdown-trigger" hidden>
                <?php if ($customerId !== null) { ?>
                    <a class="dropdown-item" href="profile_1.php">Profile</a>
                    <a class="dropdown-item" href="OrderHistory.php">Order History</a>
                    <button type="button" class="dropdown-item" onclick="confirmLogout()">Logout</button>
                <?php } else { ?>
                    <a class="dropdown-item" href="Login.php">Login</a>
                    <a class="dropdown-item" href="Register.php">Register</a>
                <?php } ?>
            </div>
        </li>
    </ul>
    </nav>
    <script src="assets/js/storefront-dropdowns.js" defer></script>
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
