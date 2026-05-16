<?php
  include('database/config.php');
  session_start();
  $UID = $_SESSION["CustomerID"] ?? null;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="css/prod.css">
    <link rel="stylesheet" href="css/headerandfooter.css">
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
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
</head>



<body>
<?php
       include("header.php");
       ?>

     <?php
      if(isset($_POST['add-tocard'])) {
        $img = $_POST['img'];
        $Pname = $_POST['Prod_Name'];
        $price = $_POST['price'];
        $size = $_POST['P_size'];
        $qty = $_POST['qty'];
      
        $existingProduct = mysqli_query($conn, "SELECT * FROM cart WHERE Product_Name = '$Pname' AND Select_Size = '$size'");
        if (mysqli_num_rows($existingProduct) > 0) {
            $row = mysqli_fetch_assoc($existingProduct);
            $oldQty = $row['Select_qty'];
            $newQty = $qty + $oldQty;
            mysqli_query($conn, "UPDATE cart SET Select_qty = $newQty WHERE Product_Name = '$Pname' AND Select_Size = '$size'");
        } else {
            // Check if the ordered quantity exceeds the available stock
            $productQuery = "SELECT Prod_Qty FROM sizeandqty WHERE Prod_Name = '$Pname' AND Prod_Size ='$size'";
            $productResult = mysqli_query($conn, $productQuery);
            $productRow = mysqli_fetch_assoc($productResult);
            $stockQty = $productRow['Prod_Qty'];
      
            if ($qty > $stockQty) {
                ?>
               <script>
                Swal.fire({
                  title: "The stock is not enough",
                  text: "Please select a lower quantity",
                  icon: "warning",
                  confirmButtonText: "OK",
                }).then(() => {
                  window.location.href = "menu.php";
                });
              </script>
                <?php
                exit(); // Stop execution if the stock is not enough
            }
      
            // Insert the product into the cart
            $insert = mysqli_query($conn, "INSERT INTO cart(UID, Prod_Img1, Product_Name, Prod_Price, Select_Size, Select_qty) VALUES ('$UID', '$img', '$Pname', '$price', '$size', '$qty')");
        }
      
        ?>
        
        
      <script>
        Swal.fire({
          title: "Successfully added to the cart",
          icon: "success",
          confirmButtonText: "OK",
        }).then(() => {
          window.location.href = "menu.php";
        });
      </script>
      
        <?php
      }


       if (!isset($_SESSION["CustomerID"])) {
        ?><script>
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
      </script><?php
    }
     
     
     ?>  
    <!--==Product-Page=================================-->
    <section id="product-page">
        <div class="product-details">
            <!--Img***********************-->
            <div class="product-img">
    
            <!-- Swiper Slider -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                <?php
                $Name=$_GET['Prod_Name'];
                $query = "SELECT * FROM `product` WHERE Prod_Name = '$Name'";;
                $result = mysqli_query($conn,$query);
                $row= mysqli_fetch_assoc($result);
                
            ?>
                <!--1****-->
                <div class="swiper-slide">
                <img src="prod/<?php  echo $row ['Prod_img1']; ?>" >
                </div>
                <!--2****-->
                <div class="swiper-slide">
                <img src="prod/<?php  echo $row ['Prod_img2']; ?>" >
                </div>
                <!--3****-->
                <div class="swiper-slide">
                <img src="prod/<?php  echo $row ['Prod_img3']; ?>" >
                </div>
                <!--4****-->
             

                </div>
                <?php
              ?>
    
            </div>
    
            </div>
            <!--Text**********************-->
            <div class="product-text">
                <!--category-->
                <?php
                $Name=$_GET['Prod_Name'];
                $query = "SELECT * FROM `product` WHERE Prod_Name = '$Name'";;
                $result = mysqli_query($conn,$query);
                $row= mysqli_fetch_assoc($result);
                
            ?>
                <span class="product-category"><?php echo $row['Prod_Cetegory'];?>--<?php echo $row['Prod_SubC'];?></span>
                <h3><?php echo $row['Prod_Name'];?></h3>
                <span class="product-price">RM <?php echo $row['Prod_Price'];?> </span>
                <p><?php echo $row['Prod_Details'];?></p>
                
                <p>colour : <?php echo $row['Prod_Colour'];?></p>
                <!--size-->
                <?php
              ?>
                <form method="post"  name="test">
                    <div class="product-size-container">
                        <strong>Select Size (US):-</strong>
                        <div class="product-size">
                            
                        <?php
                        $Name=$_GET['Prod_Name'];
                        $query = "SELECT * FROM `sizeandqty` WHERE  Qty_status='Available' AND Prod_Name = '$Name' ORDER BY CAST(Prod_Size AS DECIMAL(10,2)) ASC ";
                        $result = mysqli_query($conn,$query);
                        
                        while($row = mysqli_fetch_assoc($result)){

                        ?>      
                           <input type="checkbox" class="s-checkbox" name="P_size" id="<?php echo $row['Prod_Size'];?>" value="<?php echo $row['Prod_Size'];?>">
                        <label for="<?php echo $row['Prod_Size'];?>" class="s-label"><?php echo $row['Prod_Size'];?></label>
                        
                      
                        <?php
                        };
               

                    ?>
                        </div>
                    </div>
                    <input type="text" value="<?=$Name?>" name="Prod_Name" hidden>
                    <?php
                $Name=$_GET['Prod_Name'];
                $query = "SELECT * FROM `product` WHERE Prod_Name = '$Name'";;
                $result = mysqli_query($conn,$query);
                $row= mysqli_fetch_assoc($result);
                
            ?>
                    <input type="hidden" value="<?php  echo $row ['Prod_img1']; ?>"name="img">
                    <input type="text" value="<?php  echo $row ['Prod_Price']; ?>" name="price" hidden>
                    <?php
              ?>
              <br>
              <br>
                    <input type="number" min="1" value="1" name="qty" required>
        
                    <!--btn-->
                    <div class="product-button">
                      <input type="submit" value="Add To Cart" name="add-tocard" class="buy-now-btn" onclick="return validate()">
                      
                      <a href="#" class="add-bag-btn" onclick="goBack()">Go Back</a>

                    <script>
                      function goBack() {
                        window.history.back();
                      }
                    </script>
                  </div>
                </form>
                <!--help-btn-->
                <a href="#" class="help-btn">Need Any Help?</a>
            </div>
        </div>
        </section>
            <script>
                function validate() {
                el = document.getElementsByName('P_size');

                var atLeastOneChecked = false; //at least one cb is checked
                for (i = 0; i < el.length; i++) {
                    if (el[i].checked === true) {
                    atLeastOneChecked = true;
                    }
                }

                if (atLeastOneChecked === true) {
                    for (i = 0; i < el.length; i++) {
                    el[i].required = false;
                    }
                } else {
                    for (i = 0; i < el.length; i++) {
                    el[i].required = true;
                    }
                }
                }
            </script>

        <!--==Jquery===========================-->
        <script
      src="https://code.jquery.com/jquery-3.6.1.js"
      integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
      crossorigin="anonymous"></script>
    
        <!-- Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    
        <!-- Initialize Swiper -->
        <script>
          var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 10,
            navigation: {
              nextEl: ".swiper-button-next",
              prevEl: ".swiper-button-prev",
            },
            breakpoints: {
              450: {
                slidesPerView: 2,
                spaceBetween: 0,
              },
              820: {
                slidesPerView: 1,
                spaceBetween: 0,
              },
              1024: {
                slidesPerView: 2,
                spaceBetween:0,
              },
            },
          });
    
    
          $('.s-checkbox').on('change', function() {
                $('.s-checkbox').not(this).prop('checked', false);  
            });
        </script>
</body>

<footer>
    <?php
        include('footer.php')
        
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