<?php
include('database/config.php');

//total sales
$currentDate = date('d-m-Y');
$weekStart = date('d-m-Y', strtotime('monday this week', strtotime($currentDate)));
$weekEnd = date('d-m-Y', strtotime('sunday this week', strtotime($currentDate)));

$sql = "SELECT SUM(Order_TotalAmmount) AS TotalEarnings FROM oder WHERE Order_Date BETWEEN '$weekStart' AND '$weekEnd'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$totalEarnings = $row['TotalEarnings'];

$prodict = "SELECT COUNT(Order_Product) AS TotalProductsSold FROM oder_details WHERE Order_Day BETWEEN '$weekStart' AND '$weekEnd'";
$result = mysqli_query($conn, $prodict);
$row = mysqli_fetch_assoc($result);
$totalProductsSold = $row['TotalProductsSold'];

$customer = "SELECT COUNT(*) AS TotalCustomers FROM customer";
$result = mysqli_query($conn, $customer);
$row = mysqli_fetch_assoc($result);
$totalCustomers = $row['TotalCustomers'];

$totalproc = "SELECT COUNT(*) AS TotalProducts FROM product";
$result = mysqli_query($conn, $totalproc);
$row = mysqli_fetch_assoc($result);
$totalProducts = $row['TotalProducts'];


$famus= "SELECT Order_Product, COUNT(*) AS OrderCount
        FROM oder_details
        GROUP BY Order_Product
        ORDER BY OrderCount DESC
        LIMIT 3"; // Adjust the LIMIT value to display the desired number of top products

$resull = mysqli_query($conn, $famus);


if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
  // Get the start and end dates from the user input
  $start_date = date('d-m-Y', strtotime($_POST['start_date']));
  $end_date = date('d-m-Y', strtotime($_POST['end_date']));

  // Prepare the SQL query with the user-provided date range
  $queryt = "SELECT Order_Date AS order_date, SUM(Order_TotalAmmount) AS total_sales FROM oder WHERE Order_Date BETWEEN '$start_date' AND '$end_date' GROUP BY Order_Date";
} else {
  // If no date range is provided, display data for the current week
  $currentDate = date('d-m-Y');
  $weekStart = date('d-m-Y', strtotime('monday this week', strtotime($currentDate)));
  $weekEnd = date('d-m-Y', strtotime('sunday this week', strtotime($currentDate)));
  
  // Prepare the SQL query for the current week
  $queryt = "SELECT Order_Date AS order_date, SUM(Order_TotalAmmount) AS total_sales FROM oder WHERE Order_Date BETWEEN '$weekStart' AND '$weekEnd' GROUP BY Order_Date";
}

// Assuming you have a valid database connection, execute the query using mysqli
$resultt = mysqli_query($conn, $queryt);
$resultst = [];
while ($row = mysqli_fetch_assoc($resultt)) {
  $resultst[] = $row;
}

// Prepare the data for the chart
$labels = [];
$data = [];
foreach ($resultst as $roii) {
  $labels[] = $currentDate;
  $data[] = $roii['total_sales'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>View Customers</title>
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="css/admin_prodlist.css">
  <link rel="stylesheet" href="css/admin.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
  <style>


          .contenbar
          {
            height: 1000px;
          }
            .table__heade {
            width: 100%;
            height: auto;
            background-color: #fff4;
            padding: .8rem 1rem;
            font-size: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table__bod {
            
            height: 1000px;
            padding-left: 10px;
            background: white;

            margin: .8rem auto;
            border-radius: .6rem;

         
        }
        .tabl
        {
          width: 99%;
          height: 1000px;
          background-color: #fff5;
          

          backdrop-filter: blur(7px);
          box-shadow: 0 .4rem .8rem #0005;
          border-radius: .8rem;

          overflow: hidden;
        }
     .counte {
      display: flex;
      justify-content: space-between; /* Add spacing between the elements */
    }
    .board {
      padding: 6px;
      border: 1px solid gray;
      backdrop-filter: blur(7px);
      box-shadow: 0 .4rem .8rem #0005;
      border-radius: .8rem;
      width: 400px;
      margin-right: 10px; /* Add margin to create spacing */
      margin-right: 10px; /* Add margin to create spacing */
      transition: transform 0.3s ease-in-out;
    }
    .board:hover {
      transform: scale(1.1); /* Apply scale transformation on hover */
    }
    .t{
      padding: 10px;
    }
    .topprod
    {
      display: flex;
    }
    .product
    {
      display: flex;
      padding: 20px;
    }
    .product .img img
    {
      height: 80px;
      width: 80px;
      border-radius: 8px;
    }
    .product .pname
    {
      padding-left: 20px;
    }
    .form
    {
      padding-left: 8px;
    }
    .graph
    {
      justify-content: center;
      padding-left: 100px;
      padding-top: 30px;
      padding-bottom: 20px;
    }
    .chart-container {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 410px; /* Adjust the height as needed */
    }
  </style>
</head>
<body>
<?php 
  session_start();
  require_once("admin_sidebar.php"); ?>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu'></i>
      <span class="text">DashBoard</span>
    </div>
    <div class="contenbar">
      <div class="tabl">
        <section class="table__heade">
          <h1>Information</h1> 
        </section>
        <section class="table__bod">
          <div class="counte">
            <div class="board">
              <div class="t">
                  <h1> Total Sales This Week:RM<?php echo number_format(  $totalEarnings,2); ?></h1>
              </div>
            </div>
            <div class="board">
              <div class="t">
                <h1>Product Sold :<?php echo $totalProductsSold?></h1>
              </div>
            </div>
          </div>
          <br>
          <div class="counte">
            <div class="board">
              <div class="t">
                  <h1> Total Customer:<?php echo $totalCustomers?></h1>
              </div>
            </div>
            <div class="board">
              <div class="t">
                <h1>Total Product :<?php echo $totalProducts?></h1>
              </div>
            </div>
          </div>   
          <br>
          <h1>Top Sales Product</h1>
          <div class="topprod">
          <?php while($roww = mysqli_fetch_assoc($resull)){
                $prodnn=$roww['Order_Product'];

                $picture="SELECT Prod_img1 FROM product WHERE Prod_Name='$prodnn'";
                $call=mysqli_query($conn,$picture);
                while($rola = mysqli_fetch_assoc($call)){
                ?>

            <div class="board">
              <div class="product">
                <div class="img">
                  <img src="prod/<?php echo $rola['Prod_img1'];?>" alt="">
                </div>
                
                <div class="pname">
                  <h3><?php echo $prodnn ?></h3>
                </div>
              </div>
            </div>
           <?php }}?>
          </div>
          <br>
          <br>
          <div class="form">
            <h1>Select The Date To View Sales Report</h1>
            <form method="POST" action="">
              <label for="start_date">Start Date:</label>
              <input type="date" id="start_date" name="start_date">
              <label for="end_date">End Date:</label>
              <input type="date" id="end_date" name="end_date">
              <button type="submit" style="width:60px;height:30px">Search</button>
          </form>
          <h3><?php echo $weekStart ?>~~<?php echo $weekEnd ?></h3>
          </div>
          <div class="graph">
             <div class="chart-container">
             <canvas id="lineChart"></canvas>
            </div>
          </div>
          <br>
          <br>
          <br>
        </section>
      </div>
    </div>
  </section>
  <script>
        var ctx = document.getElementById('lineChart').getContext('2d');
        var lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Total Sales',
                    data: <?php echo json_encode($data); ?>,
                    borderColor: 'red',
                    fill: false
                }]
            },
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
                            text: 'Total Sales'
                        }
                    }
                }
            }
        });
    </script>
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
