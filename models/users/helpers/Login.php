<?php

require_once dirname(__FILE__) . "/../Users.php";

class Login extends Users
{
  public function __construct($database, $email, $password)
  {
    parent::__construct($database, $email, $password);
  }

  public function loginUser()
  {
    $this->verifyPassword();

    // generate JWT and sent it back
  }

  private function verifyPassword()
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
