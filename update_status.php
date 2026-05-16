<?php
include('database/config.php');

if (isset($_GET['prodName']) && isset($_GET['prodSize'])) {
  $prodName = $_GET['prodName'];
  $prodSize = $_GET['prodSize'];

  $query = "SELECT * FROM sizeandqty WHERE Prod_Name = '$prodName' AND Prod_Size = '$prodSize'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  if ($row['Qty_status'] == 'UnAvailable') {
    if ($row['Prod_Qty'] == 0) {
      echo "Quantity is 0. Cannot update status.";
      exit; // Stop further execution
    }
    $newStatus = 'Available';
  } else if ($row['Qty_status'] == 'Available') {
    $newStatus = 'UnAvailable';
  } else {
    // Handle other cases if needed
    // $newStatus = ...;
  }

  // Update the quantity status in the database
  $updateQuery = "UPDATE sizeandqty SET Qty_status = '$newStatus' WHERE Prod_Name = '$prodName' AND Prod_Size = '$prodSize'";
  if (mysqli_query($conn, $updateQuery)) {
    echo "Quantity status updated successfully";
  } else {
    echo "Error updating quantity status: " . mysqli_error($conn);
  }
}
?>
