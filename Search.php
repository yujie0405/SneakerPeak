<!DOCTYPE html>
<html>
<head>
    <title>Product Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        .search-form {
            display: flex;
            align-items: center;
        }
        
        .search-input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            flex: 1;
            width: 40px;
        }
        
        .search-submit {
            padding: 8px 12px;
            background: #2691d9;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }
        
        .search-submit:hover {
            background-color: blue;
        }
    </style>
</head>
<body>
    <div class="search-form">
        <form action="testing.php" method="post">
            <input type="text" name="search" class="search-input" placeholder="Search for a product">
            <button type="submit" class="search-submit">
                <i class="fa-solid fa-magnifying-glass" style="color: #fcfcfc;"></i>
            </button>
        </form>
    </div>
</body>
</html>
<?php
include("database/Database_connection.php");

if (isset($_POST['search'])) {
    $search = $_POST['search'];

    $sql = "SELECT * FROM customer WHERE name LIKE '%$search%'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row["name"] . "<br>";
        }
    } else {
        echo "0 records";
    }

    $conn->close();
}
?>
