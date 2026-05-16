<?php
    include('database/config.php');
    session_start(); // Start the session
    $UID=$_SESSION["CustomerID"] ; 
        // Check if the session variable is set
        if (isset($_SESSION['Ordercode'])) {
            // Access the value of the session variable
            $Ordercode = $_SESSION['Ordercode'];

           
        } else {
            // Session variable not set, handle the case accordingly
            echo "Order Code not found";
        }
        $query="SELECT * FROM oder WHERE Order_Code='$Ordercode' AND UID='$UID' ";
        $result=mysqli_query($conn,$query);

        
    
    
  
    $sql = "SELECT SUM(Order_qty) AS TotalQuantity FROM oder_details WHERE Order_Code = '$Ordercode'AND UID='$UID'";
    
    // Execute the query
    $resul = mysqli_query($conn, $sql);
    
    
    if ($resul) {
        
        $row = mysqli_fetch_assoc($resul);
        
        
        $totalQuantity = $row['TotalQuantity'];
        
    } 
    
  
    
    if(isset($_POST['go']))
    {
        $RN=$_POST['Receiver_Name'];
        $CN=$_POST['Receiver_Contract_Number'];
        $A1=$_POST['Receiver_Address_Line1'];
        $A2=$_POST['Receiver_Address_Line2'];
        $PC=$_POST['PostCode'];
        $C=$_POST['City'];
        $S=$_POST['State'];

        $method=$_POST['card'];
        $_SESSION['payment_method'] =$method;
        $address = $A1 . ', ' . $A2 . ', ' . $PC . ', ' . $C . ', ' . $S;
        $query="INSERT INTO oder_address (UID,Order_code,Order_Address,Receiver_Name,Receiver_Contract)VALUE('$UID','$Ordercode',' $address','$RN','$CN')";
        mysqli_query($conn, $query);
        $updateQuery = "UPDATE `oder` SET 	Payment_Type='$method' WHERE Order_Code='$Ordercode' AND UID='$UID'";
        mysqli_query($conn, $updateQuery);
        header('location:payment.php');
    }

        
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Main Page</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        
        <link rel="stylesheet" href="css/headerandfooter.css">
        <link rel="stylesheet" href="css/selectaddress.css">
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <meta name="vieport" content="width=device-width,initial-scale=1" />
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

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <meta name="vieport" content="width=device-width,initial-scale=1" />
        <style>
            .payment_Type
                {
                    display: flex;
                 
                    width: 70%;
                    
                }
                .cnt_full {
                display: block;
                margin: 20px 10px;
                width: 100%;
                }
