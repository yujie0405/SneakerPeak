<?php
include('database/connect.php');
session_start();
$query = "SELECT Prod_Brand, COUNT(*) AS TotalCount FROM product GROUP BY Prod_Brand;";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Fetch the result rows and store them in an array
    $productData = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $productData[] = $row;
    }
} else {
    // Query execution failed
    echo "Error: " . mysqli_error($conn);
}


$quer = "SELECT Prod_Status, COUNT(*) AS TotalStatus FROM product GROUP BY Prod_Status;";
$resu = mysqli_query($conn, $quer);

// Check if the query was successful
if ($result) {
    // Fetch the result rows and store them in an array
    $productDat = [];
    while ($row = mysqli_fetch_assoc($resu)) {
        $productDat[] = $row;
    }
} else {
    // Query execution failed
    echo "Error: " . mysqli_error($conn);
}



$cal = "SELECT COUNT(*) AS total_processing FROM product WHERE Prod_Status='available'";
$Ans= mysqli_query($conn, $cal);
$rowr = mysqli_fetch_assoc($Ans);
$totalPending = $rowr['total_processing'];


$ship="SELECT COUNT(*) AS Shipping FROM product WHERE Prod_Status='Deleted'";
$SHIPP=mysqli_query($conn,$ship);
$ro=mysqli_fetch_assoc($SHIPP);
$totalShipping=$ro['Shipping'];

$comp="SELECT COUNT(*) AS Complete FROM product WHERE Prod_Status='Out of Stock'";
$complete=mysqli_query($conn,$comp);
$roll=mysqli_fetch_assoc($complete);
$totalComplete=$roll['Complete'];

$totalproduct="SELECT COUNT(*) AS TotalProduct FROM product";
$totalp=mysqli_query($conn,$totalproduct);
$real=mysqli_fetch_assoc($totalp);
$tp=$real['TotalProduct'];
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
      .graph {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        margin-top: 20px;
      }

      .chart-container {
        width: 600px;
        height: 500px;
      }
    </style>
</style>
  </head>
  <body>
    <?php
    include('admin_sidebar.php');
    ?>
    <section class="home-section">
      <div class="home-content">
        <i class='bx bx-menu' ></i>
        <span class="text">Product Dashboard </span>
      </div>
      <div class="statusbox">
        <div class="bigbox">
          <div class="Processing">
            <div class="completeimg">
              <img src="image/logo/icons8-ok-48.png" alt="">
            </div>
            <div class="proceP">
              <h2>Available: <span class="Num"><?php echo $totalPending ?></span> </h2>
            </div>
          </div>
          <div class="Processing">
            <div class="processimg">
              <img src="image/logo/icons8-delete-64.png" alt="">
            </div>
            <div class="proceP">
              <h2>Deleted: <span class="Num"><?php echo $totalShipping?></span> </h2>
            </div>
          </div>
          <div class="Processing">
            <div class="shippingimg">
              <img src="image/logo/icons8-out-of-stock-64.png" alt="">
            </div>
            <div class="proceP">
              <h2>Out of Stock: <span class="Num"><?php echo $totalComplete?></span> </h2>
            </div>
          </div>
        </div>
      </div>
      <div class="contenbar">
        <div class="table">
          <section class="table__header">
            <h1>Total Product: <?php echo $tp ?> </h1>
            
          </section>
          <section class="table__body">
            <div class="graph">
                <div class="chart-container">
                  <h1>Total Of Brand</h1>
                  <canvas id="productChart"></canvas>
                </div>
                <div class="chart-container">
                <h1>Total Of Status</h1>
                  <canvas id="statusChart"></canvas>
                </div>
            </div>
          </section>

        </div>
      </div>
    </section>
    <script>
  // Get the product data from PHP and convert it to JavaScript array
  var productData = <?php echo json_encode($productData); ?>;

  // Extract the brand names and counts from the product data
  var brandNames = productData.map(function(item) {
    return item.Prod_Brand;
  });

  var counts = productData.map(function(item) {
    return item.TotalCount;
  });

  // Remove the "undefined" brand name and count from the arrays
  var undefinedIndex = brandNames.indexOf("undefined");
  if (undefinedIndex !== -1) {
    brandNames.splice(undefinedIndex, 1);
    counts.splice(undefinedIndex, 1);
  }

  // Create the pie chart
  var ctx = document.getElementById('productChart').getContext('2d');
  var productChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: brandNames,
      datasets: [{
        data: counts,
        backgroundColor: [
          '#ff6384',
          '#36a2eb',
          '#ffce56',
          '#8c5fe3',
          '#2ecc71'
          // Add more colors as needed
        ],
      }]
    },
    options: {
      responsive: true,
      legend: {
        position: 'bottom',
      },
      layout: {
        padding: {
          left: 30,
          right: 30,
          top: 30,
          bottom: 30
        }
      }
    }
  });
</script>
<script>
  // Get the product data from PHP and convert it to JavaScript array
  var productData = <?php echo json_encode($productDat); ?>;

  // Extract the status and counts from the product data
  var statusLabels = productData.map(function(item) {
    return item.Prod_Status;
  });

  var statusCounts = productData.map(function(item) {
    return item.TotalStatus;
  });

  // Create the bar chart
  var ctx = document.getElementById('statusChart').getContext('2d');
  var statusChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: statusLabels,
      datasets: [{
        label: 'Total Count',
        data: statusCounts,
        backgroundColor: '#36a2eb',
        borderWidth: 1,
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          stepSize: 1
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
