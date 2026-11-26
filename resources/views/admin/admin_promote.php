<?php
require_once SNEAKERPEAK_ROOT . '/src/App/Services/EmailService.php';
include SNEAKERPEAK_ROOT . '/database/config.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="css/addproduct.css">
  <link rel="stylesheet" href="css/admin_prodlist.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    button {
      height: 40px;
      background: #0b0b49;
      color: white;
      font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
      font-size: 18px;
      border-radius: 5px;
    }

    button:hover {
      background: #131399;
      color:  white;
    }

    #idea
    {
      font-weight: initial;
      color: red;
    }
  </style>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
<?php
require_once SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php';
?>
<section class="home-section">
  <div class="home-content">
    <i class='bx bx-menu' ></i>
    <span class="text">Add Promote Code </span>
  </div>

  <div class="itembox">
    <h1>Details </h1>

    <div class="inputbox">
      <form method="post" enctype="multipart/form-data">
        <div class="box">
          <h4>Promote Code:</h4>
          <input type="text" name="Promote_Code" required>
        </div>
        <div class="box">
          <h4>Promote Code value:</h4>
          <input type="text" name="Promote_CodeValue" required>
        </div>
        <div class="box">
          <h4>Auto Generate: <span id="idea">*if no idea  with the code</span> </h4>
          <input type="text" name="generate" id="generateInput">
          <button class="Generate" type="button" onclick="generateRandomNumber()">+</button>
        </div>
        <div class="box">
          <h4>Expire Date:</h4>
          <input type="date" name="ExpireDate" required>
        </div>
        <div class="box">
          <h4>Promote Code type:</h4>
          <select name="promotetype" id=""  class="selectinfo" required>
            <option value="Percentage Off">Percentage Off</option>
            <option value="Value Off">Value Off</option>
            <option value="Free Shipping">Free Shipping</option>
          </select>
        </div>
        <div class="box">
          <h4>Code Details:</h4>
          <input type="text" name="CodeDetails"required>
        </div>
        <div class="box">
          <div class="sub">
            <input type="submit" value="Add" name="submit" id="">
          </div>
        </div>
      </form>
    </div>

  </div>
  <br>
  <br>
  <div class="itembox">
      <h1>PromoteCode List</h1>
      <table>
                 <thead>
                     <tr>
                         <th> No. <span class="icon-arrow"></span></th>
                         <th> Promote Code <span class="icon-arrow"></span></th>
                         <th> Promote Value <span class="icon-arrow"></span></th>
                         <th> Expire Date <span class="icon-arrow"></span></th>
                         <th> Promote Type</th>
                         <th>Details<span class="icon-arrow"></span></th>
                         <th>Promote Code Status</th>
                         
                     </tr>
                 </thead>
                 <tbody>
                  <?php
                  $queri="SELECT * FROM promote_code";
                  $resul=mysqli_query($conn,$queri);
                  $count=1;
                  while($rows=mysqli_fetch_assoc($resul)){
                    $sql = "UPDATE promote_code
                            SET PromoteCode_Status = 'expired'
                            WHERE Expire_Date <= CURDATE()";
                            $do=mysqli_query($conn,$sql);
                
                  ?>
                       <tr>
                          <td><?=$count?></td>
                          <td><?php echo $rows['Promote_Code']?></td>
                          <td><?php echo $rows['Value']?></td>
                          <td><?php echo $rows['Expire_Date']?></td>
                          <td><?php echo $rows['Promote_type']?></td>
                          <td><?php echo $rows['Details']?></td>
                          <td><?php echo $rows['PromoteCode_Status']?></td>
                       </tr>
                       <?php
                       $count=$count+1;
                      };?>
                 </tbody>
             </table>
  </div>
  <br>

</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
if (isset($_POST['submit'])) {
  $PCode = $_POST['Promote_Code'];
  $value = $_POST['Promote_CodeValue'];
  $Exdate = $_POST['ExpireDate'];
  $CD = $_POST['CodeDetails'];
  $type=$_POST['promotetype'];
  $status="Available";
  // Assuming you have already established a database connection
 
  // Check if the $PCode already exists in the database
  $query = "SELECT COUNT(*) AS count FROM promote_code WHERE Promote_Code = '$PCode'";
  $result = mysqli_query($conn,$query);
  $row = $result->fetch_assoc();
  $count = $row['count'];

  if ($count > 0) {
    // $PCode already exists in the database
    ?>
    <script>
        Swal.fire({
                title: 'Error',
                text: 'The promotional code already exists. Please try a new one.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
    </script>
    <?php
  } else {
    // Insert the new promotional code into the database
    $insertQuery = "INSERT INTO promote_code (Promote_Code,Value,Expire_Date,Promote_type,Details,PromoteCode_Status) VALUES ('$PCode', '$value', '$Exdate','$type','$CD','$status')";
    $insertResult = mysqli_query($conn,$insertQuery);

    if ($insertResult) {
      // Send email to all customers
      $query = "SELECT email FROM customer";
      $result = mysqli_query($conn, $query);
    
      while ($row = mysqli_fetch_assoc($result)) {
        $to = $row['email'];
        $subject = "New Promotional Code Available";
        $message = "Dear customer,\n\nA new promotional code is available: $PCode. Enjoy the discounts!\n\nRegards,\nSneaker Peak";
    
        // Send email
        \SneakerPeak\App\Services\EmailService::send($to, $subject, $message);
      }
    
      ?><script>
      Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Promotional code successfully added and emails sent to customers.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location = "admin_promote.php";
            });

      </script><?php
    } 
  }
}

?>
<script>
  function generateRandomNumber() {
    var randomNumber = Math.floor(100000 + Math.random() * 900000); // Generate a random 6-digit number
    document.getElementById('generateInput').value = randomNumber; // Set the value of the input field
  }

  let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e) => {
      let arrowParent = e.target.parentElement.parentElement; //selecting main parent of arrow
      arrowParent.classList.toggle("showMenu");
    });
  }

  let sidebar = document.querySelector(".sidebar");
  let sidebarBtn = document.querySelector(".bx-menu");
  console.log(sidebarBtn);
  sidebarBtn.addEventListener("click", () => {
    sidebar.classList.toggle("close");
  });
</script>
</body>
</html>
