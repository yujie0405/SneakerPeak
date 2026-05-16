<?php
$Pname = $_GET['name'];
$selectedSize = $_GET['size'];

include("database/config.php");
$query = "SELECT * FROM cart WHERE Product_Name = '$Pname' AND Select_Size = '$selectedSize'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $oldqty = $row['Select_qty'];
    $qtynew = $oldqty + 1;

    mysqli_query($conn, "UPDATE `cart` SET Select_qty = '$qtynew' WHERE Product_Name = '$Pname' AND Select_Size = '$selectedSize'");
}
?>
