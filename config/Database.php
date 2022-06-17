<?php

class Database
{
  private $host = "localhost";
  private $dbName = "test";
  private $username = "root";
  private $password = "";
  private $conn;

  // Db connect
  public function connect()
  {
    $this->conn = null;

    try {
      $this->conn = new PDO(
        "mysql:host=" . $this->host . ";dbname=" . $this->dbName,
        $this->username,
        $this->password
      );

      $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $error) {
      echo "Connection error: " . $error->getMessage();
    }

    return $this->conn;
  }
}
