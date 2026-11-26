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
        /*the container must be positioned relative:*/
.custom-select {
  position: relative;
  font-family: Arial;
  width: 200px;
  margin-right: 10px;
}

.custom-select select {
  display: none; /*hide original SELECT element:*/
}

.select-selected {
  background-color: DodgerBlue;
  padding: 8px 16px;
  border-radius: 4px;
}

/*style the arrow inside the select element:*/
.select-selected:after {
  position: absolute;
  content: "";
  top: 14px;
  right: 10px;
  width: 0;
  height: 0;
  border: 6px solid transparent;
  border-color: var(--color-surface) transparent transparent transparent;
}

/*point the arrow upwards when the select box is open (active):*/
.select-selected.select-arrow-active:after {
  border-color: transparent transparent var(--color-surface) transparent;
  top: 7px;
}

/*style the items (options), including the selected item:*/
.select-items div,.select-selected {
  color: var(--color-surface);
  padding: 8px 16px;
  border: 1px solid transparent;
  border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
  cursor: pointer;
  user-select: none;
}

/*style items (options):*/
.select-items {
  position: absolute;
  background-color: DodgerBlue;
  top: 100%;
  left: 0;
  right: 0;
  z-index: 99;
}

/*hide the items when the select box is closed:*/
.select-hide {
  display: none;
}

.select-items div:hover, .same-as-selected {
  background-color: rgba(0, 0, 0, 0.1);
}

    </style>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
<form method="get">
<select name="status" class="custom-select">
    <option value="">All</option>
    <option value="available"<?php if ($status == 'available' || $status == '') echo ' selected'; ?>>Available</option>
    <option value="out-of-stock"<?php if ($status == 'out-of-stock') echo ' selected'; ?>>Out of stock</option>
    <option value="deleted"<?php if ($status == 'deleted') echo ' selected'; ?>>Deleted</option>
</select>

<select name="brand" class="custom-select">
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
