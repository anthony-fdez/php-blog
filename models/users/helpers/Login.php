<?php

declare(strict_types=1);

require_once dirname(__FILE__) . "/../Users.php";
require_once dirname(__FILE__) . "/JwtHandler.php";

use JwtHandler;

class Login extends Users
{
  public function __construct($database, $email, $password)
  {
    parent::__construct($database, $email, $password);
  }

  public function loginUser()
  {
    // When calling verify password it sets the user id and the password values in the parent state
    $passwordMatch = $this->verifyPassword();

    if (!$passwordMatch) {
      echo json_encode(array(
        "msg" => "Passwords do not match"
      ));

      return;
    }

    $jwtHandler = new JwtHandler($this->conn, $this->email, $this->id);
    $jwt = $jwtHandler->generateToken();

    echo json_encode(array(
      "msg" => "Successfully logged in",
      "isLoggedIn" => true,
      "jwt" => $jwt
    ));
  }
}
