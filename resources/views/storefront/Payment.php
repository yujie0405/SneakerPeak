<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Main Page</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <link rel="stylesheet" href="css/headerandfooter.css">
        <link rel="stylesheet" href="css/payment.css">
        <meta name="viewport" content="width=device-width,initial-scale=1" />
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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <link rel="stylesheet" href="css/flaticon.css">
        <link rel="stylesheet" href="css/icomoon.css">
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width,initial-scale=1" />

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

                          
                          <h2><?= $method === 'Card_Payment' ? 'Card Payment' : 'E-wallet Payment' ?></h2>
                          <?php if ($message !== null) { ?>
                              <p role="alert"><?= htmlspecialchars($message) ?></p>
                          <?php } ?>
                          <?php if ($demoMode) { ?>
                              <p>This demonstration does not charge or collect payment details.</p>
                              <form method="post">
                                  <input type="hidden" name="cart_token" value="<?= htmlspecialchars($token) ?>">
                                  <button type="submit" class="btn btn-primary" name="complete_demo">Complete demo payment</button>
                              </form>
                          <?php } else { ?>
                              <p>Online payment is currently unavailable.</p>
                          <?php } ?>

                          
                        </div>
                        
                    </div>
                    
                </div>

                
             
           
            
            </div>
           
        </div>
        
        
<script>

</script>

    </body>
    <footer>
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
</html>