.cnt_min {
  display: inline-block; 
  width:150px;
  margin: 10px;
  height: 50px;
  position: relative;
  padding: 0 2%;
}
.cnt_min input[type="radio"] {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
}
.selected_img {
  pointer-events: none;
  width: 50px;
  height: 40px;
}
.cnt_min input[type="radio"]:checked ~ .selected_img {
  border: solid 1px green;
  box-shadow: 0px 1px 4px 0px #ccc;
  border-radius: 5px;
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
        <div class="category">
            
            <section class="address">
                
                <form method="post" id="paymentForm"  action="">
                    <div class="topic">
                        <H2>Select A Delivery Address</H2>
                        <br>
                        <hr>
                    </div>   
                    
                    <div class="addre">
                    <select name="addressOption" id="addressOption" onchange="fetchAddress()">
                        <option value="New">New Address</option>
                        <option value="Customer_Address">Address 1</option>
                        <option value="Customer_Address_2">Address 2</option>
                    </select>
                    </div> 
                    <div class="addre">
                        <h3>Receiver Name:</h3>
                        <input type="text" name="Receiver_Name" required>
                    </div>
                    <div class="addre">
                        <h3>Receiver Contact Number:</h3>
                        <input type="text" name="Receiver_Contract_Number" id="receiverContractNumber" required>

                        <script>
                        // Get the input element
                        const receiverContractNumberInput = document.getElementById('receiverContractNumber');

                        // Add an event listener for the 'input' event
                        receiverContractNumberInput.addEventListener('input', function() {
                        // Remove non-numeric characters from the input value
                        this.value = this.value.replace(/[^0-9]/g, '');

                        // Validate the input against the Malaysian telephone number format
                        const malaysiaPhoneNumberPattern = /^01[0-46-9]-*[0-9]{7,8}$/;
                        const isValidPhoneNumber = malaysiaPhoneNumberPattern.test(this.value);

                        // Display an error message if the input is not a valid Malaysian telephone number
                        const errorMessageElement = document.getElementById('phoneNumberError');
                        if (!isValidPhoneNumber) {
                            errorMessageElement.textContent = 'Please enter a valid Malaysian telephone number.';
                        } else {
                            errorMessageElement.textContent = '';
                        }
                        });
                        </script>
                        <p id="phoneNumberError" style="color: red;"></p>


                    </div>
                    
                    <div class="addre">
                    <h3>Receiver Address Line 1:</h3>
                    <div id="addressField">
                        <input type="text" id="addressLine1" name="Receiver_Address_Line1" value="">
                    </div>
                    </div>

                    <div class="addre">
                    <h3>Receiver Address Line 2:</h3>
                    <input type="text" id="addressLine2" name="Receiver_Address_Line2" value="">
                    </div>

                    <div class="addre">
                    <h3>Post Code:</h3>
                    <input type="text" id="postCodeField" name="PostCode" value="" required>
                    </div>

                    <div class="addre">
                    <h3>City:</h3>
                    <input type="text" id="cityField" name="City" value="" required>
                    </div>

                    <div class="addre">
                    <h3>State:</h3>
                    <input type="text" id="stateField" name="State" value="" required>
                    </div>
                    <div class="topic">
                        <H2>Select Payment Method</H2>
                        <br>
                        <hr>
                    </div> 
                    <br>
                    <div class="payment_Type">
                    <div class="cnt_full">
                        <div class="cnt_min">
                            <input type="radio" id="cardPayment" name="card" value="Card_Payment" /><img src="image/logo/card.png" alt="Select payment method" class="selected_img">
                            <p>Card Payment</p>
                        </div>
                        <div class="cnt_min">
                            <input type="radio" id="qrPayment"  name="card" value="QR_Payment" /><img src="image/logo/qr.png" alt="Select payment method" class="selected_img">
                            <p>QR Payment</p>
                        </div>
                        
                        </div>
                    </div>
                    <br>
                    <div class="addre">
                        <input type="submit" name="go"  value="Proceed To Pay" onclick="validatePaymentMethod()">
                    </div>
                </form>
            </section>
            <script>
                function validatePaymentMethod() {
                var cardPayment = document.getElementById('cardPayment');
                var qrPayment = document.getElementById('qrPayment');
                
                if (!cardPayment.checked && !qrPayment.checked) {
                    event.preventDefault(); // Prevent the form from submitting
                    
                    // Display SweetAlert message
                    swal({
                    title: "Error",
                    text: "Please select a payment method.",
                    icon: "error",
                    });
                }
                }


                function fetchAddress() {
                    var selectOption = document.getElementById('addressOption');
                    var addressField1 = document.getElementById('addressLine1');
                    var addressField2 = document.getElementById('addressLine2');
                    var postCodeField = document.getElementById('postCodeField');
                    var cityField = document.getElementById('cityField');
                    var stateField = document.getElementById('stateField');
                    var selectedOption = selectOption.value;

                    if (selectedOption === 'Customer_Address' || selectedOption === 'Customer_Address_2') {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = xhr.responseText;
                        var addresses = response.split(',');
                        if (addresses.length >= 5) {
                            addressField1.value = addresses[1].trim();
                            addressField2.value = addresses[2].trim();
                            postCodeField.value = addresses[3].trim();
                            cityField.value = addresses[4].trim();
                            stateField.value = addresses[5].trim();
                        } else {
                            addressField1.value = '';
                            addressField2.value = '';
                            postCodeField.value = '';
                            cityField.value = '';
                            stateField.value = '';
                        }
                        }
                    };
                    xhr.open('GET', 'fetch_address.php?UID=<?php echo $UID; ?>&addressOption=' + selectedOption, true);
                    xhr.send();
                    }
                }
                </script>
            <section class="cart">
            <?php
                       $row = mysqli_fetch_assoc($result) ;
                         
                    ?>

                <div class="total">
                    <div class="info">
                        <h4>Receipt: </h4>
                        <hr>
                        <div class="amount">
                        <h4>Amount(<?= $totalQuantity?>):</h4>
                            <h4>RM <?php echo number_format($row['Order_TotalAmmount'], 2); ?></h4>

                        </div>
                        <div class="amount">
                            <h4>Shipping Feed:</h4>
                            <h4>RM <?php echo $row['ShippingFeed']; ?></h4>
                        </div>
                        <br>
                        <hr>
                        <div class="amount">
                            <h4>Total Amount:</h4>
                            <h4>RM <span class="money"><?php echo number_format($row['Order_TotalAmmount'] + $row['ShippingFeed'], 2); ?></span></h4>

                        </div>
                    </div> 
                </div>
               <?php

               ?>
            </section>
        </div>
       
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
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</html>