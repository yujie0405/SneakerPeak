<?php

  include SNEAKERPEAK_ROOT . '/database/connect.php';
  if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
?>


<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/addproduct.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
<?php
    require_once SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php';
?>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">Add Product </span>


    </div>
    
    <div class="itembox">
      <h1>Product Details </h1>

      <div class="inputbox">
        <form method="post" enctype="multipart/form-data">
          <div class="box">
            <h4>Product Name:</h4>
            <input type="text" name="prod_name" required>
          </div>
          <div class="box">
            <h4>Product Brand:</h4>
            <select name="prod_brand" id="" class="selectinfo" required>
            <?php
                    $query = "select * from brand";
                    $result = mysqli_query($conn,$query);
                    while($row = mysqli_fetch_assoc($result)){

                  ?>
              <option value="<?php echo $row['Brand_Name'];?>"><?php echo $row['Brand_Name'];?></option>
              <?php
                                 };
                               
        
                  ?>
            </select>
          </div>
          <div class="box">
            <h4>Product Price:</h4>
            <input type="number" name="prod_price" min="1" required>
          </div>
          <div class="box">
            <h4>Product Colour:</h4>
            <input type="text" name="prod_colour" required>
          </div>
          <div class="box">
            <h4>Product Category:</h4>
            <select name="prod_gender" id="" class="selectinfo" required>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Kid">Kid</option>
            </select>
          
          </div>
          <div class="box">
            <h4>Product Sub-Category:</h4>
            <select name="Sub-Cetegory" id=""  class="selectinfo" required>
            <?php
                    $query = "select * from sub_category";
                    $result = mysqli_query($conn,$query);
                    while($row = mysqli_fetch_assoc($result)){

                  ?>
              <option value="<?php echo $row['S_Name'];?>"><?php echo $row['S_Name'];?></option>
              <?php
                                 };
                               
        
                  ?>
            </select>
          
          </div>
          <div class="box">
            <h4>Product Details:</h4>
             <textarea name="prod_details" id=""  required></textarea>
          </div>
          <div class="box">
            <h4>Product image 1:</h4>
            <input type="file" name="prod_img1" required>
            <h4>Product image 2:</h4>
            <input type="file" name="prod_img2" required>
            <h4>Product image 3:</h4>
            <input type="file" name="prod_img3" required>
          </div>
          <input type="text" value="available" name="status" hidden>
          <div class="box">
            <div class="sub">
            <input type="submit" value="Add Product" name="submit" id="">
            </div>
          </div>
        </form>
      </div>
    </div>
    <br>
    <br>
    <div class="itembox">
      <h1>Add Size</h1>
      <div class="inputbox">
        <form method="post" enctype="multipart/form-data">
          <div class="box">
            <h4>Product Name:</h4>
            <select name="p_Name" id="" class="selectinfo">
              <option value="Select" style="display: none;">Select Product</option>
                <?php
                    $query = "select * from product";
                    $result = mysqli_query($conn,$query);
                    while($row = mysqli_fetch_assoc($result)){

                  ?>
              <option value="<?php echo $row['Prod_Name'];?>">
                      <?php echo $row['Prod_Name'];?>
                      <img src="prod/<?php echo $row['Prod_img1'];?>" alt="">
              </option>
              <?php
                                 };
                               
        
                  ?>
              
            </select>
          </div>
          <div class="box">
            <h4>Product Size:</h4>
            <select name="prod_Size" id="" class="selectinfo" required>
              <option value="Select" style="display: none;">Select The Size</option>
              <option value="3.5">3.5</option>
              <option value="4">4</option>
              <option value="4.5">4.5</option>
              <option value="5">5</option>
              <option value="5.5">5.5</option>
              <option value="6">6</option>
              <option value="6.5">6.5</option>
              <option value="7">7</option>
              <option value="7.5">7.5</option>
              <option value="8">8</option>
              <option value="8.5">8.5</option>
              <option value="9">9</option>
              <option value="9.5">9.5</option>
              <option value="10">10</option>
              <option value="10.5">10.5</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
            </select>
          </div>
          <div class="box">
            <h4>Product Qty:</h4>
            <input type="number" name="prod_qty" min="1">
            
          </div>
          <div class="box">
            <div class="sub">
            <input type="submit" value="Add Size" name="subm" id="">
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
    if (isset($_POST['submit'])) {
      $product_name = $_POST['prod_name'];
      
      // Check if the product with the same name already exists
      $existingProductQuery = "SELECT * FROM product WHERE Prod_Name = '$product_name'";
      $existingProductResult = mysqli_query($conn, $existingProductQuery);
      
      if (mysqli_num_rows($existingProductResult) > 0) {
          // Product already exists, display an alert message
          ?>
          <script> Swal.fire({
        title: 'Product already exists',
        text: 'Please use another name.',
        icon: 'error',
        confirmButtonText: 'OK'
    });</script><?php
      } else {
          // Product does not exist, proceed with insertion
          $product_brand = $_POST['prod_brand'];
          $product_price = $_POST['prod_price'];
          $product_colour = $_POST['prod_colour'];
          $product_gender = $_POST['prod_gender'];
          $product_SubC = $_POST['Sub-Cetegory'];
          $product_details = $_POST['prod_details'];
          $product_status = $_POST['status'];
  
          $product_img1 = $_FILES['prod_img1']['name'];
          $prod_img1_tmp = $_FILES['prod_img1']['tmp_name'];
  
          $product_img2 = $_FILES['prod_img2']['name'];
          $prod_img2_tmp = $_FILES['prod_img2']['tmp_name'];
  
          $product_img3 = $_FILES['prod_img3']['name'];
          $prod_img3_tmp = $_FILES['prod_img3']['tmp_name'];
  
          $img_upload_path = SNEAKERPEAK_ASSETS . '/prod/';
          move_uploaded_file($prod_img1_tmp, $img_upload_path . $product_img1);
          move_uploaded_file($prod_img2_tmp, $img_upload_path . $product_img2);
          move_uploaded_file($prod_img3_tmp, $img_upload_path . $product_img3);
  
          $prod = mysqli_query($conn, "INSERT INTO product(Prod_Name,Prod_Brand,Prod_Price,Prod_Colour,Prod_Cetegory,Prod_SubC,Prod_Details,Prod_Img1,Prod_Img2,Prod_Img3,Prod_Status) 
          VALUES ('$product_name','$product_brand','$product_price','$product_colour','$product_gender','$product_SubC','$product_details','$product_img1','$product_img2','$product_img3','$product_status')");
          if($prod)
          {
            ?>
            <script>
               Swal.fire({
                title: 'Product Added',
                text: 'The product has been successfully added.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(function() {
                // Redirect or perform any additional action after the user clicks 'OK'
                 window.location.href = 'admin_addproduct.php';
            });
            </script>

          <?php
          }
      }
  }
  
  

  if (isset($_POST['subm'])) {
    $p_name = $_POST['p_Name'];
    $p_S = $_POST['prod_Size'];
    $p_q = $_POST['prod_qty'];
  
    // Check if the record already exists for the product and size
    $existingSizeQuery = "SELECT * FROM sizeandqty WHERE Prod_Name = '$p_name' AND Prod_Size = '$p_S'";
    $existingSizeResult = mysqli_query($conn, $existingSizeQuery);
  
    if (mysqli_num_rows($existingSizeResult) > 0) {
        // Record already exists, update the quantity
        $existingSizeRow = mysqli_fetch_assoc($existingSizeResult);
        $existingQty = $existingSizeRow['Prod_Qty'];
        $newQty = $existingQty + $p_q;
  
        $updateSizeQuery = "UPDATE sizeandqty SET Prod_Qty = '$newQty' WHERE Prod_Name = '$p_name' AND Prod_Size = '$p_S'";
        $updateSizeResult = mysqli_query($conn, $updateSizeQuery);
  
        if ($updateSizeResult) {
           ?> <script>
               Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Quantity Update successfully.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location = "admin_addproduct.php";
            });

           
           </script><?php
        } 
    } else {
        // Record does not exist, insert a new record
        $statusqty='Available';
        $insertSizeQuery = "INSERT INTO sizeandqty(Prod_Name, Prod_Size, Prod_Qty,Qty_Status) VALUES ('$p_name', '$p_S', '$p_q','$statusqty')";
        $insertSizeResult = mysqli_query($conn,$insertSizeQuery);
  
        if ($insertSizeResult) {
           ?><script>
              Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Size  inserted successfully.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location = "admin_addproduct.php";
            });
          
            </script><?php
        } 
    }
  }


?>
  <script>
  let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e)=>{
   let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
   arrowParent.classList.toggle("showMenu");
    });
  }
  let sidebar = document.querySelector(".sidebar");
  let sidebarBtn = document.querySelector(".bx-menu");
  console.log(sidebarBtn);
  sidebarBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("close");
  });
  </script>
</body>
</html>
