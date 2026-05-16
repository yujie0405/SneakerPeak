<?php
// get the ID of the item to be deleted from the HTTP GET request
$id = $_GET['id'];

// connect to the database
$conn = mysqli_connect('localhost','root','','sneakerpeak');

// delete the item from the database
mysqli_query($conn,"UPDATE `product` SET Prod_Status = 'available' WHERE Prod_ID = '$id'");

// close the database connection
mysqli_close($conn);

// send a response to the JavaScript function
echo "Item Update To Available Successful";
?>