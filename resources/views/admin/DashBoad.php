<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
<?php 
  if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
  require_once SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
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
          <?php foreach ($topProducts as $product) { ?>

            <div class="board">
              <div class="product">
                <div class="img">
                  <img src="prod/<?php echo rawurlencode($product['Prod_img1']);?>" alt="">
                </div>
                
                <div class="pname">
                  <h3><?php echo htmlspecialchars($product['Order_Product']) ?></h3>
                </div>
              </div>
            </div>
           <?php } ?>
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
          <h3><?= htmlspecialchars($start) ?> to <?= htmlspecialchars($end) ?></h3>
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
                labels: <?php echo json_encode($labels, JSON_HEX_TAG); ?>,
                datasets: [{
                    label: 'Total Sales',
                    data: <?php echo json_encode($data, JSON_HEX_TAG); ?>,
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
