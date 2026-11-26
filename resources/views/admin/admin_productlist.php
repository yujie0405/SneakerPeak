<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <title>Products | Sneaker Peak</title>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/admin_prodlist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
<body class="admin-workspace">
<?php
  require_once SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php';
?>
  <section class="home-section">
    <header class="home-content"><h1>Products</h1></header>
    <div class="contenbar">
      <div class="table">
        <section class="table__header">
             <h1>Product List </h1> 
         </section>
         <section class="Filter" aria-label="Product filters">
              <form method="get">
                  <div><label for="product-status">Status</label>
                  <select name="status" id="product-status">
                    <option value=""  <?php if ($status == '') echo 'selected'; ?>>All</option>
                    <option value="available"<?php if ($status == 'available') echo ' selected'; ?>>Available</option>
                    <option value="out-of-stock"<?php if ($status == 'out-of-stock') echo ' selected'; ?>>Out of stock</option>
                    <option value="deleted"<?php if ($status == 'deleted') echo ' selected'; ?>>Deleted</option>
                  </select></div>
                    <div><label for="product-brand">Brand</label>
                    <select name="brand" id="product-brand">
                        <option value="">All</option>
                        <?php foreach ($brands as $rows): ?>
                        <option value="<?= htmlspecialchars($rows['Brand_Name']) ?>"<?= $brand === $rows['Brand_Name'] ? ' selected' : '' ?>><?= htmlspecialchars($rows['Brand_Name']) ?></option>
                        <?php endforeach; ?>
                    </select></div>
                    <div><label for="product-search">Product name</label>
                    <input type="text" id="product-search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by product name"></div>
                    <button type="submit">Search</button>
              </form>
         </section>
         <section class="table__body">
            
              
             <div class="admin-table-scroll" tabindex="0" role="region" aria-label="Scrollable table"><table>
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
                                            <button title="View product details" data-product="<?= htmlspecialchars($prodName) ?>" onclick="viewProductDetails(this.dataset.product)">View</button>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="delete">  
                                                <button title="delete product" onclick="deleteItem('<?php echo $row['Prod_ID'] ?>')">Delete</button>
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
             </table></div>
             
         </section>
      </div>
    </div>
    

  </section>

</body>
</html>
