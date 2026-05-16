<?php
    include('database/config.php');
    session_start();
    $Ordercode=$_SESSION['Ordercode'];
    $method=$_SESSION['payment_method'];

    
    $query="SELECT * FROM oder WHERE Order_Code='$Ordercode'";
    $result=mysqli_query($conn,$query);
    
    $row = mysqli_fetch_assoc($result);
        
        
    $total = $row['Order_TotalAmmount'];
    $ship =$row['ShippingFeed'];

    $all=$total+$ship;
    $Paymentmethod="Complete";

    $rei = mysqli_query($conn, "SELECT email FROM customer WHERE id IN(SELECT UID FROM oder WHERE Order_code='$Ordercode') ");
    $ri = mysqli_fetch_assoc($rei);
    $email = $ri['email'];

    function sendPaymentEmail($recipientEmail) {
      $subject = 'Payment Successful';
      $message = "Dear Customer, your payment has been successfully processed.\n\nBest regards,\nSneakerPeak ";
      $headers = 'From: 1211202710@student.mmu.edu.my'; // Replace with your email address or a valid sender email address
  
      // Send the email
      mail($recipientEmail, $subject, $message, $headers);
  }
  function updateProductQuantity($Ordercode, $conn) {
    // Retrieve the order details
    $orderDetailsQuery = "SELECT * FROM `oder_details` WHERE Order_code = '$Ordercode'";
    $orderDetailsResult = mysqli_query($conn, $orderDetailsQuery);

    // Update the product quantity
    while ($rlw = mysqli_fetch_assoc($orderDetailsResult)) {
        $product = $rlw['Order_Product'];
        $size = $rlw['Order_Size'];
        $qty = $rlw['Order_qty'];

        // Retrieve the current product quantity from the product table
        $productQuery = "SELECT Prod_Qty FROM sizeandqty WHERE Prod_Name = '$product' AND Prod_Size ='$size'";
        $productResult = mysqli_query($conn, $productQuery);
        $productRow = mysqli_fetch_assoc($productResult);
        $currentQty = $productRow['Prod_Qty'];

        // Update the product quantity for the specific size
        if ($currentQty >= $qty) {
            $updatedQty = $currentQty - $qty;

            // Update the product quantity in the product table
            $updateQuery = "UPDATE sizeandqty SET Prod_Qty = '$updatedQty' WHERE Prod_Name = '$product' AND Prod_Size = '$size'";
            mysqli_query($conn, $updateQuery);
        }
    }
}

if (isset($_POST['Checkout'])) {
    $cardHolderName = $_POST['chn'];
    $cardNumber = $_POST['card_number'];
    $expiryDate = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Validate the card number
    $cardNumber = str_replace(' ', '', $cardNumber); // Remove spaces from the card number
    $isValidCardNumber = preg_match('/^\d{16}$/', $cardNumber);

    // Validate the expiry date
    function validateExpirationDate($date) {
        $currentYear = date('y');
        $currentMonth = date('m');

        // Extract month and year from the input date
        list($month, $year) = explode('/', $date);

        // Convert month and year to integers
        $month = intval($month);
        $year = intval($year);

        // Check if the expiration year is in the future
        if ($year < $currentYear) {
            return false;
        }

        // Check if the expiration year is the current year
        if ($year == $currentYear) {
            // Check if the expiration month is in the past or greater than 12
            if ($month < $currentMonth || $month > 12) {
                return false;
            }
        } else if ($month > 12) { // Check if the expiration month is greater than 12 for years beyond the current year
            return false;
        }

        // Expiration date is valid
        return true;
    }

    $isValidExpiryDate = validateExpirationDate($expiryDate);

    // Check if card validation passes
    if ($isValidCardNumber && $isValidExpiryDate) {
        // Proceed with the rest of the code

        $updateQuery = "UPDATE `oder` SET Payment_Status='$Paymentmethod' WHERE Order_Code='$Ordercode'";
        $sql = "DELETE FROM cart WHERE Product_Name IN (SELECT Order_Product FROM oder_details WHERE Order_Code ='$Ordercode')";
        mysqli_query($conn, $updateQuery);
        mysqli_query($conn, $sql);

        // Send email notification to the user
        $recipientEmail = $email; // Replace with the recipient's email address
        sendPaymentEmail($recipientEmail);
        updateProductQuantity($Ordercode, $conn);
        header('location:invoice.php');
    } else {
        // Invalid card payment, display an error message
        echo "<script>
          alert('Please check your card details.');
        </script>";
    }
} else if (isset($_POST['Submit-QR'])) {
      $qr = $_POST['invoice'];
      if ($qr == "") {
          // Display a SweetAlert to notify the user to upload the receipt
          echo "<script>
              alert('Please upload the receipt before submitting.');
          </script>";
      } else {
          $updateQuery = "UPDATE `oder` SET Payment_Status='$Paymentmethod' WHERE Order_Code='$Ordercode'";
          $sql = "DELETE FROM cart WHERE Product_Name IN (SELECT Order_Product FROM oder_details WHERE Order_Code ='$Ordercode')";
          mysqli_query($conn, $updateQuery);
          mysqli_query($conn, $sql);
          
          // Send email notification to the user
          $recipientEmail = $email; // Replace with the recipient's email address
          sendPaymentEmail($recipientEmail);
          updateProductQuantity($Ordercode, $conn);
          echo "Payment Successful";
          header('location:invoice.php');
      }
  }
  
