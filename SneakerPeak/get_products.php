<?php
include('database/config.php');
$status = $_GET['status'];
if ($status == "available" || $status == "out-of-stock" || $status == "deleted") {
    $query = "select * from product where Prod_Status = '$status'";
} else {
    $query = "select * from product where Prod_Status = 'available' or Prod_Status = 'out-of-stock'";
}
$result = mysqli_query($conn,$query);
while($row = mysqli_fetch_assoc($result)) {
    echo '<tr><td>'.$row['Prod_Name'].'</td><td>'.$row['Prod_Price'].'</td><td>'.$row['Prod_Status'].'</td></tr>';
}
?>
