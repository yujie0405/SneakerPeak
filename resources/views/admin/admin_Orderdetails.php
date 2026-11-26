<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <title>Order Details | Sneaker Peak</title>
    <meta charset="UTF-8">

    
    <link rel="stylesheet" href="css/admin_productd.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      
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

      #Code{
        color: red;
      }
      .product
      {
        display: flex;
      }
      .product h3
      {
        padding-top: 25px;
      }
      .Process
      {
        padding-left: 20px;
        
      }
      .butn
      {
        display: flex;
      }
      .butn button
       {
       margin-right: 10px;
       width: 150px; 
       height: 50px;  
       background: #0b0b49;
       color: white;
      }
      .butn button:hover
      {
        background: #131399;
      }
      .butn button:active
      {
        background: #0b0b49;
      }
    </style>
   <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
<body class="admin-workspace">
  <?php
    require_once SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php';
  ?>
  <section class="home-section">
    <header class="home-content"><h1>Order Details</h1></header>
    <div class="contenbar">
      <div class="table">
        <section class="table__header">
          <h1>Order Details  </h1>
          <h1>Order Code: <span id="Code">#<?= htmlspecialchars($orderCode) ?></span></h1>
        </section>
        
        <section class="table__body">
          <div class="proddtails">
            <div class="admin-table-scroll" tabindex="0" role="region" aria-label="Scrollable table"><table>
              <thead>
                <tr>
                  <th>S.N <span class="icon-arrow"></span></th>
                  <th>Product <span class="icon-arrow"></span></th>
                  <th>Price <span class="icon-arrow"></span></th>
                  <th>Qty <span class="icon-arrow"></span></th>
                  <th>Size <span class="icon-arrow"></span></th>
                </tr>
              </thead>
              <tbody>
              <tr>
                  <?php
                     $count = 1;
                    foreach ($orderItems as $rows) {
                      $productPicture = $rows['Prod_img1'] ?? '';
                  ?>
                  <td><?=$count?></td>
                  <td>
                    <div class="product">
                      <img src="prod/<?php echo  $productPicture?>" alt="">
                      <h3><?php echo $rows['Order_Product'] ?></h3>
                    </div>
                  </td>
                  
                  <td>RM<?php echo $rows['Order_ProdcutP'] ?></td>
                  <td><?php echo $rows['Order_qty'] ?></td>
                  <td><?php echo $rows['Order_Size'] ?></td>
              </tr>
              <?php
                  $count = $count + 1; 
                    }
              ?>
              </tbody>
            </table></div>
            
          </div>
          <div class="Process">
          <div class="StatusInformation">
            <h1>Info</h1>
              <p>Total Amount:RM<?php echo number_format($cal['Order_TotalAmmount'],2); ?></p>
              <p>Shipping Fee:RM<?php echo $cal['ShippingFeed'] ?></p>
              <p>Sub Total Amount:RM<?php echo number_format(($cal['Order_TotalAmmount']+$cal['ShippingFeed']),2); ?></p>
              <hr>
              <p>Order Date:<?php echo $cal['Order_Date'] ?></p>
              <p>Payment Type:<?php echo $cal['Payment_Type'] ?></p>
              <p>Address:<?= htmlspecialchars($call['Order_Address'] ?? '') ?></p>
              <p>Receiver Name:<?= htmlspecialchars($call['Receiver_Name'] ?? '') ?></p>
              <p>Receiver Contact Number:<?= htmlspecialchars($call['Receiver_Contract'] ?? '') ?></p>
          </div>
          <br>
          <br>
          <h1>Action</h1>
          <hr>
          <br>
          <div class="butn">
              <button onclick="confirmShipping('<?php echo $orderCode; ?>')">Shipping</button>
              <button onclick="confirmTellUser('<?php echo $orderCode; ?>')">Tell Customer To Complete</button>
              <script>
                function confirmTellUser(orderCode) {
                  swal({
                    title: "Are you sure?",
                    text: "Do you want to tell the user to complete the order?",
                    icon: "warning",
                    buttons: ["Cancel", "Yes"],
                    dangerMode: true,
                  }).then((confirmed) => {
                    if (confirmed) {
                      // Update the 'ask' field in the order table
                      updateAskStatus(orderCode);
                    }
                  });
                }

                function updateAskStatus(orderCode) {
                  var xhr = new XMLHttpRequest();
                  xhr.open("POST", "telluser.php", true);
                  xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                      // handle the response from the PHP script
                      swal({
                        title: "User has been notified to complete the order",
                        icon: "success",
                      }).then(function () {
                        // Reload the page after the user clicks "OK"
                        location.reload();
                      });
                    }
                  };
                  xhr.send(new URLSearchParams({ orderCode, order_token: <?= json_encode($token) ?> }));
                }
              </script>

              <script>
                function confirmShipping(orderCode) {
                  swal({
                    title: "Are you sure?",
                    text: "Do you want to update the Order Status to Shipping?",
                    icon: "warning",
                    buttons: ["Cancel", "Yes"],
                    dangerMode: true,
                  }).then((confirmed) => {
                    if (confirmed) {
                      // Update the Order Status in the database
                      updateOrderStatus(orderCode);
                    }
                  });
                }

                function updateOrderStatus(orderCode) {
                      var xhr = new XMLHttpRequest();
                      xhr.open("POST", "changestatus.php", true);
                      xhr.onreadystatechange = function() {
                          if (xhr.readyState === 4 && xhr.status === 200) {
                              // handle the response from the PHP script
                              swal({
                                  title: "Order is Shipping to Customer",
                                  icon: "success",
                              }).then(function () {
                                  // Reload the page after the user clicks "OK"
                                  location.reload();
                              });
                          }
                      };
                      xhr.send(new URLSearchParams({ orderCode, order_token: <?= json_encode($token) ?> }));
                  }

              </script>
              <button onclick="goBack()">Back to Order List</button>
          </div>
        </div>
        </section>
      </div>
    </div>
  </section>
  <script>
    function goBack() {
      window.history.back();
    }
  </script>

</body>
</html>
