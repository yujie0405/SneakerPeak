<?php
  session_start();

  if(isset($_GET['prodName'])) {
    $prodName = $_GET['prodName'];
    $_SESSION['prodName'] = $prodName;
  }


?>