<?php
  include('database/config.php');
  session_start();
  $orderCode = $_GET['orderCode'];

  // Update the 'ask' field to 1 in the order table
  $updateQuery = "UPDATE oder SET ask = 1 WHERE Order_code = '$orderCode'";
  mysqli_query($conn, $updateQuery);

 
  $result = mysqli_query($conn, "SELECT email FROM customer WHERE id IN(SELECT UID FROM oder WHERE Order_code='$orderCode') ");
  $row = mysqli_fetch_assoc($result);
  $email = $row['email'];
  // Send an email to the user
  // Your email sending code here
  $subject = "Order Complete";
  $message = "Dear customer,\n\nYour order with the order code $orderCode has been shipped. To complete the order, please go to your order history and press the 'Complete' button.\n\nBest regards,\nSneakerPeak";
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
  // Redirect or display a success message
  // header("Location: success.php");
?>
