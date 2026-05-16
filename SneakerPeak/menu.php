<?php
 session_start();
 require_once('database/config.php');

 
  if (isset($_POST['search'])) {
    $search = $_POST['search'];

    // Query to retrieve the matching products
    $query = "SELECT * FROM product WHERE Prod_Name LIKE '%$search%' AND Prod_Status='available'";
    $result = mysqli_query($conn, $query);
} elseif (isset($_POST['Filter'])) {
    $selectedBrands = isset($_POST['brands']) ? $_POST['brands'] : array();
    $selectedCategory = isset($_POST['Cate']) ? $_POST['Cate'] : 'default_category';
    $selectedSubCategories = isset($_POST['subc']) ? $_POST['subc'] : array();
    
    $query = "SELECT * FROM product WHERE Prod_Status = 'available' ";

    // Add brand filter
    if (!empty($selectedBrands)) {
        $brands = implode("','", $selectedBrands);
        $query .= "AND Prod_Brand IN ('$brands') ";
    }

    if (!empty($selectedSubCategories)) {
        $subCategoryList = implode("', '", $selectedSubCategories);
        $query .= "AND Prod_SubC IN ('$subCategoryList') ";
    }
    
    // Perform the database query
    $result = mysqli_query($conn, $query);
}

 else {
    // Default query to retrieve all available products
    $query = "SELECT * FROM product WHERE Prod_Status='available'";
    $result = mysqli_query($conn, $query);
}
 

 ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Menu Page</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="vieport" content="width=device-width,initial-scale=1" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="css/headerandfooter.css">
    
        <link rel="stylesheet" href="css/style.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="css/flaticon.css">
        <link rel="stylesheet" href="css/icomoon.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/ionicons.min.css">

        <link rel="stylesheet" href="css/menu.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
        <style>
           
           .search-form {
         display: flex;
         width: 250px;
         align-items: center;
     }
     
     .search-input {
         padding: 8px;
         border: 1px solid #ccc;
         border-radius: 4px;
         flex: 1;
         width: 150px;
     }
     
     .search-submit {
         padding: 8px 12px;
         background: #2691d9;
         border: none;
         border-radius: 4px;
         color: white;
         cursor: pointer;
     }
     
     .search-submit:hover {
         background-color: blue;
     }
     select
     {
         width: 150px;
         height: 40px;
     }
     .labl{
         font-size: 18px;
     }
    
     </style>


    </head>


    <body>
    <?php
       include("header.php");
       ?>
        <br>
        <br>
        <br>
        <div class="menuborder">
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
         else
         {
            $UID=$_SESSION["CustomerID"] ;   
         }
        
         
        
         if (isset($_GET['add'])) {
            $add_id = $_GET['add'];
          
            // Check if the product is already added to the wishlist
            $wishlist_query = mysqli_query($conn, "SELECT * FROM wishlist WHERE UID = '$UID' AND prod_name = '$add_id'");
            $wishlist_exists = mysqli_num_rows($wishlist_query);
          
            if ($wishlist_exists > 0) {
              // Product already exists in the wishlist, display alert
              ?>
              <script>
                Swal.fire({
                    title: "Product already added to wishlist",
                    icon: "warning",
                    confirmButtonText: "OK",
                });
                </script>
              
              <?php
            } else {
              // Add the product to the wishlist
              $add_query = mysqli_query($conn, "INSERT INTO wishlist (UID, prod_name) VALUES ('$UID', '$add_id')");
              if ($add_query) {
                ?>
                <script>
                    Swal.fire({
                        title: "Item successfully added to wishlist",
                        icon: "success",
                        confirmButtonText: "OK",
                    });
                    </script>

                
                <?php
            }
            }
          }

        ?>
        <section class="menuselect" style="width:25%">
                
                    <ul class="category">
                        <li>
                            <h3>Search The Product </h3>
                            <div class="search-form">
                                <form action="menu.php" method="post">
                                    <input type="text" name="search" style="font-size: 13px; width: 200px;" class="search-input" placeholder="Search for a product">
                                    <button type="submit" class="search-submit" style="width:40px ;height: 40px;padding: 6px;font-size: 17px;">
                                        <i class="fa-solid fa-magnifying-glass" style="width:10px; height:10px; ;color: #fcfcfc;"></i>
                                    </button>
                                </form>
                            </div>  
                        </li>
                        <form method="post" action="menu.php">
                        <li>
                            <div class="selectcategory">
                                <div class="titlename">
                                    <h4>Brand</h4>
                                </div>
                                <hr>
                                <br>
                                <ul>
                                    <?php
                                    $brand = "SELECT * FROM brand";
                                    $callbrand = mysqli_query($conn, $brand);
                                    while ($rolll = mysqli_fetch_assoc($callbrand)) {
                                        ?>
                                        <li>
                                            <input type="checkbox" name="brands[]" id="<?php echo $rolll['Brand_Name']; ?>" value="<?php echo $rolll['Brand_Name']; ?>">
                                            <label for="<?php echo $rolll['Brand_Name']; ?>" class="labl"><?php echo $rolll['Brand_Name']; ?></label>
                                        </li>
                                    <?php } ?>

                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="titlename">
                                <h4>Category</h4>
                            </div>
                            <hr>

                            <select name="Cate" id="">
                                <option value="">All</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Kid">Kid</option>

                            </select>
                            <br>
                        </li>
                        <li>
                            <br>
                            <div class="titlename">
                                <h4>Sub-Category</h4>
                            </div>
                            <hr>
                             <ul>
                             <?php
                                $subc = "SELECT * FROM sub_category";
                                $callsubc = mysqli_query($conn, $subc);
                                while ($rolii = mysqli_fetch_assoc($callsubc)) {
                                    ?>      
                                <li>
                                <input type="checkbox" name="subc[]" id="<?php echo $rolii['S_Name']; ?>" value="<?php echo $rolii['S_Name']; ?>">
                                <label for="<?php echo $rolii['S_Name']; ?>" class="labl"><?php echo $rolii['S_Name']; ?></label>

                                </li>
                                <?php } ?>
                             </ul>
                                     
                            
                                
                            
                            <br>
                        </li>
                        <li>
                            <input type="submit" class="fil" style="width:100px; height: 30px;padding-bottom:10px;" value="Search" name="Filter">
                        </li>
                    </form>

                    </ul> 
                    
                
            </section>
            <section class="menu-list">
             <div class="Menu">
                    <h1>Product</h1>
                    <hr>
                    <br>
                    <br>
                    <?php
                        while($row = mysqli_fetch_assoc($result)){
                          ?>
                    <div class="spac">
                        <div class="card">

                            <div class="small_card">
                                <a href="menu.php?add=<?php echo $row ['Prod_Name'];?>"><i class="fa-solid fa-heart"></i></a>
                                
                            </div>
            
                            <div class="image">
                                <img src="prod/<?php  echo $row ['Prod_img1']; ?>">
                            </div>
                            
                            <div class="products_text">
                                <div class="rolbox">
                                    <div class="pname">
                                        <h2><?php  echo $row ['Prod_Name']; ?></h2>
                                    </div>
                                </div>
                                <div class="dbox">
                                 <p>
                                     <?php  echo $row ['Prod_Details']; ?>
                                 </p>
                                </div>
                                <div class="price">
                                <h4 style="font-size:20px">RM <?php  echo $row ['Prod_Price']; ?></h4>
                                </div>
                                <div class="button">
                                <a href="prod.php?Prod_Name=<?php  echo $row ['Prod_Name']; ?>" target="_self"  class="btn">View Product</a>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }

                        ?>
                </div>
             
                
            </section>
        </div>
         

            <br>
            <br>
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>