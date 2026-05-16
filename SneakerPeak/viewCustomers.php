<!DOCTYPE html>
<html lang="en">
<head>
  <title>View Customers</title>
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="css/admin_prodlist.css">
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>
  <?php 
  session_start();
  require_once("admin_sidebar.php"); ?>
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
                include "database/Database_Connection.php";
                $sql = "SELECT * from customer ";
                $result = $conn->query($sql);
                $count = 1;
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
              ?>
                <tr>
                  <td><?=$count?></td>
                  <td><?=$row["name"]?></td>
                  <td><?=$row["email"]?></td>
                  <td><?=$row["Customer_ContractNumber"]?></td>
                </tr>
              <?php
                    $count = $count + 1;
                  }
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
