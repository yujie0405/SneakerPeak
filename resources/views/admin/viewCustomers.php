<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Customers</title>
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="css/admin_prodlist.css">
  <link rel="stylesheet" href="css/admin.css">
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
  <?php 
  if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
  require_once SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; ?>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu'></i>
      <span class="text">View Customers</span>
    </div>
    <div class="contenbar">
      <div class="table">
        <section class="table__header">
          <h1>All Customers</h1> 
        </section>
        <section class="table__body">
          <table>
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
          </table>
        </section>
      </div>
    </div>
  </section>
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
