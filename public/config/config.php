<?php
class DB {
  private $host = "127.0.0.1";
  private $username = "username";
  private $password = "password";
    

  public function conectar(){
    try {
    $conn = new PDO("mysql:host=$this->host;dbname=onload",$this->username,$this->password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $conn;
      echo "Connected successfully";
    } catch(PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
     }

  }
}
?>