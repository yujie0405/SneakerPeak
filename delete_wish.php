<?php
    include('database/config.php');
    
    // Get the product name from the URL parameter
    $prodName = $_GET['name'];
    
    // Delete the item from the wishlist table
    $deleteQuery = "DELETE FROM wishlist WHERE prod_name = '$prodName'";
    $deleteResult = mysqli_query($conn, $deleteQuery);
    
    if ($deleteResult) {
        // Item deleted successfully
        echo "Item deleted from the wishlist.";
    } else {
        // Failed to delete the item
        echo "Failed to delete the item from the wishlist.";
    }
?>
