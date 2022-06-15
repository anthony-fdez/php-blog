<?php


class Users
{
  // Protected so the child classes can access it
  protected $conn;
  protected $email;
  protected  $password;

  public function __construct($database, $email, $password)
  {
    $this->conn = $database;
    $this->email = $email;
    $this->password = $password;
  }

  protected function verifyPassword()
  {
    $query = "SELECT `password` FROM users WHERE email = \"$this->email\"";
    $statement = $this->conn->prepare($query);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if (password_verify($this->password, $result["password"])) {
      return true;
    }

    return false;
  }
}
