<?php

require SNEAKERPEAK_ROOT . '/database/config.php';

$customers = $conn->query('SELECT name, email, Customer_ContractNumber FROM customer')->fetch_all(MYSQLI_ASSOC);
require SNEAKERPEAK_ROOT . '/resources/views/admin/viewCustomers.php';