<?php
  include('database/config.php');
  session_start();
  if(isset($_SESSION['prodName'])) {
    $prodName = $_SESSION['prodName'];
  }
  $pn=$prodName;
  $query = "SELECT * FROM `product` WHERE Prod_Name = '$pn'";
  $result = mysqli_query($conn,$query);
  $row= mysqli_fetch_assoc($result);

  if(isset($_POST['Update']))
  {
    $Prod_N=$_POST['productName'];
    $Prod_P=$_POST['productPrice'];
    $Prod_D=$_POST['productDetails'];

    $_SESSION['prodName'] = $Prod_N;

    $UPDATE= "UPDATE product SET Prod_Name = '$Prod_N', Prod_Price = '$Prod_P', Prod_Details = '$Prod_D' WHERE Prod_Name = '$pn'";
    $updat="UPDATE sizeandqty SET Prod_Name='$Prod_N' WHERE Prod_Name='$pn' ";
    $Result=mysqli_query($conn,$UPDATE);
    $result=mysqli_query($conn,$updat);
    if($Result && $result)
    {
      // Redirect to admin_productdetails.php
      
      header("Location: admin_productdetails.php");
      exit(); // Make sure to exit after the redirect
    }
  }

?>


<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <!--<title> Drop Down Sidebar Menu | CodingLab </title>-->
    <link rel="stylesheet" href="css/admin.css">

    <link rel="stylesheet" href="css/admin_productd.css">
    <!-- Boxiocns CDN Link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <style>
      .status_available
        {
            background: greenyellow;
            color: black;
            font-weight: bolder;
            border-radius: 8px;
            display: inline-block; /* Added display property */
            padding: 8px 16px; /* Added padding for height */
            line-height: 1; /* Added line-height to center text vertically */

        }
        .status_unavailable
        {
            background: red;
            color: white;
            font-weight: bolder;
            border-radius: 8px;
            display: inline-block; /* Added display property */
            padding: 8px 16px; /* Added padding for height */
            line-height: 1; /* Added line-height to center text vertically */
        }
        
.contenbar
{
    padding-top: 20px;
    padding-left: 20px;
    height: 100%;
    width: 98%;
    padding-bottom: 20px;
}


.table
{
    width: 99%;
    height: 90vh;
    background-color: #fff5;
    

    backdrop-filter: blur(7px);
    box-shadow: 0 .4rem .8rem #0005;
    border-radius: .8rem;

    overflow: hidden;
}

