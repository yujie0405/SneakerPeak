<?php
include('database/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $UID = $_GET['UID'];
    $addressOption = $_GET['addressOption'];

    if ($addressOption === 'Customer_Address') {
        $addressField = 'Customer_Address';
    } elseif ($addressOption === 'Customer_Address_2') {
        $addressField = 'Customer_Address_2';
    } else {
        // Handle the case when the selected option is not valid
        $addressField = '';
    }

    // Retrieve the customer address from the database based on the address field
    $query = "SELECT $addressField FROM customer WHERE id='$UID'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $customerAddress = $row[$addressField];
        echo '' . $customerAddress . '';
    } else {
        // Handle the case when the customer address is not found
        echo '<input type="text" name="Receiver_Address_Line1" value="">';
    }
}
?>

