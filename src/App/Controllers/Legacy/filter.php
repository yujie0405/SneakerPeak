<?php
// Retrieve the submitted form data
$category = isset($_GET['category']) ? $_GET['category'] : array();
$subCategory = isset($_GET['subCategory']) ? $_GET['subCategory'] : '';

// Generate the WHERE clause for filtering based on the form data
$whereClause = "WHERE Prod_Status = 'available'";
if (!empty($category)) {
  $categoryStr = "'" . implode("', '", $category) . "'";
  $whereClause .= " AND Category IN ($categoryStr)";
}
if (!empty($subCategory)) {
  $whereClause .= " AND Sub_Category = '$subCategory'";
}

// Modify your existing query to include the WHERE clause
$query = "SELECT * FROM product $whereClause";
$result = mysqli_query($conn, $query);

// Generate the HTML for the filtered product list
while ($row = mysqli_fetch_assoc($result)) {
  // Output the product cards
  // ...
}
?>