.table__header {
    width: 100%;
    height: 10%;
    background-color: #fff4;
    padding: .8rem 1rem;
    font-size: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table__body {
    width: 98%;
    max-height: calc(89% - 1.6rem);
    background-color: #fffb;

    margin: .8rem auto;
    border-radius: .6rem;

    overflow: auto;
    overflow: overlay;
}

.table__body::-webkit-scrollbar{
    width: 0.5rem;
    height: 0.5rem;
}

.table__body::-webkit-scrollbar-thumb{
    border-radius: .5rem;
    background-color: #0004;
    visibility: hidden;
}

.table__body:hover::-webkit-scrollbar-thumb{ 
    visibility: visible;
}

table {
    width: 100%;
}

tbody td img {
    width: 70px;
    height: 90px;
    margin-right: .5rem;
    border-radius: 8px;

    vertical-align: middle;
}

table, th, td {
    border-collapse: collapse;
    padding: 1rem;
    text-align: left;
}

thead th {
    position: sticky;
    top: 0;
    left: 0;
    background-color: #d5d1defe;
    cursor: pointer;
    text-transform: capitalize;
}

tbody tr:nth-child(even) {
    background-color: #0000000b;
}

tbody tr {
    --delay: .1s;
    transition: .5s ease-in-out var(--delay), background-color 0s;
}

tbody tr.hide {
    opacity: 0;
    transform: translateX(100%);
}

tbody tr:hover {
    background-color: #fff6 !important;
}

tbody tr td,
tbody tr td p,
tbody tr td img {
    transition: .2s ease-in-out;
}

tbody tr.hide td,
tbody tr.hide td p {
    padding: 0;
    font: 0 / 0 sans-serif;
    transition: .2s ease-in-out .5s;
}

tbody tr.hide td img {
    width: 0;
    height: 0;
    transition: .2s ease-in-out .5s;
}

.status {
    padding: .4rem 0;
    border-radius: 2rem;
    text-align: center;
}

.status.delivered {
    background-color: #86e49d;
    color: #006b21;
}

.status.cancelled {
    background-color: #d893a3;
    color: #b30021;
}

.status.pending {
    background-color: #ebc474;
}

.status.shipped {
    background-color: #6fcaea;
}


.button .btnst ul li
{
    list-style: none;
    padding-bottom: 10px;
}
.button .btnst ul li .view button
{
    background:rgb(78, 100, 246) ;
    border: 1px;
    border-radius: 6px;
}

.button .btnst ul li .delete button
{
    
    border: 0.2px black solid;
    border-radius: 6px;
}

.button .btnst ul li .view button:hover
{
    background: rgb(14, 44, 244);
}

.button .btnst ul li .delete button:hover
{
    background: rgba(133, 132, 132, 0.4);
}

.button .btnst ul li .delete button:active
{
    background: #fff5;
}


.button .btnst ul li .view button:active
{
    background: rgb(1, 13, 100);
    color: white;
}

.button .btnst ul li .view button i
{
 font-size: 20px;
  color: white;
  height: 50px;
  min-width: 35px;
  text-align: center;
  line-height: 35px;
 
}
.button .btnst ul li .view button i:active
{
    color: white;
}

@media (max-width: 1000px) {
    td:not(:first-of-type) {
        min-width: 12.1rem;
    }
}

thead th span.icon-arrow {
    display: inline-block;
    width: 1.3rem;
    height: 1.3rem;
    border-radius: 50%;
    border: 1.4px solid transparent;
    
    text-align: center;
    font-size: 1rem;
    
    margin-left: .5rem;
    transition: .2s ease-in-out;
}

thead th.active,tbody td.active {
    color: #6c00bd;
}

     </style>
   </head>
<body>
  <?php
    require_once("admin_sidebar.php");
  ?>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">Product Details</span>
    </div>
    <div class="contenbar">
      <div class="table">
        <section class="table__header">
          <h1>Product Details  </h1>
        </section>
        
        <section class="table__body">
          <div class="proddtails">
              <div class="prod_img">
                <div class = "img-container">
                 
                  <img src="prod/<?php  echo $row ['Prod_img1']; ?>" >
                </div>
                <div class = "hover-container">
                    <div><img src = "prod/<?php  echo $row ['Prod_img1']; ?>"></div>
                    <div><img src = "prod/<?php  echo $row ['Prod_img2']; ?>"></div>
                    <div><img src = "prod/<?php  echo $row ['Prod_img3']; ?>"></div>
                </div>
              </div>
              <div class="pn">
                  <div class="pname">
                    <h1><?php  echo $row ['Prod_Name']; ?></h1>
                    <h3>RM<?php  echo $row ['Prod_Price']; ?></h3>
                    <p style="max-width: 500px;"><?php  echo $row ['Prod_Details']; ?></p>
                  </div>
                  <div class="stock">
                    <table>
                      <thead>
                        <tr>
                          <th>Size</th>
                          <th>Qty</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                              $quer = "SELECT * FROM `sizeandqty` WHERE Prod_Name = '$pn' ORDER BY CAST(Prod_Size AS DECIMAL(10,2)) ASC";;
                              $resul = mysqli_query($conn,$quer);
                              

                              while($rows = mysqli_fetch_assoc($resul)){
                                $prodSize = $rows['Prod_Size'];
                                $prodQty = $rows['Prod_Qty'];

                                if ($prodQty == 0) {
                                  // Update Qty_Status to "UnAvailable"
                                  $updateQuery = "UPDATE sizeandqty SET Qty_Status = 'UnAvailable' WHERE Prod_Name = '$pn' AND Prod_Size = '$prodSize'";
                                  mysqli_query($conn, $updateQuery);
                                  

                                  
                                }
                           
                        ?>
                        <tr>
                          <td><?php echo $rows['Prod_Size'];?></td>
                          <td><?php echo $rows['Prod_Qty'];?></td>
                          <td>
                            <?php 
                                if($rows['Qty_status']=='UnAvailable'){
                            ?>
                            <span class="status_unavailable"><?php echo $rows['Qty_status'];?></span>
                            <?php }else{?>
                              <span class="status_available"><?php echo $rows['Qty_status'];?></span>
                              <?php } ?>
                          </td>
                          <td><button class="normal" onclick="updateQtyStatus('<?php echo $rows['Prod_Name']; ?>', '<?php echo $rows['Prod_Size']; ?>')">Update</button></td>

                        </tr>
                      </tbody>
                      <?php
                        };
               

                    ?>
                    </table>
                    
                  </div>
                  <br>
                  <h3>Status: <?php  echo $row ['Prod_Status']; ?></h3>
                  <div class="button-status">
                    
                    <button class="delete"onclick="deleteItem('<?php echo $row['Prod_ID'] ?>')">Delete</button>
                    <button class="normal"onclick="AvailableItem('<?php echo $row['Prod_ID'] ?>', '<?php echo $pn; ?>')">Available</button>
                    <button class="normal" onclick="showEditForm()">Edit</button>
                    <button class="normal" onclick="goBack()">Back</button>

                    <script>
                    function goBack() {
                      window.location.href = 'admin_productlist.php';
                    }
                    </script>
                  </div>
              </div>
          </div>
        </section>
        <div id="editFormOverlay" class="overlay"></div>
        <div id="editFormPopup" class="popup">
          <span class="close" onclick="hideEditForm()">&times;</span>
          <form method="post">
            <label for="productName">Product Name:</label>
            <input type="text" id="productName" name="productName" value="<?php echo $row['Prod_Name']?>" required>
            <label for="productPrice">Product Price:</label>
            <input type="text" id="productPrice" name="productPrice" value="<?php  echo $row ['Prod_Price']; ?>" required>
            <label for="productDetails">Product Details:</label>
            <textarea id="productDetails" name="productDetails"  required><?php  echo $row ['Prod_Details']; ?></textarea>    
            <input type="submit" value="Update" name="Update"></input>
          </form>
        </div>
      </div>
    </div>
  </section>
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


  
  function showEditForm() {
    let editFormOverlay = document.getElementById("editFormOverlay");
    let editFormPopup = document.getElementById("editFormPopup");
    editFormOverlay.style.display = "block";
    editFormPopup.style.display = "block";
  }
  function hideEditForm() {
  let productNameInput = document.getElementById("productName");
  let productPriceInput = document.getElementById("productPrice");
  let productDetailsInput = document.getElementById("productDetails");

  // Check if any of the input values have been changed
  if (
    productNameInput.value !== "<?php echo $row['Prod_Name']?>" ||
    productPriceInput.value !== "<?php echo $row['Prod_Price']?>" ||
    productDetailsInput.value !== "<?php echo $row['Prod_Details']?>"
  ) {
    // Prompt the user to confirm closing the form
    Swal.fire({
      title: "Are you sure?",
      text: "Are you sure you want to close the form? Your changes will be discarded.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
    }).then((result) => {
      if (result.isConfirmed) {
        // If the user confirms, close the form
        let editFormOverlay = document.getElementById("editFormOverlay");
        let editFormPopup = document.getElementById("editFormPopup");
        editFormOverlay.style.display = "none";
        editFormPopup.style.display = "none";
        location.reload(); // Refresh the page
      }
    });
  } else {
    // If no changes have been made, simply close the form
    let editFormOverlay = document.getElementById("editFormOverlay");
    let editFormPopup = document.getElementById("editFormPopup");
    editFormOverlay.style.display = "none";
    editFormPopup.style.display = "none";
  }
}

  function deleteItem(id) {
  // Display a SweetAlert confirmation dialog
  Swal.fire({
    title: "Are you sure?",
    text: "Are you sure you want to delete this item?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes",
  }).then((result) => {
    if (result.isConfirmed) {
      // User clicked "Yes" in the SweetAlert dialog

      // Send an HTTP GET request to the PHP script
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "delete_item.php?id=" + id, true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Handle the response from the PHP script
          alert(xhr.responseText);
          // Reload the page to show the updated list of items
          location.reload();
        }
      };
      xhr.send();
    }
  });
}
                                
                                
  </script>
  <script>
   function AvailableItem(id) {
  // Display a SweetAlert confirmation dialog
  Swal.fire({
    title: "Are you sure?",
    text: "Are you sure you want to update this item?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes",
  }).then((result) => {
    if (result.isConfirmed) {
      // User clicked "Yes" in the SweetAlert dialog

      // Send an HTTP GET request to the PHP script
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "Available_item.php?id=" + id, true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Handle the response from the PHP script
          alert(xhr.responseText);
          // Reload the page to show the updated list of items
          location.reload();
        }
      };
      xhr.send();
    }
  });
}
  </script>

  <script>
    const allHoverImages = document.querySelectorAll('.hover-container div img');
    const imgContainer = document.querySelector('.img-container');

    window.addEventListener('DOMContentLoaded', () => {
        allHoverImages[0].parentElement.classList.add('active');
    });

    allHoverImages.forEach((image) => {
        image.addEventListener('mouseover', () =>{
            imgContainer.querySelector('img').src = image.src;
            resetActiveImg();
            image.parentElement.classList.add('active');
        });
    });

    function resetActiveImg(){
        allHoverImages.forEach((img) => {
            img.parentElement.classList.remove('active');
        });
    }

    function updateQtyStatus(prodName, prodSize) {
  // Display a SweetAlert confirmation dialog
  Swal.fire({
    title: "Are you sure?",
    text: "Are you sure you want to update this item?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes",
  }).then((result) => {
    if (result.isConfirmed) {
      // User clicked "Yes" in the SweetAlert dialog

      // Send an HTTP GET request to the PHP script
      var xhr = new XMLHttpRequest();
      xhr.open(
        "GET",
        "update_status.php?prodName=" + prodName + "&prodSize=" + prodSize,
        true
      );
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Handle the response from the PHP script
          alert(xhr.responseText);
          // Reload the page to show the updated list of items
          location.reload();
        }
      };
      xhr.send();
    }
  });
}
  </script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</body>
</html>
