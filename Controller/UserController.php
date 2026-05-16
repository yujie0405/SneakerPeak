<?php
 require_once("config/Database.php");

      $db = \Config\Database::getInstance();
      $conn = $db->getConnection();
        

    class registerUser {
        public function register($username, $email, $password) {
            global $conn;
            $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            return mysqli_query($conn, $query);
        }
    }   

?>