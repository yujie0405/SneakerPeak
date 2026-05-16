<?php
include('database/config.php');

$orderCode = $_GET['orderCode'];

mysqli_query($conn, "UPDATE `oder` SET Order_Status='Shipped' WHERE Order_code='$orderCode'");
// Retrieve customer email associated with the order
$result = mysqli_query($conn, "SELECT email FROM customer WHERE id IN(SELECT UID FROM oder WHERE Order_code='$orderCode') ");
$row = mysqli_fetch_assoc($result);
$email = $row['email'];

// Send email notification
$subject = "Order Shipped";
$message = "Dear customer,\n\nYour order with the order code $orderCode has been shipped. Thank you for choosing our service.\n\nBest regards,\nSneakerPeak";
$sender = "From: 1211202710@student.mmu.edu.my";

if(mail($email, $subject, $message, $sender))
{
  echo "Email notification sent to the customer!";
}
else
{
  echo "Failed to send email notification!";
}

mysqli_close($conn);

?>

