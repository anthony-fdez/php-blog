<?php

require_once dirname(__FILE__) . "/../models/users/helpers/JwtHandler.php";
require_once dirname(__FILE__) . "/../config/Database.php";

class Auth
{
  private $token;
  private $email;

  public function __construct($headers)
  {
    if (!isset($headers['Authorization'])) {
      http_response_code(401);
      echo json_encode(array(
        "msg" => "Please log in (send 'Authorization' with a token in the headers of your request)",
        'isLoggedIn' => false
      ));
      die;
    }

    if (!isset($headers['Email'])) {
      http_response_code(401);
      echo json_encode(array(
        "msg" => "Please log in (send 'Email' with the user email in the request headers)",
        'isLoggedIn' => false
      ));
      die;
    }

    if (!filter_var($headers["Email"], FILTER_VALIDATE_EMAIL)) {
      throw new Exception("Invalid 'Email' sent with the header of the request");
    }

    $this->token = $headers["Authorization"];
    $this->email = $headers["Email"];

    $this->verifyToken();
  }

  private function verifyToken()
  {
    $database = new Database();
    $conn = $database->connect();

    $jwtHandler = new JwtHandler($conn, $this->email, null);
    $isTokenValid = $jwtHandler->validateToken($this->email, $this->token);

    if (!$isTokenValid) {
      echo json_encode(array(
        "msg" => "Access denied, please pass `Email` and `Authentication` headers to validate the user"
      ));
      die;
    }
  }
}
