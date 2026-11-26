<?php
     require_once SNEAKERPEAK_ROOT . '/config/Database.php';

      $db = \Config\Database::getInstance();
      $conn = $db->getConnection();
      
   class GetLimitItems {
        public function getItems() {
            global $conn;
            require_once SNEAKERPEAK_ROOT . '/src/App/Models/ProductModel.php';
            return \SneakerPeak\App\Models\ProductModel::latest();

        }
    }

?>