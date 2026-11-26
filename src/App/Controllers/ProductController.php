<?php
     require_once SNEAKERPEAK_ROOT . '/config/Database.php';

      $db = \Config\Database::getInstance();
      $conn = $db->getConnection();
      
   class GetLimitItems {
        public function getItems() {
            global $conn;
            $query = "SELECT * FROM product ORDER BY Prod_ID DESC LIMIT 8;";
            $result = mysqli_query($conn, $query);
            return $result;
        }
    }

?>