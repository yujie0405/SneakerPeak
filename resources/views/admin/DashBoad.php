<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="css/admin_prodlist.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 

<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
<body class="admin-workspace">
<?php 
  if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
  require_once SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
  <section class="home-section">
    <header class="home-content"><h1>Dashboard</h1></header>
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
          <h1>Top Sales Product</h1>
          <div class="topprod">
          <?php foreach ($topProducts as $product) { ?>

            <div class="board">
              <div class="product">
                <div class="img">
                  <img src="<?= htmlspecialchars(\SneakerPeak\Manager\CatalogImageUpload::url($product['Prod_img1'])) ?>" alt="">
                </div>

                <div class="pname">
                  <h3><?php echo htmlspecialchars($product['Order_Product']) ?></h3>
                </div>
              </div>
            </div>
           <?php } ?>
          </div>
          <div class="form">
            <h1>Select The Date To View Sales Report</h1>
            <form method="POST" action="">
              <div>
                <label for="start_date">Start date</label>
                <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($start) ?>">
              </div>
              <div>
                <label for="end_date">End date</label>
                <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($end) ?>">
              </div>
              <button type="submit">Search</button>
          </form>
          <h3><?= htmlspecialchars($start) ?> to <?= htmlspecialchars($end) ?></h3>
          </div>
          <div class="graph">
             <div class="chart-container">
             <canvas id="lineChart" role="img" aria-label="Sales by date"></canvas>
            </div>
          </div>
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
                            text: 'Total Sales'
                        }
                    }
                }
            }
        });
    </script>

</body>


</html>
