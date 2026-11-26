<?php

require SNEAKERPEAK_ROOT . '/database/config.php';

$customers = $conn->query('SELECT DisplayName AS name, Email AS email, Phone AS Customer_ContractNumber FROM T_User WHERE IsAdmin = 0')->fetch_all(MYSQLI_ASSOC);
require SNEAKERPEAK_ROOT . '/resources/views/admin/viewCustomers.php';