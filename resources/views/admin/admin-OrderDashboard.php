<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <title>Order Dashboard | Sneaker Peak</title>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="css/Order.css">
    <link rel="stylesheet" href="css/admin_prodlist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
  <body class="admin-workspace">
    <?php
    include SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php';
    ?>
    <section class="home-section">
      <header class="home-content"><h1>Order Dashboard</h1></header>
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
        <h2>Orders: <?= (int) $totalOrders ?></h2>
          <p><?= htmlspecialchars($start) ?> to <?= htmlspecialchars($end) ?></p>
      </section>
          <section class="formclase">
            <form action="admin-OrderDashboard.php" method="post">
              <div><label for="order-start">Start date</label><input type="date" name="date1" id="order-start" value="<?= htmlspecialchars($start) ?>" required></div>
              <div><label for="order-end">End date</label><input type="date" name="date2" id="order-end" value="<?= htmlspecialchars($end) ?>" required></div>
              <input type="submit" value="Search" name="date">
            </form>
          </section>
          <section class="table__body">
            <div class="chart-container">
              <canvas id="orderChart" role="img" aria-label="Orders by date"></canvas>
            </div>
          </section>
        </div>
      </div>
    </section>
    <script>
      // Get the order data from PHP
      var orderData = <?php echo json_encode($orderData, JSON_HEX_TAG); ?>;
      
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
          maintainAspectRatio: false,
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
  
    </script>
  </body>
</html>
