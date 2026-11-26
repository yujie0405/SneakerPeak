<?php
require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';
$conn = \SneakerPeak\Core\Database::getInstance()->getConnection();
require_once SNEAKERPEAK_ROOT . '/src/App/Models/ProductModel.php';
$projection = \SneakerPeak\App\Models\ProductModel::CATALOG;
$status = (string) ($_GET['status'] ?? '');
$brand = (string) ($_GET['brand'] ?? '');
$search = (string) ($_GET['search'] ?? '');
$result = $conn->execute_query(
    "SELECT * FROM ($projection) catalog WHERE (? = '' OR Prod_Status = ?)
     AND (? = '' OR Prod_Brand = ?) AND (? = '' OR Prod_Name LIKE CONCAT('%', ?, '%'))",
    [$status, $status, $brand, $brand, $search, $search]
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>try list</title>
    <style>
        select, input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
<form method="get">
<select name="status">
    <option value=""  <?php if ($status == '') echo 'selected'; ?>>All</option>
   
    <option value="available"<?php if ($status == 'available' || $status == '') echo ' selected'; ?>>Available</option>
    <option value="out-of-stock"<?php if ($status == 'out-of-stock') echo ' selected'; ?>>Out of stock</option>
    <option value="deleted"<?php if ($status == 'deleted') echo ' selected'; ?>>Deleted</option>
</select>

    <select name="brand">
        <option value="">All</option>
        <option value="Brand1"<?php if ($brand == 'Brand1') echo ' selected'; ?>>Brand1</option>
        <option value="Brand2"<?php if ($brand == 'Brand2') echo ' selected'; ?>>Brand2</option>
        <option value="Brand3"<?php if ($brand == 'Brand3') echo ' selected'; ?>>Brand3</option>
    </select>
    <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Search by product name">
    <button type="submit">Filter</button>
</form>
<table>
    <thead>
    <tr>
        <th>Product Name</th>
        <th>Product Price</th>
        <th>Product Status</th>
        <th>Product Brand</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
<tr>
<td><?php echo $row['Prod_Name'] ?></td>
<td><?php echo $row['Prod_Price'] ?></td>
<td><?php echo $row['Prod_Status'] ?></td>
<td><?php echo $row['Prod_Brand'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>

</table>
</body>
</html>
