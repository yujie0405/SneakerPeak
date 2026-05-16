<?php
include('database/connect.php');
session_start();
$query="SELECT *
FROM oder
ORDER BY 
  CASE 
    WHEN Order_Status = 'pending' THEN 1
    WHEN Order_Status = 'Shipped' THEN 2
    WHEN Order_Status = 'Complete' THEN 3
    ELSE 4
  END;";
$result=mysqli_query($conn,$query);

$cal = "SELECT COUNT(*) AS total_processing FROM oder WHERE Order_Status='Pending'";
$Ans= mysqli_query($conn, $cal);
$rowr = mysqli_fetch_assoc($Ans);
$totalPending = $rowr['total_processing'];


$ship="SELECT COUNT(*) AS Shipping FROM oder WHERE Order_Status='Shipping'";
$SHIPP=mysqli_query($conn,$ship);
$ro=mysqli_fetch_assoc($SHIPP);
$totalShipping=$ro['Shipping'];

$comp="SELECT COUNT(*) AS Complete FROM oder WHERE Order_Status='Complete'";
$complete=mysqli_query($conn,$comp);
$roll=mysqli_fetch_assoc($complete);
$totalComplete=$roll['Complete'];

?>


<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <!--<title> Drop Down Sidebar Menu | CodingLab </title>-->
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/Order.css">
    <link rel="stylesheet" href="css/admin_prodlist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <style>
       #Shipping {
          display: inline-block;
          padding: 5px 10px;
          background-color: rgb(170, 243, 0);
          color: black;
          font-weight: bold;
          border-radius: 4px;
          transition: transform 0.3s ease;
        }

        #Shipping:hover {
          transform: scale(1.1);
        }
        .alert {
          background-color: #ccc; /* Change the background color to a desired read-only style */
         
          
          opacity: 0.7; /* Adjust the opacity to give a visual cue of the button being disabled */
        }
        .Filter
        {
          padding-left: 50px;
          
        }
        .Filter select
        {
          width: 400px;
          height: 40px;
        }
       
     </style>
   </head>
<body>
 <?php
  include('admin_sidebar.php');
 ?>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">Order List</span>
    </div>
    <div class="statusbox">
      <div class="bigbox">
        <div class="Processing">
          <div class="processimg">
            <img src="image/logo/processing.png" alt="">
          </div>
          <div class="proceP">
            <h2>Processing: <span class="Num"><?php echo $totalPending ?></span> </h2>
          </div>
        </div>
        <div class="Processing">
          <div class="shippingimg">
            <img src="image/logo/shipping.png" alt="">
          </div>
          <div class="proceP">
            <h2>Shipped: <span class="Num"><?php echo $totalShipping?></span> </h2>
          </div>
        </div>
        <div class="Processing">
          <div class="completeimg">
            <img src="image/logo/complete.png" alt="">
          </div>
          <div class="proceP">
            <h2>Complete: <span class="Num"><?php echo $totalComplete?></span> </h2>
          </div>
        </div>
      </div>
    </div>
    <div class="contenbar">
      <div class="table">
        <section class="table__header">
             <h1>Order List </h1> 
         </section>
         <!-- <section class="Filter">
          <form action="admin-Orderlist.php" method="post">
            <label for="Order_Status"><h1>Order Status:</h1></label>
            <select name="Order_Status" id="">
              <option value="">All</option>
              <option value="Pending">Pending</option>
              <option value="Shiped">Shipped</option>
              <option value="Complete">Complete</option>
            </select>
          </form>
         </section> -->
         <section class="table__body">
             <div class="Filter">
                <form method="get">
                  
                </form>
              </div>
              
              <table>
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Order Code</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Payment Type</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
        <tbody class="table__body">
        <?php
                         $count = 1;
                        while($row = mysqli_fetch_assoc($result)){
                          $_SESSION["Order_Code"]=$row['Order_code']; 
                          ?>
          <tr>
            <td><?=$count?></td>
            <td><?php echo $row['Order_code']?></td>
            <td><?php echo $row['Order_Date']?></td>
            <td>RM<?php echo number_format(  $row['Order_TotalAmmount'],2); ?></td>
            <td><?php echo $row['Payment_Type']?></td>
            <td>
            <?php
             if($row['Payment_Status']=='Paying'){
            ?>
              <span id="processing">Pending</span>
             <?php }else{?>
              <span id="complete">Complete</span>
             <?php } ?>
            </td>
            <td>
            <?php
             if($row['Order_Status']=='Pending'){
            ?>
            <span id="processing">Pending</span></td>
              <?php }elseif($row['Order_Status']=='Shipped'){ ?>
                <span id="Shipping">Shipped</span>
                <?php }else{ ?>

                  <span id="complete">Complete</span>
                  <?php }?>
            <td>
            <?php
             if($row['Payment_Status']=='Paying'){
            ?>
           <div class="buttonbox">
            <button title="View Order" class="alert" onclick="showPaymentAlert()">
              <i class="fa fa-search" style="color: white;"></i>
            </button>
          </div>
            <?php }else{ ?>
              <div class="buttonbox">
              <button title="View Order" onclick="viewOrder('<?php echo $row['Order_code']; ?>')">
                <i class="fa fa-search" style="color: white;"></i>
              </button>
            </div>
          <?php }?>
            </td>
           
          </tr>
          <?php
                      $count = $count + 1;    
                        }

                        ?>
        </tbody>
      </table>
             
         </section>
      </div>
   
  </section>
  <script>
        function showPaymentAlert() {
      Swal.fire({
        title: 'Payment Pending',
        text: 'The customer has not made the payment yet.',
        icon: 'warning',
        confirmButtonText: 'OK'
      });
    }
    function viewOrder(orderCode) {
      window.location.href = "admin_OrderDetails.php?orderCode=" + orderCode;
    }



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
