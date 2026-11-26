<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    
    <link rel="stylesheet" href="css/admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/admin_prodlist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
       select, input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

      
        .Filte
        {
          padding-left: 50px;
        }
       
    </style>
   <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
<?php
  require_once SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php';
?>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">Drop Down Sidebar</span>
    </div>
    <div class="contenbar">
      <div class="table">
        <section class="table__header">
             <h1>Product List </h1> 
         </section>
         <section class="Filte">
         <div class="Filter">
              <form method="get">
                  <select name="status">
                    <option value=""  <?php if ($status == '') echo 'selected'; ?>>All</option>
                    <option value="available"<?php if ($status == 'available' || $status == '') echo ' selected'; ?>>Available</option>
                    <option value="out-of-stock"<?php if ($status == 'out-of-stock') echo ' selected'; ?>>Out of stock</option>
                    <option value="deleted"<?php if ($status == 'deleted') echo ' selected'; ?>>Deleted</option>
                  </select>
                    <select name="brand">
                        <option value="">All</option>
                        <?php foreach ($brands as $rows): ?>
                        <option value="<?php echo $rows['Brand_Name'] ?>"<?php if ($brand == 'Brand1') echo ' selected'; ?>><?php echo $rows['Brand_Name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by product name">
                    <button type="submit"><i class="fa fa-search"></i></button>
              </form>
              </div>
         </section>
         <section class="table__body">
            
              
             <table>
                 <thead>
                     <tr>
                        
                         <th> Product Name <span class="icon-arrow"></span></th>
                         <th> Product Brand <span class="icon-arrow"></span></th>
                         <th> Product Price <span class="icon-arrow"></span></th>
                         <th> Product Colour <span class="icon-arrow"></span></th>
                         <th> Category <span class="icon-arrow"></span></th>
                         <th>Sub Category<span class="icon-arrow"></span></th>
                         <th> Product Image <span class="icon-arrow"></span></th>
                         <th> Status<span class="icon-arrow"></span></th>
                         <th>Info<span class="icon-arrow"></span></th>
                     </tr>
                 </thead>
                 <tbody>
                        <?php
                        
                        foreach ($products as $row) {
                          $prodName = $row['Prod_Name'];
                          ?>
                          
                          <td><?php echo $row['Prod_Name'] ?></td>
                          <td><?php echo $row['Prod_Brand'] ?></td>
                          <td><?php echo number_format(  $row['Prod_Price'],2); ?></td>
                          <td><?php echo $row['Prod_Colour'] ?></td>
                          <td><?php echo $row['Prod_Cetegory'] ?></td> 
                          <td><?php echo $row['Prod_SubC'] ?></td>
                          <td><img src="prod/<?php  echo $row ['Prod_img1']; ?>" alt=""></td>
                          <td><?php echo $row['Prod_Status'] ?></td>
                          <td>
                            <div class="button">
                                <div class="btnst">
                                    <ul>
                                        <li>
                                            <div class="view">
                                            <button title="View product details" data-product="<?= htmlspecialchars($prodName) ?>" onclick="viewProductDetails(this.dataset.product)"><i class='bx bx-search-alt-2'></i></button>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="delete">  
                                                <button title="delete product" onclick="deleteItem('<?php echo $row['Prod_ID'] ?>')"><i class='bx bxs-message-square-x'></i></button>
                                            </div>
                                        </li>
                                    </ul>
                                    
                                    
                                </div>
                          </div>
                          
                          <script>
                            function viewProductDetails(prodName) {
                                  window.location.href = "admin_productdetails.php?prodName=" + encodeURIComponent(prodName);
                                }
               
                            function deleteItem(id) {
                                    // confirm with the user before deleting the item
                                    if (confirm("Are you sure you want to delete this item?")) {
                                        // send an HTTP GET request to the PHP script
                                        var xhr = new XMLHttpRequest();
                                        xhr.open("POST", "delete_item.php", true);
                                        xhr.onreadystatechange = function() {
                                            if (xhr.readyState === 4 && xhr.status === 200) {
                                                // handle the response from the PHP script
                                                alert(xhr.responseText);
                                                // reload the page to show the updated list of items
                                                location.reload();
                                            }
                                        };
                                        xhr.send(new URLSearchParams({ id, catalog_token: <?= json_encode($catalogToken) ?> }));
                                    }
                                }
                              
                                
                          </script>
                        </td>
                          </tr> 
                          <?php
                          
                        }

                        ?>
                 </tbody>
             </table>
             
         </section>
      </div>
    </div>
    

  </section>
  <script>
  let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e)=>{
   let arrowParent = e.target.parentElement.parentElement;
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
