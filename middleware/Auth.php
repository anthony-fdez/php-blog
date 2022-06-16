<?php

require_once dirname(__FILE__) . "/../models/users/helpers/JwtHandler.php";

class Auth
{
  private $db;
  private $token; // to do 

  public function __construct($db, $headers)
  {
    $this->db = $db;

    if (!isset($headers['Authorization'])) {
      http_response_code(401);
      echo json_encode(array(
        "msg" => "Please log in (send 'Authorization' with a token in the headers of your request)",
        'isLoggedIn' => false
      ));
      die;
    }

    $this->token = $headers['Authorization'];
    $this->verifyToek();
  }

  public function verifyToek()
  {
    $jwtHandler = new JwtHandler();

    $isTokenValid = $jwtHandler->validateToken($this->token);

    if (!$isTokenValid) {
      echo json_encode(array("msg" => "Access denied, please log in (invalid or expired JWT)"));
      die;
    }
  }
}
