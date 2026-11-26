<?php
include SNEAKERPEAK_ROOT . '/database/connect.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

?>
<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
  
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/addbrand.css">
  
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
      <span class="text">Add Brand & Sub-Category</span>
    </div>
    
    <div class="addbrand-box">
      <h1>Add Brand</h1>
      <div class="container">
        <div class="brand-inputbox">


        <form method="post" action="admin_addbrand.php" enctype="multipart/form-data">
          <div class="box">
            <h4>Brand Name:</h4>
            <input type="text" name="brand_Name" id="" placeholder="Enter Brand Name" required>
          </div>
          <div class="box">
            <h4>Brand Logo image:</h4>
            <input type="file" name="logo_img" required>
          </div>
          <div class="box">
            <div class="sub">
              <input type="submit" value="Add Brand" name="ADD" id="" required>
            </div>
          </div>
        </form>



        </div>
          <div class="table">
            <div class="table-warpper">
              <table>
                <thead>
                  <tr>
                    <th colspan="2">Brand</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $query = "SELECT Name AS Brand_Name, LogoPath AS Brand_logo FROM E_Brand";
                    $result = mysqli_query($conn,$query);
                    while($row = mysqli_fetch_assoc($result)){

                  ?>
                  <tr>
                    <td>
                        <div class="brands">
                           <div class="brands_name">
                            <?php echo $row['Brand_Name'];?>
                           </div>
                        </div>
                      
                    </td>
                    <td>
                         <div class="brands_img">
                                <img src="image/logo/<?php echo $row['Brand_logo'];?>" alt="" style="width:30px; height:30px;">
                           </div>
                    </td>
                  </tr>
                  <?php
                                 };
                               
        
                  ?>
                  
                </tbody>
              </table>
            </div> 
          </div>
      </div>
    </div>


    <br>
    <div class="addsubcateg-box">
      <h1>Add Sub-Category</h1>
      <div class="container">
        <div class="subcateg-inputbox">
        <form method="post" action="admin_addbrand.php">
          <div class="box">
            <h4>Sub-Category Name:</h4>
            <input type="text" name="Sub_Category" id="" placeholder="Enter Sub-Category" required>
          </div>
          <div class="box">
            <div class="sub">
              <input type="submit" value="Add Sub-Category" name="ADD_SUB" id="" required>
            </div>
          </div>
        </form>
        </div>
        <div class="table">
         <div class="table-warpper">
            <table>
              <thead>
                <tr>
                  <th>Sub-Category</th>
                </tr>
              </thead>
              <tbody>
              <?php
                    $query = "SELECT Name AS S_Name FROM E_SubCategory";
                    $result = mysqli_query($conn,$query);
                    while($row = mysqli_fetch_assoc($result)){

                  ?>
                <tr>
                  <td><?php echo $row['S_Name'];?></td>
                </tr>
                <?php
                                 };
                               
        
                  ?>
                  
              </tbody>
            </table>
         </div> 
        </div>

      </div>
    </div>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php
if (isset($_POST['ADD']) || isset($_POST['ADD_SUB'])) {
    require_once SNEAKERPEAK_ROOT . '/src/App/Services/CatalogImageUpload.php';
    $isBrand = isset($_POST['ADD']);
    $label = trim((string) ($_POST[$isBrand ? 'brand_Name' : 'Sub_Category'] ?? ''));
    $directory = SNEAKERPEAK_ASSETS . '/image/logo/';
    $logo = null;
    try {
        if ($label === '') throw new DomainException('Enter a name.');
        if ($isBrand) {
            $logo = \SneakerPeak\App\Services\CatalogImageUpload::save($_FILES['logo_img'] ?? [], $directory);
            $conn->execute_query('INSERT INTO E_Brand (Name, LogoPath) VALUES (?, ?)', [$label, $logo]);
        } else {
            $conn->execute_query('INSERT INTO E_SubCategory (Name) VALUES (?)', [$label]);
        }
        $message = $isBrand ? 'Brand added.' : 'Subcategory added.';
    } catch (Throwable $error) {
        if ($logo !== null) unlink($directory . $logo);
        $message = $error instanceof DomainException ? $error->getMessage()
            : ($error->getCode() === 1062 ? 'That name already exists.' : 'Could not save the entry.');
    } ?>
    <script>Swal.fire({title: <?= json_encode($message, JSON_HEX_TAG) ?>});</script>
<?php } ?>
</body>
</html>