?>  

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Main Page</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <link rel="stylesheet" href="css/headerandfooter.css">
        <link rel="stylesheet" href="css/payment.css">
        <meta name="vieport" content="width=device-width,initial-scale=1" />
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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://js.stripe.com/v3/"></script>

        <link rel="stylesheet" href="css/flaticon.css">
        <link rel="stylesheet" href="css/icomoon.css">
        <link rel="stylesheet" href="css/style.css">
        <meta name="vieport" content="width=device-width,initial-scale=1" />

        <!-- SweetAlert CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">

        <!-- SweetAlert JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>

        <style>
            .formbox input[type="number"] {
                padding: 6px;
                font-size: 17px;
                border: 1px solid rgb(208, 208, 208);
                border: 1px solid rgb(208, 208, 208);
                width: 98%;
                height: 40px;
                background: #dddddd;
      }

      /* Hide number input arrow buttons */
      .form-group input[type="number"]::-webkit-inner-spin-button,
      .form-group input[type="number"]::-webkit-outer-spin-button {
          -webkit-appearance: none;
          margin: 0;
      }
      span
      {
        color: red;
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
        <br>
        <div class="Payment">
            <div class="boxwhite">
          
                <div class="con"> 
                <div class="feed" style="width:300px; display:flex; padding-top:30px;padding-left:20px;">
                            <h3>TOTAL AMOUNT:</h3>
                            <h3>RM <span style="color:red;"> <?php echo number_format($all, 2); ?></span></h3>
                            
                            </div>
                    <div class="formdisplay">
                   
                        <div class="formbox">

                          
                          <?php
                            if($method == "Card_Payment")
                            {
                                include("card.php");     
                            }
                            else if($method == "QR_Payment")
                            {
                                include("QR.php");
                            }
                            $row = mysqli_fetch_assoc($result) ;
                            
                          ?>

                          
                        </div>
                        
                    </div>
                    
                </div>

                
             
           
            
            </div>
           
        </div>
        <script>
         function formatCardNumber() {
            const input = document.getElementById('card_number');
            const value = input.value.replace(/\D/g, '');
            const formattedValue = value.replace(/^(.{0,16})(.{0,4})$/, function(_, firstPart, secondPart) {
                return (firstPart ? firstPart + ' ' : '') + secondPart;
            });
            input.value = formattedValue;

            const errorElement = document.getElementById('ErrorCardNum');
            if (value.length !== 16 && value.length !== 19) {
                errorElement.textContent = 'Card number must be 16 or 19 digits';
            } else {
                errorElement.textContent = '';
            }
        }

        function validateCVV() {
            const input = document.getElementById('cvv');
            const value = input.value.replace(/\D/g, '');
            const maxDigits = 3;
            if (value.length > maxDigits) {
                input.value = value.slice(0, maxDigits);
            }
        }
        </script>
        <script>

   

        function formatExpiryDate() {
        const input = document.getElementById('expiry_date');
        const value = input.value.replace(/\D/g, '');
        const formattedValue = value.replace(/^(.{2})(.*)$/, '$1/$2');
        input.value = formattedValue;

        // Validate the expiry date
        const today = new Date();
        const currentYear = today.getFullYear() % 100;
        const currentMonth = today.getMonth() + 1;
        
        if (value.length === 4) {
            const inputMonth = parseInt(value.substring(0, 2));
            const inputYear = parseInt(value.substring(2, 4));

            if (inputYear < currentYear || (inputYear === currentYear && inputMonth < currentMonth)) {
            input.setCustomValidity('Card has expired');
            } else {
            input.setCustomValidity('');
            }
        } else {
            input.setCustomValidity('');
        }
        }

    function validateCVV() {
        const input = document.getElementById('cvv');
        const value = input.value.replace(/\D/g, '');
        const maxDigits = 3;
        if (value.length > maxDigits) {
            input.value = value.slice(0, maxDigits);
        }
    }

    
</script>
<script>

</script>
<script>
                                        function formatExpiryDate() {
                                        const input = document.getElementById('expiry_date');
                                        const value = input.value.replace(/\D/g, '');
                                        const formattedValue = value.replace(/^(.{2})(.*)$/, '$1/$2');
                                        input.value = formattedValue;

                                        // Validate the expiry date
                                        const today = new Date();
                                        const currentYear = today.getFullYear() % 100;
                                        const currentMonth = today.getMonth() + 1;
                                        
                                        if (value.length === 4) {
                                            const inputMonth = parseInt(value.substring(0, 2));
                                            const inputYear = parseInt(value.substring(2, 4));

                                            if (inputYear < currentYear || (inputYear === currentYear && inputMonth < currentMonth)) {
                                            input.setCustomValidity('Card has expired');
                                            } else {
                                            input.setCustomValidity('');
                                            }
                                        } else {
                                            input.setCustomValidity('');
                                        }
                                        }
                                        </script>
    </body>
    <footer>
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