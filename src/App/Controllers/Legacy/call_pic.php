<?php
    include SNEAKERPEAK_ROOT . '/database/config.php';
    
    // Fetch data from the cart table
    $query = "SELECT * FROM cart";
    $result = mysqli_query($conn, $query);

    // Initialize total amount and subtotal
    $subtotal = 0;
    $shippingFee = 20;
    
    $totalAmount = $subtotal + ($subtotal < 500 ? $shippingFee : 0);
    $total=0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart</title>
    <!-- Your CSS and other head elements -->
    <script>
        // Function to calculate the subtotal
        function calculateSubtotal() {
            const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            let subtotal = 0;

            checkboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    const row = checkbox.parentNode.parentNode;
                    const amount = parseFloat(row.querySelector('.amount').textContent);
                    subtotal += amount;
                }
            });

            document.querySelector('.total-subtotal').textContent = 'RM ' + subtotal.toFixed(2);
            
            // Calculate and display the total amount
            const totalAmount = subtotal; // Add 5 to the subtotal
            document.getElementById('total-amount').textContent = 'RM ' + totalAmount.toFixed(2);
        }

        // Function to handle "Select All" checkbox
        function selectAll() {
            const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = document.getElementById('select-all').checked;
            });

            calculateSubtotal();
        }

        // Attach change event listeners to checkboxes
        window.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    calculateSubtotal();
                });
            });

            document.getElementById('select-all').addEventListener('change', function () {
                selectAll();
            });
        });
    </script>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
    <script>
                function plush(name, size) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", "plush.php?name=" + name + "&size=" + size, true);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // handle the response from the PHP script
                            // reload the page to show the updated list of items
                            location.reload();

                        }
                    };
                    xhr.send();
                }
                function minus(name,size)
                {
                    var xhr = new XMLHttpRequest();
                                        xhr.open("GET", "minus.php?name=" + name + "&size=" + size, true);
                                        xhr.onreadystatechange = function() {
                                            if (xhr.readyState === 4 && xhr.status === 200) {
                                                // handle the response from the PHP script
                                               
                                                // reload the page to show the updated list of items
                                                location.reload();
                                            }
                                        };
                                        xhr.send();
                }
             </script>
             <script>
    // Function to calculate the subtotal
    function calculateSubtotal() {
        const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
        let subtotal = 0;

        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                const row = checkbox.parentNode.parentNode;
                const amount = parseFloat(row.querySelector('.amount').textContent);
                subtotal += amount;
            }
        });

        document.querySelector('.total-subtotal').textContent = 'RM ' + subtotal.toFixed(2);
        
        // Calculate and display the shipping fee
        const shippingFee = subtotal >= 500 ? 0 : 20;
        document.getElementById('shipping-fee').textContent = 'RM ' + shippingFee.toFixed(2);
        
        // Calculate and display the total amount
        const <?= $total ?> = subtotal + shippingFee;
        
    }

    // Rest of the code...
</script>
    <h2>User/Shopping Cart</h2>
    <table>
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"> Select All</th>
                <th colspan="2">Product</th>
                <th>Size</th>
                <th>Quantity</th> <!-- New table head -->
                <th>Subtotal</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $amount = $row['Prod_Price'] * $row['Select_qty'];
            ?>
            <tr>
                <td>
                    <input type="checkbox" name="checkboxes[]" value="<?php echo $row['Product_Name']; ?>">
                </td>
                <td colspan="2">
                    <div class="cart-info">
                        <img src="prod/<?php echo $row['Prod_Img1']; ?>">
                        <div class="note">
                            <p><?php echo $row['Product_Name']; ?></p>
                            <small>Price: RM<?php echo $row['Prod_Price']; ?></small>
                        </div>
                    </div>
                </td>
                <td><?php  echo $row ['Select_Size']; ?></td>
                <td> <button class="plush" onclick="plush('<?php echo $row['Product_Name'] ?>', '<?php echo $row['Select_Size'] ?>')">+</button>
                    <input type="text" value="<?php echo $rowtal['Select_qty']; ?>" min="1" readonly>
                    <button class="minus" onclick="minus('<?php echo $row['Product_Name'] ?>', '<?php echo $row['Select_Size'] ?>')">-</button>
                </td>
                 <td class="amount"><?php echo $amount; ?></td>
                 <td>
                        <a href="cart.php?delete=<?php echo $row['Product_Name']; ?>&size=<?php echo $row['Select_Size']; ?>">
                            <i class="fas fa-trash-alt" title="Delete item"></i>
                        </a>
                    </td>
            </tr>
            <?php
                   
                }
            ?>
        </tbody>
    </table>
    
    <div class="total-price">
        <table>
            <tr>
                <td>Subtotal</td>
                
                <td class="total-subtotal" >RM <?php echo  $subtotal ?></td>

            </tr>
            <tr>
                <td>Shipping Fee</td>
                <td id="shipping-fee">RM <?php echo $subtotal >= 500 ? "0.00" : $shippingFee.".00"; ?></td>
            </tr>
            <tr>
                <td>Total Amount</td>
                <td id="total-amount">RM <?php echo  $total ?></td>
            </tr>
            <!-- Other total rows -->
            <tr>
                <input type="submit" value="Insert into Database" onclick="insertIntoDatabase()">
            </tr>
            
        </table>
    </div>
</body>
</html>
