<?php
session_start();

// Database connection configuration
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "sneaker_peak";

// Create database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected item ID from the form data
    $selectedItemId = $_POST['item_id'];

    // Insert the selected item into the database
    $sql = "INSERT INTO cart (item_id) VALUES ($selectedItemId)";
    mysqli_query($conn, $sql);

    // Set a success message
    $_SESSION['success_message'] = "Item added to cart successfully.";
}

// Get all the items from the database
$sql = "SELECT * FROM items";
$result = mysqli_query($conn, $sql);

// Display the items and the add to cart button
while ($row = mysqli_fetch_assoc($result)) {
    echo "<div>";
    echo "<h3>" . $row['name'] . "</h3>";
    echo "<p>" . $row['description'] . "</p>";
    echo "<p>Price: $" . $row['price'] . "</p>";
    echo "<form method='POST'>";
    echo "<input type='hidden' name='item_id' value='" . $row['id'] . "'>";
    echo "<button type='submit'>Add to Cart</button>";
    echo "</form>";
    echo "</div>";
}

// Display the success message if it exists
if (isset($_SESSION['success_message'])) {
    echo "<p>" . $_SESSION['success_message'] . "</p>";
    unset($_SESSION['success_message']);
}

// Close database connection
mysqli_close($conn);
?>
