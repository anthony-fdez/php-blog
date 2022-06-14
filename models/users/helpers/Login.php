<?php

declare(strict_types=1);

require_once dirname(__FILE__) . "/../Users.php";

require_once __DIR__ . '/../../../vendor/autoload.php';

use Firebase\JWT\JWT;

class Login extends Users
{
  public function __construct($database, $email, $password)
  {
    parent::__construct($database, $email, $password);
  }

  public function loginUser()
  {
    $passwordMatch = $this->verifyPassword();

    if (!$passwordMatch) {
      echo json_encode(array(
        "msg" => "Passwords do not match"
      ));

      return;
    }


    $this->generateJwtToken();

    echo json_encode(array(
      "msg" => "Loged in"
    ));

    // generate JWT and sent it back
  }

  private function generateJwtToken()
  {
    // i cant get the fucking autoload to work so no jwt for now
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
