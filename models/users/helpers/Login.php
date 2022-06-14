<?php

require_once dirname(__FILE__) . "/../Users.php";
require __DIR__ . '/../../../vendor/autoload.php';

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
    $now = strtotime("now");
    echo JWT::encode([
      "iat" => $now, // ISSUED AT - TIME WHEN TOKEN IS GENERATED
      "nbf" => $now, // NOT BEFORE - WHEN THIS TOKEN IS CONSIDERED VALID
      "exp" => $now + 3600, // EXPIRY - 1 HR (3600 SECS) FROM NOW IN THIS EXAMPLE
      "jti" => base64_encode(random_bytes(16)), // JSON TOKEN ID
      "iss" => JWT_ISSUER, // ISSUER
      "aud" => JWT_AUD, // AUDIENCE
      // WHATEVER USER DATA YOU WANT TO ADD
      "data" => [
        "id" => $user["id"],
        "name" => $user["name"],
        "email" => $user["email"]
      ]
    ], JWT_SECRET, JWT_ALGO);
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
