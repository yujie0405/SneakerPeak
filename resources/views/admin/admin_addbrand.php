<?php
include SNEAKERPEAK_ROOT . '/database/connect.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

?>
<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <title>Brands and Categories | Sneaker Peak</title>
    <meta charset="UTF-8">
  
    <link rel="stylesheet" href="css/addbrand.css">
  
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
<body class="admin-workspace">
  <?php
    require_once SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php';
  ?>
  <section class="home-section">
    <header class="home-content"><h1>Brands and categories</h1></header>
    
    <div class="addbrand-box">
      <h1>Add Brand</h1>
      <div class="container">
        <div class="brand-inputbox">


        <form method="post" action="admin_addbrand.php" enctype="multipart/form-data">
          <div class="box">
            <label for="admin-field-brand_Name">Brand Name:</label>
            <input type="text" name="brand_Name" placeholder="Enter Brand Name" required id="admin-field-brand_Name">
          </div>
          <div class="box">
            <label for="admin-field-logo_img">Brand Logo image:</label>
            <input type="file" name="logo_img" required id="admin-field-logo_img">
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
              <div class="admin-table-scroll" tabindex="0" role="region" aria-label="Scrollable table"><table>
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
              </table></div>
            </div> 
          </div>
      </div>
    </div>


    <div class="addsubcateg-box">
      <h1>Add Sub-Category</h1>
      <div class="container">
        <div class="subcateg-inputbox">
        <form method="post" action="admin_addbrand.php">
          <div class="box">
            <label for="admin-field-Sub_Category">Sub-Category Name:</label>
            <input type="text" name="Sub_Category" placeholder="Enter Sub-Category" required id="admin-field-Sub_Category">
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
            <div class="admin-table-scroll" tabindex="0" role="region" aria-label="Scrollable table"><table>
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
            </table></div>
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
