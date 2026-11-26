<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="sidebar close">
<div class="logo-details">
  
  <span class="logo_name">Sneaker Peak</span>
</div>
<ul class="nav-links">
  <li>
    <a href="DashBoad.php">
      <i class='bx bx-grid-alt' ></i>
      <span class="link_name">Dashboard</span>
    </a>
    <ul class="sub-menu blank">
      <li><a class="link_name" href="DashBoad.php">Dashboard</a></li>
    </ul>
  </li>
  <li>
    <div class="iocn-link">
      <a href="admin-ProductDashboard.php">
        <i class='bx bx-collection' ></i>
        <span class="link_name">Product</span>
      </a>
      <i class='bx bxs-chevron-down arrow' ></i>
    </div>
    <ul class="sub-menu">
      <li><a class="link_name" href="admin-ProductDashboard.php">Product</a></li>
      <li><a href="admin_productlist.php">Product List</a></li>
      <li><a href="admin_addproduct.php">Add Product</a></li>
      
      <li><a href="admin_addbrand.php">Brand&SubCategory</a></li>
    </ul>
  </li>
  <li>
    <div class="iocn-link">
      <a href="#">
      <i class="fa-regular fa-user" style="color: var(--color-surface);"></i>
        <span class="link_name">Accounts</span>
      </a>
      <i class='bx bxs-chevron-down arrow' ></i>
    </div>
    <ul class="sub-menu">
      <li><a class="link_name">Users</a></li>
      <li><a href="viewCustomers.php">View Customer</a></li>
      <li><a href="add_newAdmin.php">View Admin</a></li>
    </ul>
  </li>
  <li>
    <div class="iocn-link">
      <a href="admin-OrderDashboard.php">
        <i class='bx bx-plug' ></i>
        <span class="link_name">Order</span>
      </a>
      <i class='bx bxs-chevron-down arrow' ></i>
    </div>
    <ul class="sub-menu">
      <li><a class="link_name" href="admin-OrderDashboard.php">Order</a></li>
      <li><a href="admin-Orderlist.php">Order List</a></li>
    </ul>
  </li>
  <li>
    <a href="admin_promote.php">
      <i class='bx bx-compass' ></i>
      <span class="link_name">Promote</span>
    </a>
    <ul class="sub-menu blank">
      <li><a class="link_name" href="admin_promote.php">Promote</a></li>
    </ul>
  </li>
  
  <li>
    <a href="admin_profile.php">
      <i class='bx bx-cog' ></i>
      <span class="link_name">Profile</span>
    </a>
    <ul class="sub-menu blank">
      <li><a class="link_name" href="admin_profile.php">Profile</a></li>
    </ul>
  </li>
  <li>
<div class="profile-details">
  <div class="profile-content">
    <!--<img src="image/profile.jpg" alt="profileImg">-->
  </div>
  
  <div class="name-job">
    <div class="profile_name"><?= htmlspecialchars((string) ($_SESSION['name'] ?? 'Admin')) ?></div>
    <div class="job">Admin</div>
  </div>
  <a href="logout.php"><i class='bx bx-log-out'></i></a>

</div>
</li>
</ul>
</div>