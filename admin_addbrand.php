<?php
include('database/connect.php');
session_start();

?>
<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
  
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/addbrand.css">
  
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
<body>
  <?php
    require_once ("admin_sidebar.php");
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
                    $query = "select * from brand";
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
                    $query = "select * from sub_category";
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
    if (isset($_POST['ADD'])) {
      $B_Name = $_POST['brand_Name'];
  
      // Check if the brand name already exists
      $check_query = mysqli_query($conn, "SELECT Brand_Name FROM brand WHERE Brand_Name = '$B_Name'");
      if (mysqli_num_rows($check_query) > 0) {
          // Brand name already exists
          ?>
          <script>
             Swal.fire({
              title: 'Error',
              text: "The Brand You Enter Already Exist!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.isConfirmed) {
                Swal.fire(
                  'Deleted!',
                  'Your Brand has been deleted.',
                  'success'
                )
              }
            })
          </script>
          <?php
      } else {
          $B_logo = $_FILES['logo_img']['name'];
          $B_logo_tmp = $_FILES['logo_img']['tmp_name'];
  
          $img_upload_path = 'image/logo/';
  
          move_uploaded_file($B_logo_tmp, $img_upload_path . $B_logo);
  
          $brand = mysqli_query($conn, "INSERT INTO brand (Brand_Name, Brand_logo) VALUES ('$B_Name', '$B_logo')");
          if ($brand) {
            ?>
              <script>
                   Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'The Brand Had Been Added',
                showConfirmButton: false,
                timer: 1500
                }).then(() => {
                    window.location = "admin_addbrand.php";
                });
              </script>
              
            <?php
          } 
      }
  }
  

  ?>
  <?php
  if (isset($_POST['ADD_SUB'])) {
  $S_C = $_POST['Sub_Category'];

  // Check if the subcategory already exists
  $existingSubCategoryQuery = "SELECT * FROM sub_category WHERE S_Name = '$S_C'";
  $existingSubCategoryResult = mysqli_query($conn, $existingSubCategoryQuery);

  if (mysqli_num_rows($existingSubCategoryResult) > 0) {
    ?>
      
     
      <script>
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Something went wrong! The Sub Category Your Enter Already Exist',
         
        })
      </script>
      <?php 
  } else {
   
      // Subcategory does not exist, proceed with insertion
      $sc = mysqli_query($conn, "INSERT INTO sub_category(S_Name) VALUES ('$S_C')");
      ?>
     
    
    <script>
        Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'The SubCategory Add Sucessfully',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location = "admin_addbrand.php";
            });
      </script>
     <?php 
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
