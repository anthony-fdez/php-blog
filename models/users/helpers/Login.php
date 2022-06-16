<?php

declare(strict_types=1);

require_once dirname(__FILE__) . "/../Users.php";
require_once dirname(__FILE__) . "/JwtHandler.php";

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

    $jwtHandler = new JwtHandler();
    $jwt = $jwtHandler->generateToken($this->email);

    echo json_encode(array(
      "msg" => "Successfully logged in",
      "isLoggedIn" => true,
      "jwt" => $jwt
    ));
  }
}
