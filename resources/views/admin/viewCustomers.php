<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Customers</title>
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="css/admin_prodlist.css">
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/admin.php'; ?>
</head>
<body class="admin-workspace">
  <?php 
  if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
  require_once SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
  <section class="home-section">
    <header class="home-content"><h1>Customers</h1></header>
    <div class="contenbar">
      <div class="table">
        <section class="table__header">
          <h1>All Customers</h1> 
        </section>
        <section class="table__body">
          <div class="admin-table-scroll" tabindex="0" role="region" aria-label="Scrollable table"><table>
            <thead>
              <tr>
                <th>S.N <span class="icon-arrow"></span></th>
                <th>Username <span class="icon-arrow"></span></th>
                <th>Email <span class="icon-arrow"></span></th>
                <th>Contact Number <span class="icon-arrow"></span></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $count = 1;
                foreach ($customers as $row) {
              ?>
                <tr>
                  <td><?=$count?></td>
                  <td><?= htmlspecialchars($row["name"]) ?></td>
                  <td><?= htmlspecialchars($row["email"]) ?></td>
                  <td><?= htmlspecialchars($row["Customer_ContractNumber"]) ?></td>
                </tr>
              <?php
                    $count = $count + 1;
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
