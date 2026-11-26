<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/headerandfooter.css">
    <link rel="stylesheet" href="css/wishlist.css">
    <link rel="stylesheet" href="css/style.css">
     
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <title>Wish List</title>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>

<header>
 <?php
    include SNEAKERPEAK_ROOT . '/resources/views/partials/header.php';
    if (!isset($_SESSION["CustomerID"])) {
        ?>
        <script>
            Swal.fire({
                title: "It appears that you haven't logged in.",
                text: "Would you like to go to the login page?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Login",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                window.location.href = "Login.php";
                } else {
                window.history.back();
                }
            });
            </script>
        <?php
    }          
?>
</header>

<body>
    <br>
    <br>
    <div class="header_fixed">
        <table>
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Sport </th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 1;
                foreach ($wishlist as $rows) {
                    $prodName = $rows['prod_name'];
                    $productPicture = $rows['Prod_img1'];
                    ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><img src="prod/<?php echo $productPicture; ?>" alt=""></td>
                        <td><?php echo $rows['prod_name']; ?></td>
                        <td><?php echo $rows['Prod_SubC']; ?></td>
                        <td><button data-product="<?= htmlspecialchars($prodName) ?>" onclick="viewprod(this.dataset.product)">View</button></td>
                        <td><button title="delete product" data-product="<?= htmlspecialchars($prodName) ?>" onclick="deletewish(this.dataset.product)">Delete</button></td>
                    </tr>
                <?php
                }
                ?>
             
            </tbody>
        </table>
    </div>

</body>

<script>
  function viewprod(Prod_Name) {
    window.location.href = "prod.php?Prod_Name=" + encodeURIComponent(Prod_Name);
}



    function deletewish(name) {
        // Confirm with the user before deleting the item
        if (confirm("Are you sure you want to delete this item?")) {
            // Send an AJAX request to the PHP script
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "delete_wish.php?name=" + encodeURIComponent(name), true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Handle the response from the PHP script
                    alert(xhr.responseText);
                    // Reload the page to show the updated list of items
                    location.reload();
                }
            };
            xhr.send();
        }
    }
                             
                </script>


<footer>
   <?php
    include SNEAKERPEAK_ROOT . '/resources/views/partials/footer.php';

    ?>
</footer>
<script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/scrollax.min.js"></script>
  <?php if ($mapsKey = sneakerpeakSetting('MAPS_BROWSER_KEY')) { ?><script src="https://maps.googleapis.com/maps/api/js?key=<?= rawurlencode($mapsKey) ?>&sensor=false"></script><?php } ?>
  <?php if (!empty($mapsKey)) { ?><script src="js/google-map.js"></script><?php } ?>
  <script src="js/main.js"></script>
</html>