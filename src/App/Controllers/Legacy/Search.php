<?php
$search = (string) ($_GET['search'] ?? $_POST['search'] ?? '');
header('Location: menu.php?' . http_build_query(['search' => $search]));
