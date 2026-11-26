<!DOCTYPE html>
<html lang="en">
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Order history</title>
        <link rel="stylesheet" href="css/Orderhistory.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/flaticon.css">
        <link rel="stylesheet" href="css/icomoon.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/ionicons.min.css">
        <style>
            img
            {
                height: 40px;
                width: 40px;
            }
            .circle img {
                    transition: transform 0.3s ease-in-out;
                }

                /* Apply the animation on hover */
                .circle:hover img {
                    transform: scale(1.2);
                }
                .button-spacing {
                margin-right: 10px; /* Adjust the spacing as per your preference */
            }
        </style>
    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
    <header>
        <?php
            include SNEAKERPEAK_ROOT . '/resources/views/partials/header.php';
        ?>
    </header>
    <body>
        <br>
        <br>
        <div class="ODH">
            <div class="conten">
                <h1>Order History</h1>
                <hr>
            </div>
            <div class="trackorder">
                <div class="hh">
                    <div class="trackcontan">
                        <h2>Track Your Order:#<?php echo $orderCode ?></h2>
                        <form action="" method="post">
                            <input type="text" name="odcode" id="" class="tracko">
                            <input type="submit" value="Search" name="od">
                        </form>
                    </div>
                    <div class="body">
                        <div class="cont">
                        <div class="steps">
                            <span class="circle <?php echo $os == 'Pending' ? 'active' : ''; ?>"><img src="image/logo/processing.png" alt=""></span>
                            <span class="circle <?php echo $os == 'Shipping' ? 'active' : ''; ?>"><img src="image/logo/shipping.png" alt=""></span>
                            <span class="circle <?php echo $os == 'Complete' ? 'active' : ''; ?>"><img src="image/logo/complete.png" alt=""></span>
                            
                            <div class="progress-bar">
                                <span class="indicator"></span>
                            </div>
                        </div>
                          <h1>
                            <?php echo $oc ?>

                            </h1>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div class="historybox">
                    <h2>History List</h2>
                    <div class="hissmallbox">
                        <div class="smallcont">
                            <ul>
                            <?php
                            foreach ($orders as $row) {
                                $odc = $row['Order_code'];
                                $asking = $row['ask'];
                            ?>  
                            <li>
                                <div class="smallbox">
                                    <h3>Order Code: <span>#<?php echo $row['Order_code'] ?></span></h3>
                                    <hr>
                                    <ul> 
                                        <li>Total Amount:RM<?php echo number_format($row['Order_TotalAmmount'], 2); ?></li>
                                        <li>Payment Type:<?php echo $row['Payment_Type'] ?></li>
                                        <li>Order Date:<?php echo $row['Order_Date'] ?></li>
                                    </ul>
                                    <hr>
                                    <div class="smalbtn">
                                        <?php if($asking == '0') { ?>
                                            <button class="complete-btn" disabled>Complete Order</button>
                                        <?php } else { ?>
                                            <button class="complete-btn" style="background: green; " onclick="confirmCompletion('<?php echo $odc; ?>')">Complete Order</button>
                                        <?php } ?>
                                        <span class="button-spacing"></span>
                                        <button onclick="openInvoice('<?php echo $odc; ?>')">View Receipt</button>
                                    </div>
                                </div>
                            </li>
                            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                            <script>
                                function openInvoice(orderCode) {
                                    // Open invoice.php with the orderCode as a parameter
                                    window.location.href = 'recei.php?Ordercode=' + orderCode;
                                }

                                function confirmCompletion(orderCode) {
                                // Display a confirmation dialog using SweetAlert
                                swal({
                                    title: "Complete Order",
                                    text: "Are you sure you want to complete the order?",
                                    icon: "warning",
                                    buttons: ["Cancel", "Complete"],
                                    dangerMode: true,
                                }).then((willComplete) => {
                                    if (willComplete) {
                                        // Make an AJAX request to updateask.php to update the status
                                        var xhr = new XMLHttpRequest();
                                        xhr.open("GET", "updateask.php?Ordercode=" + orderCode, true);
                                        xhr.onreadystatechange = function () {
                                            if (xhr.readyState === 4 && xhr.status === 200) {
                                                // If the update is successful, reload the page
                                                location.reload();
                                            }
                                        };
                                        xhr.send();
                                    }
                                });
                            }
                            </script>
                            <?php
                            }
                            ?>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       <br>
       <br>
       <br>
       <br>
    </body>
    
    <script>
        const circles = document.querySelectorAll(".circle"),
        progressBar = document.querySelector(".indicator"),
        buttons = document.querySelectorAll("button");
        let currentStep = 1;
        // function that updates the current step and updates the DOM
        const updateSteps = (e) => {
        // update current step based on the button clicked
        currentStep = e.target.id === "next" ? ++currentStep : --currentStep;
        // loop through all circles and add/remove "active" class based on their index and current step
        circles.forEach((circle, index) => {
            circle.classList[`${index < currentStep ? "add" : "remove"}`]("active");
        });
        // update progress bar width based on current step
        progressBar.style.width = `${((currentStep - 1) / (circles.length - 1)) * 100}%`;
        // check if current step is last step or first step and disable corresponding buttons
        if (currentStep === circles.length) {
            buttons[1].disabled = true;
        } else if (currentStep === 1) {
            buttons[0].disabled = true;
        } else {
            buttons.forEach((button) => (button.disabled = false));
        }
        };
        // add click event listeners to all buttons
        buttons.forEach((button) => {
        button.addEventListener("click", updateSteps);
        });
    </script>
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
</html>