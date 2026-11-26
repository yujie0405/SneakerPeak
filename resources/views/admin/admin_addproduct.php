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
                    $query = "SELECT Name AS Brand_Name FROM E_Brand";
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
                    $query = "SELECT Name AS S_Name FROM E_SubCategory";
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
                    $query = "SELECT Name AS Prod_Name, Image1Path AS Prod_img1 FROM T_Product";
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
    require_once SNEAKERPEAK_ROOT . '/src/App/Services/CatalogImageUpload.php';
    $files = [];
    $directory = SNEAKERPEAK_ASSETS . '/prod/';
    try {
        $name = trim((string) ($_POST['prod_name'] ?? ''));
        $price = filter_var($_POST['prod_price'] ?? null, FILTER_VALIDATE_FLOAT);
        if ($name === '' || $price === false || $price <= 0) throw new DomainException('Enter a product name and positive price.');
        if ($conn->execute_query('SELECT Id FROM T_Product WHERE Name = ?', [$name])->fetch_row()) {
            throw new DomainException('Product already exists.');
        }
        $brand = $conn->execute_query('SELECT Id FROM E_Brand WHERE Name = ?', [(string) $_POST['prod_brand']])->fetch_row()[0] ?? null;
        $sub = $conn->execute_query('SELECT Id FROM E_SubCategory WHERE Name = ?', [(string) $_POST['Sub-Cetegory']])->fetch_row()[0] ?? null;
        if (!$brand || !$sub) throw new DomainException('Choose a valid brand and subcategory.');
        foreach (['prod_img1','prod_img2','prod_img3'] as $field) {
            $files[] = \SneakerPeak\App\Services\CatalogImageUpload::save($_FILES[$field] ?? [], $directory);
        }
        $conn->execute_query(
            "INSERT INTO T_Product (BrandId, SubCategoryId, Name, Price, Colour, Audience, Details, Image1Path, Image2Path, Image3Path, ProductStatusId)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, (SELECT Id FROM E_ProductStatus WHERE Code = 'AVAILABLE'))",
            [$brand, $sub, $name, $price, $_POST['prod_colour'], $_POST['prod_gender'], $_POST['prod_details'], ...$files]
        );
        $resultMessage = 'Product added.';
    } catch (Throwable $error) {
        foreach ($files as $file) unlink($directory . $file);
        $resultMessage = $error instanceof DomainException ? $error->getMessage() : 'Could not save the product.';
    }
} elseif (isset($_POST['subm'])) {
    $qty = filter_var($_POST['prod_qty'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 4294967295]]);
    $size = (string) ($_POST['prod_Size'] ?? '');
    if (!$qty || !preg_match('/^\d+(?:\.5)?$/D', $size)) {
        $resultMessage = 'Choose a valid size and quantity.';
    } else {
        $conn->begin_transaction();
        try {
            $id = $conn->execute_query('SELECT Id FROM T_Product WHERE Name = ? ORDER BY Id LIMIT 1 FOR UPDATE', [(string) ($_POST['p_Name'] ?? '')])->fetch_row()[0] ?? null;
            if (!$id) throw new DomainException('Choose an existing product.');
            $conn->execute_query("INSERT INTO E_Size (SizingSystem, SizeLabel, SortOrder) VALUES ('UNSPECIFIED', ?, ?) ON DUPLICATE KEY UPDATE Id = Id",
                [$size, (int) round(2 * (float) $size)]);
            $sizeId = $conn->execute_query("SELECT Id FROM E_Size WHERE SizingSystem = 'UNSPECIFIED' AND SizeLabel = ?", [$size])->fetch_row()[0];
            $conn->execute_query('INSERT INTO T_ProductSizeAndCategory (ProductId, SizeId, Qty, IsAvailable) VALUES (?, ?, ?, 1)
                ON DUPLICATE KEY UPDATE Qty = Qty + VALUES(Qty)', [$id, $sizeId, $qty]);
            $conn->commit();
            $resultMessage = 'Stock added.';
        } catch (Throwable $error) {
            $conn->rollback();
            $resultMessage = $error instanceof DomainException ? $error->getMessage() : 'Could not save stock.';
        }
    }
}
if (isset($resultMessage)) { ?>
    <script>Swal.fire({title: <?= json_encode($resultMessage, JSON_HEX_TAG) ?>});</script>
<?php } ?>
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
