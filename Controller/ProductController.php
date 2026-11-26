<?php
     require_once SNEAKERPEAK_ROOT . '/src/Core/Database.php';

      $db = \SneakerPeak\Core\Database::getInstance();
      $conn = $db->getConnection();
      
   class GetLimitItems {
        public function getItems() {
            global $conn;
            require_once SNEAKERPEAK_ROOT . '/src/App/Models/ProductModel.php';
            return \SneakerPeak\App\Models\ProductModel::latest();

        }
    }

?>