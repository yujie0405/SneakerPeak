<?php
 require_once SNEAKERPEAK_ROOT . '/config/Database.php';

      $db = \Config\Database::getInstance();
      $conn = $db->getConnection();
        

    class registerUser {
        public function register($username, $email, $password) {
            global $conn;
            return $conn->execute_query(
                'INSERT INTO T_User (DisplayName, Email, EmailKey, PasswordHash) VALUES (?, ?, ?, ?)',
                [$username, $email, strtolower(trim($email)), password_hash($password, PASSWORD_DEFAULT)]
            );
        }
    }   

?>