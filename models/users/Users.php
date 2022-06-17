<?php


class Users
{
  // Protected so the child classes can access it
  protected $conn;
  protected $email;
  protected $password;
  protected $dbHashedPassword;
  protected $id;

  public function __construct($database, $email, $password)
  {
    $this->conn = $database;
    $this->email = $email;
    $this->password = $password;
  }

  protected function verifyPassword()
  {
    $query = "SELECT `password`, `id`, `email` FROM users WHERE email = :email";
    $statement = $this->conn->prepare($query);
    $statement->execute(["email" => $this->email]);

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if (isset($result["id"])) {
      $this->id = $result["id"];
    }

    if (isset($result["password"])) {
      $this->dbHashedPassword = $result["password"];
    }

    if (isset($result["email"])) {
      $this->email = $result["email"];
    }

    if (password_verify($this->password, $this->dbHashedPassword)) {
      return true;
    }

    return false;
  }
}
