<?php
    include("database/config.php"); 
    session_start(); 
    $UID = $_SESSION["CustomerID"] ?? null;
   

    $query = "SELECT * FROM cart WHERE UID ='$UID'";
    $result = mysqli_query($conn, $query);

    // Initialize total amount and subtotal
   
    $promoCode="";
    $Ordercode = generateUniqueNumber($conn);
    $_SESSION['Ordercode'] = $Ordercode;
    $todaydate = date('d/m/Y');
   
    
       
    function generateUniqueNumber($conn) {
        do {
            $number = mt_rand(100000, 999999); // Generate a random 6-digit number
            $query = "SELECT * FROM `Oder` WHERE Order_Code = '$number'";
            $result = mysqli_query($conn, $query);
    
            if ($result) {
                $numberExists = mysqli_num_rows($result) > 0;
            } else {
                // Handle the case when the query execution fails
                echo "Error executing the query: " . mysqli_error($conn);
                break;
            }
        } while ($numberExists);
    
        return $number;
    }

    $subtotal = 0;
    
    $shippingFee = 20;
    $discount=0;
    $promoDetails="";
    $total = $subtotal + ($subtotal >= 500 ? 0 : $shippingFee);
    $per=0;
    if(isset($_POST['PTC'])) {
        // Check if the cart is empty
        $cartCheckQuery = "SELECT COUNT(*) AS total FROM cart WHERE UID = '$UID'";
        $cartCheckResult = mysqli_query($conn, $cartCheckQuery);
        $cartCheckData = mysqli_fetch_assoc($cartCheckResult);
        $cartCount = $cartCheckData['total'];
        
        if($cartCount == 0) {
            // Cart is empty, show an error message or redirect to the cart page
            echo '<script>';
            echo 'alert("Empty Cart\n\nYour cart is empty. Please add items to your cart before proceeding to checkout.");';
            echo '</script>';
            // You can also redirect the user to the cart page using header() function
            // header('location: cart.php');
        } else {
            // Proceed with the checkout process
           $pc=$_POST['PC'];
            $oDer = 'Pending';
            $payment = 'unset';
            $Status = 'Paying';
            $t = $_POST['total'];
            $s = $_POST['shipping'];
            
            $pcCheckQuery = "SELECT COUNT(*) AS total FROM oder WHERE Promote_Code = '$pc'";
            $pcCheckResult = mysqli_query($conn, $pcCheckQuery);
            $pcCheckData = mysqli_fetch_assoc($pcCheckResult);
            $pcCount = $pcCheckData['total'];
        
            if ($pcCount > 0) {
                $pc = null;
            }
            $ask=0;
            // Insert the values into the database
            $query = "INSERT INTO oder (UID,Order_Code, Order_Date,Order_TotalAmmount,ShippingFeed,Payment_Type, Payment_Status,Order_Status,Promote_Code,ask) VALUES ('$UID','$Ordercode', '$todaydate',' $t ',' $s','$payment', '$Status','$oDer','$pc','$ask')";
            mysqli_query($conn, $query);
            $cartQuery = "SELECT * FROM cart WHERE UID='$UID'";
            $cartResult = mysqli_query($conn, $cartQuery);
        
            while ($cartRow = mysqli_fetch_assoc($cartResult)) {
                $productName = $cartRow['Product_Name'];
                $productPrice = $cartRow['Prod_Price'];
                $productSize = $cartRow['Select_Size'];
                $productQty = $cartRow['Select_qty'];
        
                // Insert each cart item into the "Order_Details" table
                $orderDetailsQuery = "INSERT INTO oder_details (UID,Order_Code, Order_Product,Order_ProdcutP, Order_Size, Order_qty,Order_Day) VALUES ('$UID','$Ordercode', '$productName','$productPrice' ,'$productSize', '$productQty','$todaydate')";
                mysqli_query($conn, $orderDetailsQuery);
            }
            header('location: selectaddress.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
       <title>Shipping Cart</title>
       <link rel="stylesheet" href="css/cart.css">
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

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">


    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <meta name="vieport" content="width=device-width,initial-scale=1" />
    <style>
        input[type=text]
        {
            padding: 5px;
        }
        input[type=submit].add
        {
            width: 50px;
            height: 40px;
            background: #0b0b49;
            color: white;
        }
        input[type=submit].add:hover
        {
            background-color: rgb(224, 224, 224);
            color: #0b0b49;
            
        }
    </style>
</head>

<header>
<?php
		include("header.php");

	?>
</header>

<body>
   
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
    if (isset($_GET['delete']) && isset($_GET['size'])) {
        $productToDelete = $_GET['delete'];
        $selectedSize = $_GET['size'];
        
        $query = "DELETE FROM cart WHERE Product_Name = '$productToDelete' AND Select_Size = '$selectedSize' AND UID='$UID'";
        mysqli_query($conn, $query);
        ?>
             <script>
    Swal.fire({
        title: "Are you sure you want to delete this item?",
        text: "This action cannot be undone.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the delete action
            window.location.href = `delete.php?delete=${encodeURIComponent('$productToDelete')}&size=${encodeURIComponent('$selectedSize')}`;
            window.location.href = "cart.php";
        } else {
            // Cancelled, do nothing
            // You can redirect to a different page if needed
        }
    });
    </script>

<?php
        
    }

 ?>
<script>
                function plush(name, size) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", "plush.php?name=" + name + "&size=" + size, true);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // handle the response from the PHP script
                            // reload the page to show the updated list of items
                            location.reload();

                        }
                    };
                    xhr.send();
                }
                function minus(name,size)
                {
                    var xhr = new XMLHttpRequest();
                                        xhr.open("GET", "minus.php?name=" + name + "&size=" + size, true);
                                        xhr.onreadystatechange = function() {
                                            if (xhr.readyState === 4 && xhr.status === 200) {
                                                // handle the response from the PHP script
                                               
                                                // reload the page to show the updated list of items
                                                location.reload();
                                            }
                                        };
                                        xhr.send();
                }
             </script>
             
    <div class="infomation">
        <h2>Shopping Cart</h2>
    </div>
    <div class="cart-page">
    
            <table>
                <thead>
                    <tr>
                        
                        <th colspan="2">Product</th>
                        <th>Size</th>
                        <th>Quantity</th> <!-- New table head -->
                        <th>Subtotal</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            $productname=$row['Product_Name'];
                            $prodSize=$row['Select_Size'];
                            $prodstatusquery="SELECT Prod_Status FROM product WHERE Prod_Name='$productname'";
                            $chec=mysqli_query($conn,$prodstatusquery);
                            $checks=mysqli_fetch_assoc($chec);

                            $checksizestatus="SELECT Qty_status FROM sizeandqty WHERE Prod_Name='$productname'AND Prod_Size='$prodSize'";
                            $checknow=mysqli_query($conn,$checksizestatus);
                            $do=mysqli_fetch_assoc($checknow);
                            if($checks['Prod_Status']=='Deleted')
                            {
                                $deleteProd_Status="DELETE FROM cart WHERE Product_Name IN(SELECT Prod_Name FROM product WHERE Prod_Name='$productname' AND Prod_Status='Deleted' )";
                                $rundelete=mysqli_query($conn, $deleteProd_Status);
                                ?>
                                 <script>
                                    Swal.fire({
                                        title: "Item Deleted",
                                        text: "The item  has been deleted.",
                                        icon: "info",
                                        showConfirmButton: false,
                                        timer: 3000
                                    }).then(() => {
                                        window.location.href = "cart.php";
                                    });
                                    </script>
                                <?php
                            }
                            else if($do['Qty_status'] =='UnAvailable')
                            {
                                $deleteProd="DELETE FROM cart WHERE Product_Name IN(SELECT Prod_Name FROM sizeandqty WHERE Prod_Name='$productname' AND Qty_status='UnAvailable' )";
                                $rundel=mysqli_query($conn,$deleteProd);
                                ?>
                                <script>
                                Swal.fire({
                                    title: "Item Deleted",
                                    text: "The item  is no longer available.",
                                    icon: "info",
                                    showConfirmButton: false,
                                    timer: 3000
                                }).then(() => {
                                    window.location.href = "cart.php";
                                });
                                </script>
                                <?php
                            }
                            if($row['Prod_Price']>0)
                            {
                                $amount = $row['Prod_Price'] * $row['Select_qty'];

                            }
                            else
                            {
                                $amount=0;
                            }
                            
                    ?>
                    <tr>
                        
                        <td colspan="2">
                            <div class="cart-info">
                                <img src="prod/<?php echo $row['Prod_Img1']; ?>">
                                <div class="note">
                                    <p><?php echo $row['Product_Name']; ?></p>
                                    <small>Price: RM<?php echo number_format(  $row['Prod_Price'],2); ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?php  echo $row ['Select_Size']; ?></td>
                        <td> 
                            <button class="minus" onclick="minus('<?php echo $row['Product_Name'] ?>', '<?php echo $row['Select_Size'] ?>')">-</button>
                            
                            <input type="text" value="<?php echo $row['Select_qty']; ?>" min="1" readonly>
                            <button class="plush" onclick="plush('<?php echo $row['Product_Name'] ?>', '<?php echo $row['Select_Size'] ?>')">+</button>
                        </td>
                        <td class="amount"><?php echo number_format(  $amount,2); ?></td>
                        <td>
                                <a href="cart.php?delete=<?php echo $row['Product_Name']; ?>&size=<?php echo $row['Select_Size']; ?>" onclick="return showDeleteConfirmation();" >
                                    <i class="fas fa-trash-alt" title="Delete item"></i>
                                </a>
                                <script>
                                    function showDeleteConfirmation() {
                                        Swal.fire({
                                            title: "Are you sure you want to delete this item?",
                                            text: "This action cannot be undone.",
                                            icon: "question",
                                            showCancelButton: true,
                                            confirmButtonText: "Yes, delete it",
                                            cancelButtonText: "Cancel",
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = "cart.php?delete=<?php echo $row['Product_Name']; ?>&size=<?php echo $row['Select_Size']; ?>";
                                                return true;
                                            } else {
                                                // Cancelled, do not perform the deletion
                                                return false;
                                            }
                                        });
                                    }
                                    </script>
                        </td>
                    </tr>
                    <?php
                        $subtotal+=$amount;
                        $total=0;
                        $total=$subtotal + ($subtotal >= 500 ? 0 : $shippingFee);
                        }
                    ?>
                </tbody>
            </table>

            
                         
            </div>
        
                    <div class="total-price">
                        <table>
                            <tr>
                                <td>Subtotal</td>
                                <td class="total-subtotal">RM <?php echo number_format(  $subtotal,2); ?></td>
                            </tr>
                            <tr>
                                <?php
                                             if (isset($_POST['applyPromo'])) {
                                        $promoCode = $_POST['Promote_Code'];

                                        // Query the database to check if the promotion code exists
                                        $promoQuery = "SELECT * FROM promote_code WHERE Promote_Code = '$promoCode'";
                                        $promoResult = mysqli_query($conn, $promoQuery);

                                        if (mysqli_num_rows($promoResult) > 0) {
                                            // Promotion code exists, fetch the discount value and type
                                            $promoData = mysqli_fetch_assoc($promoResult);
                                            $discount = $promoData['Value'];
                                            $promoType = $promoData['Promote_type'];
                                            $promoDetails = $promoData['Details'];

                                            // Check the status of the promotion code
                                            $promoStatus = $promoData['PromoteCode_Status'];

                                            if ($promoStatus == 'Available') {
                                                // Check if the promotion code has been used by the user
                                                $codeUsedQuery = "SELECT * FROM oder WHERE UID = '$UID' AND Promote_Code = '$promoCode'";
                                                $codeUsedResult = mysqli_query($conn, $codeUsedQuery);

                                                if (mysqli_num_rows($codeUsedResult) > 0) {
                                                    // Promotion code has already been used by the user
                                                    ?>
                                                    <script>
                                                        Swal.fire({
                                                            title: "Promotion code has already been used",
                                                            icon: "error",
                                                            timer: 3000,
                                                            timerProgressBar: true,
                                                            showConfirmButton: false
                                                        });
                                                    </script>
                                                    <?php
                                                } else {
                                                    // Calculate the discounted total based on the promotion type
                                                    if ($promoType == "Value Off") {
                                                        $discountedTotal = $total - $discount;
                                                    } elseif ($promoType == "Percentage Off") {
                                                        $per = $subtotal * ($discount / 100);
                                                        $discountedTotal = $total - $per;
                                                    } elseif ($promoType == "Free Shipping") {
                                                        $totalship = $subtotal + ($shippingFee = 0);
                                                        $discountedTotal = $totalship;
                                                        // Set the Free Shipping flag to 0 (or any desired value)
                                                        $freeShipping = 0;
                                                    }

                                                    // Update the total value for further processing
                                                    $total = $discountedTotal;

                                                    // Store the promotion code in the database for the user

                                                }
                                            } else {
                                                // Promotion code has expired
                                                ?>
                                                <script>
                                                    Swal.fire({
                                                        title: "The promotion code has expired",
                                                        icon: "error",
                                                        timer: 3000,
                                                        timerProgressBar: true,
                                                        showConfirmButton: false
                                                    });
                                                </script>
                                                <?php
                                            }
                                        } else {
                                            // Promotion code does not exist
                                            ?>
                                            <script>
                                                Swal.fire({
                                                    title: "The promotion code you entered does not exist",
                                                    icon: "error",
                                                    timer: 3000,
                                                    timerProgressBar: true,
                                                    showConfirmButton: false
                                                });
                                            </script>
                                            <?php
                                        }
                                    }

                                ?>
                            <tr>
                                <td>Discount:<?php echo  $promoDetails?></td>
                                <?php if( $promoType="Percentage Off") {?>
                                <td>RM <?php echo number_format(  $per,2); ?></td>
                                <?php }else{ ?>
                                    <td>RM <?php echo number_format(  $discount,2); ?></td>
                                   <?php }?> 
                            </tr>


                            <td>Shipping Fee</td>
                            <td id="shipping-fee">RM <?php echo $subtotal >= 500 ? "0.00" : $shippingFee.".00"; ?></td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                
                                <td id="total-amount">RM <?php echo number_format(  $total,2); ?> </td>
                            </tr>
                            <tr>
                                <td>
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                            <form method="post" action="cart.php">
                                <td><input type="text" name="Promote_Code" placeholder="Enter The Promote Code"></td>
                                <td><input type="submit" value="Add" class="add" name="applyPromo"></td>
                            </form>
                            </tr>
                        </table>
            <form method="post" action="">               
                    </div>
                    <div class="checkout">
                        <div class="buttoncheckout">
                            
                            <a href="menu.php">Back To Menu</a>
                            <input type="hidden" name="PC" value="<?php echo $promoCode ?>">
                            <input type="hidden" name="shipping" value="<?php echo $subtotal >= 500 ? "0.00" : $shippingFee.".00"; ?>">
                            <input type="hidden" name="total" value="<?= $total ; ?>">
                            <input type="submit" value="Proceed To Checkout" name="PTC">
                        </div>
                        
                        
                    </div>
        </form>
</body>


<footer>
    <P></P>
<P></P>
<P></P>
<P></P>
<P></P>
<?php
		include("footer.php");
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
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
</html>