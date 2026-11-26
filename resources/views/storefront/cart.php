<!DOCTYPE html>
<html lang="en">
<head>
       <title>Shipping Cart</title>
       <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />    
    <link rel="stylesheet" href="css/headerandfooter.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">


    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <style>
        input[type=text]
        {
            padding: 5px;
        }
        input[type=submit].add
        {
            width: 50px;
            height: 40px;
        }
    </style>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
    <link rel="stylesheet" href="assets/css/cart.css?v=<?= filemtime(SNEAKERPEAK_ASSETS . '/css/cart.css') ?>">
</head>

<body>
<header>
<?php
		include SNEAKERPEAK_ROOT . '/resources/views/partials/header.php';

	?>
</header>
   
    <br>
    <br>
   <?php
     if (!isset($_SESSION["CustomerID"])) {
        ?>
        <script>
            Swal.fire({
                title: "It appears that you haven't logged in.",
                text: "Would you like to go to the login page?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Login",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                window.location.href = "Login.php";
                } else {
                window.history.back();
                }
            });
            </script>
        <?php
    }
    ?>
<script>
               async function changeQuantity(endpoint, button) {
                    const data = new URLSearchParams({
                        name: button.dataset.product,
                        size: button.dataset.size,
                        cart_token: <?= json_encode($token) ?>
                    });
                    const response = await fetch(endpoint, { method: 'POST', body: data });
                    if (response.ok) {
                        location.reload();
                    }
                }
             </script>
             
    <div class="infomation">
        <h2>Shopping Cart</h2>
    </div>
    <div class="cart-page" tabindex="0" role="region" aria-label="Shopping cart items">
    
            <table>
                <colgroup>
                    <col>
                    <col class="cart-size-column">
                    <col class="cart-quantity-column">
                    <col class="cart-subtotal-column">
                    <col class="cart-action-column">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col" class="cart-product-cell">Product</th>
                        <th scope="col">Size</th>
                        <th scope="col">Quantity</th>
                        <th scope="col" class="cart-subtotal-cell">Subtotal</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($items as $row) {
                            $amount = (float) $row['Current_Price'] * (int) $row['Select_qty'];
                    ?>
                    <tr>
                        
                        <td class="cart-product-cell">
                            <div class="cart-info">
                                <img src="prod/<?= rawurlencode($row['Prod_Img1']) ?>" alt="">
                                <div class="note">
                                    <p><?= htmlspecialchars($row['Product_Name']) ?></p>
                                    <small>Price: RM<?php echo number_format((float) $row['Current_Price'], 2); ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($row['Select_Size']) ?></td>
                        <td>
                            <div class="cart-quantity">
                            <button class="minus" type="button" aria-label="Decrease quantity" <?= (int) $row['Select_qty'] <= 1 ? 'disabled' : '' ?> data-product="<?= htmlspecialchars($row['Product_Name']) ?>" data-size="<?= htmlspecialchars($row['Select_Size']) ?>" onclick="changeQuantity('minus.php', this)">-</button>
                            
                            <input type="text" value="<?php echo $row['Select_qty']; ?>" aria-label="Quantity" readonly>
                            <button class="plush" type="button" aria-label="Increase quantity" data-product="<?= htmlspecialchars($row['Product_Name']) ?>" data-size="<?= htmlspecialchars($row['Select_Size']) ?>" onclick="changeQuantity('plush.php', this)">+</button>
                            </div>
                        </td>
                        <td class="amount cart-subtotal-cell">RM <?= number_format($amount, 2) ?></td>
                        <td>
                                <form method="post" onsubmit="return confirm('Remove this item from your cart?')">
                                    <input type="hidden" name="cart_token" value="<?= htmlspecialchars($token) ?>">
                                    <input type="hidden" name="size" value="<?= htmlspecialchars($row['Select_Size']) ?>">
                                    <button type="submit" name="remove" value="<?= htmlspecialchars($row['Product_Name']) ?>" aria-label="Remove item">
                                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                    </button>
                                </form>
                        </td>
                    </tr>
                    <?php
                        
                        }
                    ?>
                </tbody>
            </table>

            
                         
            </div>
        
                    <div class="total-price">
                        <table>
                            <tr>
                                <td>Subtotal</td>
                                <td class="total-subtotal">RM <?php echo number_format($quote['subtotal'], 2); ?></td>
                            </tr>
                            <tr>
                                <?php
                                             if ($message !== null) { ?>
                                    <td colspan="2" role="alert"><?= htmlspecialchars($message) ?></td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <td>Discount: <?= htmlspecialchars($promotion['Details'] ?? '') ?></td>
                                <td>RM <?= number_format($quote['discount'], 2) ?></td>
                            </tr>
                            <tr>
                            <td>Shipping Fee</td>
                            <td id="shipping-fee">RM <?php echo number_format($quote['shipping'], 2); ?></td>
                            </tr>
                            <tr class="cart-total-row">
                                <td>Total</td>
                                
                                <td id="total-amount">RM <?php echo number_format($quote['total'], 2); ?> </td>
                            </tr>
                            <tr>
                                <td>
                                    <hr>
                                </td>
                            </tr>
                            <tr>

                                <td><input type="text" form="cart-promotion" aria-label="Promotion code" name="Promote_Code" value="<?= htmlspecialchars($promoCode) ?>" placeholder="Enter The Promote Code"></td>
                                <td><input form="cart-promotion" type="hidden" name="cart_token" value="<?= htmlspecialchars($token) ?>"><input form="cart-promotion" type="submit" value="Add" class="add" name="applyPromo"></td>
                            </tr>
                        </table>
                        <form id="cart-promotion" method="post" action="cart.php"></form>
                    </div>
                    <form method="post" action="cart.php">
                    <div class="checkout">
                        <div class="buttoncheckout">
                            
                            <a href="menu.php">Back To Menu</a>
                            <input type="hidden" name="cart_token" value="<?= htmlspecialchars($token) ?>">
                            <input type="hidden" name="PC" value="<?= htmlspecialchars($promoCode) ?>">
                            <input type="submit" value="Proceed To Checkout" name="PTC" <?= !$items ? 'disabled' : '' ?>>
                        </div>
                        
                        
                    </div>
        </form>



<footer>
    <P></P>
<P></P>
<P></P>
<P></P>
<P></P>
<?php
		include SNEAKERPEAK_ROOT . '/resources/views/partials/footer.php';
	?>
    
</footer>
<script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/scrollax.min.js"></script>
  <?php if ($mapsKey = sneakerpeakSetting('MAPS_BROWSER_KEY')) { ?><script src="https://maps.googleapis.com/maps/api/js?key=<?= rawurlencode($mapsKey) ?>&sensor=false"></script><?php } ?>
  <?php if (!empty($mapsKey)) { ?><script src="js/google-map.js"></script><?php } ?>
  <script src="js/main.js"></script>
</body>
</html>
