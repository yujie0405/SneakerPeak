<?php
$currentDate = date("Y-m-d"); // Get the current date
$promoExpirationDate = "2023-07-14"; // Example: Set the expiration date

if ($currentDate > $promoExpirationDate) {
    // Promotion code has expired
    echo "The promotion code has expired";
} else {
    // Promotion code is still valid
    echo "The promotion code is still valid";
}

?>


