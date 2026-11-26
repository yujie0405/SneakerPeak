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
                  $queri="SELECT Code AS Promote_Code, PromotionValue AS Value, ExpiresOn AS Expire_Date, PromotionType AS Promote_type, Details,
                    IF(IsActive=1 AND (ExpiresOn IS NULL OR ExpiresOn >= CURRENT_DATE), 'Available', 'Expired') AS PromoteCode_Status FROM T_Promotecode";
                  $resul=mysqli_query($conn,$queri);
                  $count=1;
                  while($rows=mysqli_fetch_assoc($resul)){
                
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
    $code = trim((string) ($_POST['Promote_Code'] ?? ''));
    $kind = match ($_POST['promotetype'] ?? '') {
        'Value Off', 'FIXED' => 'FIXED', 'Percentage Off', 'PERCENTAGE' => 'PERCENTAGE',
        'Free Shipping', 'FREE_SHIPPING' => 'FREE_SHIPPING', default => null,
    };
    $value = filter_var($_POST['Promote_CodeValue'] ?? null, FILTER_VALIDATE_FLOAT);
    $date = (string) ($_POST['ExpireDate'] ?? '');
    $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
    try {
        if ($code === '' || !$kind || $value === false || $value < 0
            || ($kind === 'PERCENTAGE' && $value > 100) || !$parsed || $parsed->format('Y-m-d') !== $date) {
            throw new DomainException('Enter a valid promotion code, value, type and expiry.');
        }
        $conn->execute_query('INSERT INTO T_Promotecode (Code, PromotionType, PromotionValue, ExpiresOn, Details, IsActive)
            VALUES (?, ?, ?, ?, ?, 1)', [$code, $kind, $value, $date, (string) ($_POST['CodeDetails'] ?? '')]);
        $message = 'Promotion added.';
        $recipients = $conn->query('SELECT Email FROM T_User WHERE IsActive = 1 AND IsAdmin = 0 AND EmailVerified = 1');
        foreach ($recipients as $recipient) {
            \SneakerPeak\App\Services\EmailService::send($recipient['Email'], 'New Promotional Code Available',
                "A new SneakerPeak promotional code is available: $code.");
        }
    } catch (Throwable $error) {
        $message = $error instanceof DomainException ? $error->getMessage()
            : ($error->getCode() === 1062 ? 'This promotion code already exists.' : 'Could not save the promotion.');
    } ?>
    <script>Swal.fire({title: <?= json_encode($message, JSON_HEX_TAG) ?>});</script>
<?php } ?>
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
