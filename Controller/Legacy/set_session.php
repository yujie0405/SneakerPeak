<?php

$idToken = $_GET['id'] ?? null;
header('Location: ' . (is_string($idToken) && $idToken !== ''
    ? 'admin_productdetails.php?id=' . rawurlencode($idToken)
    : 'admin_productlist.php'));
