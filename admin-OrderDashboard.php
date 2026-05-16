<?php
include('database/connect.php');
session_start();
$query="SELECT * FROM oder";
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

$currentDate = date('d-m-Y');
$weekStart = date('d-m-Y', strtotime('monday this week', strtotime($currentDate)));
$weekEnd = date('d-m-Y', strtotime('sunday this week', strtotime($currentDate)));

// Construct the SQL query

// ...

// Check if the form is submitted
if (isset($_POST['date'])) {
  // Get the selected dates from the form
 // Get the selected dates from the form
  $date1 = date('d-m-y', strtotime($_POST['date1']));
  $date2 = date('d-m-y', strtotime($_POST['date2']));

  // Construct the SQL query with the selected dates
  $query = "SELECT COUNT(*) AS total_orders FROM oder WHERE Order_Date BETWEEN '$date1' AND '$date2'";
  $querli = "SELECT Order_Date, COUNT(*) AS total_orders FROM oder WHERE Order_Date BETWEEN '$date1' AND '$date2' GROUP BY Order_Date";
} else {
  // Construct the SQL query with the default week range
  $query = "SELECT COUNT(*) AS total_orders FROM oder WHERE Order_Date BETWEEN '$weekStart' AND '$weekEnd'";
  $querli = "SELECT Order_Date, COUNT(*) AS total_orders FROM oder WHERE Order_Date BETWEEN '$weekStart' AND '$weekEnd' GROUP BY Order_Date";
}

// Execute the query
$result = mysqli_query($conn, $query);
$resulted = mysqli_query($conn, $querli);

// Check if the query was successful
if ($result) {
  // Fetch the result row
  $row = mysqli_fetch_assoc($result);
  
  // Access the total_orders value
  $totalOrders = $row['total_orders'];
  
  // Display the total orders for the current week
  
} else {
  // Query execution failed
  echo "Error: " . mysqli_error($conn);
}
if ($resulted) {
  // Fetch the result rows and store them in an array
  $orderData = [];
  while ($row = mysqli_fetch_assoc($resulted)) {
      $orderData[] = $row;
  }
} else {
  // Query execution failed
  echo "Error: " . mysqli_error($conn);
}

// ...






?>


<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!--<title> Drop Down Sidebar Menu | CodingLab </title>-->
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/Order.css">
    <link rel="stylesheet" href="css/admin_prodlist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      .chart-container {
        width: 100%;
        height: 100%; /* Adjust the height as needed */
      }
      .formclase
      {
        padding-left: 30px;
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
        <span class="text">Order Dashboard </span>
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
        <?php if (isset($_POST['date'])): ?>
          <h1>Total Order Between <?php echo $totalOrders ?></h1>
        <?php else: ?>
          <h1>Total Order Of this Week <?php echo $totalOrders ?></h1>
        <?php endif; ?>
        <p>
          <?php if (isset($_POST['date'])): ?>
            Week: <?php echo $date1 ?> ~ <?php echo $date2 ?>
          <?php else: ?>
            Week: <?php echo $weekStart ?> ~ <?php echo $weekEnd ?>
          <?php endif; ?>
        </p>
      </section>
          <section class="formclase">
            <form action="admin-OrderDashboard.php" method="post">
              <input type="date" name="date1" id="" required>
              <input type="date" name="date2" id="" required>
              <input type="submit" value="Search" name="date">
            </form>
          </section>
          <section class="table__body">
            <div class="chart-container">
              <canvas id="orderChart"></canvas>
            </div>
          </section>
        </div>
      </div>
    </section>
    <script>
      // Get the order data from PHP
      var orderData = <?php echo json_encode($orderData); ?>;
      
      // Prepare the data for the chart
      var chartData = {
        labels: orderData.map(data => data.Order_Date),
        datasets: [{
          label: 'Total Orders',
          data: orderData.map(data => data.total_orders),
          backgroundColor: 'rgba(75, 192, 192, 0.6)', // Adjust the color as needed
          borderColor: 'rgba(75, 192, 192, 1)', // Adjust the color as needed
          borderWidth: 1
        }]
      };
  
      // Get the canvas element
      var ctx = document.getElementById('orderChart').getContext('2d');
  
      // Create the bar chart
      new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
          responsive: true,
          scales: {
            x: {
              display: true,
              title: {
                display: true,
                text: 'Order Date'
              }
            },
            y: {
              display: true,
              title: {
                display: true,
                text: 'Total Orders'
              },
              beginAtZero: true
            }
          }
        }
      });
    </script>
    <script>
      function viewOrder(orderCode) {
        window.location.href = "admin_OrderDetails.php?orderCode=" + orderCode;
      }
  
      let arrow = document.querySelectorAll(".arrow");
      for (var i = 0; i < arrow.length; i++) {
        arrow[i].addEventListener("click", (e)=>{
          let arrowParent = e.target.parentElement.parentElement; // Selecting the main parent of the arrow
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
