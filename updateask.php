<?php
include('database/config.php');
if (isset($_GET['Ordercode'])) {
    $orderCode = $_GET['Ordercode'];

    // Connect to your database
   

    // Prepare and execute the SQL update statement
    $sql = "UPDATE oder SET ask = 0 WHERE Order_code = '$orderCode'";
    $result = mysqli_query($conn, $sql);
    $complete="UPDATE oder SET Order_Status='Complete'WHERE Order_code = '$orderCode' ";
    $result = mysqli_query($conn, $complete);
    // Check if the update was successful
    if ($result) {
        // Return a success message or perform any additional actions
        echo "Order status updated successfully.";
    } else {
        // Return an error message or handle the error appropriately
        echo "Error updating order status: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
