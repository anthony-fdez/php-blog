<?php

class Auth
{
  private $db;
  private $token; // to do 

  public function __construct($db, $headers)
  {
    $this->db = $db;

    if (!isset($headers['Authorization'])) {
      http_response_code(401);
      echo json_encode(array("msg" => "Please log in (send 'Authorization' with a token in the headers of your request)"));
      die;
    }

    $this->token = $headers['Authorization'];
    $this->verifyToek();
  }

  public function verifyToek()
  {
    if (!$this->isTokenValid()) {
      http_response_code(401);
      echo json_encode(array("msg" => "Please log in"));
      die;
    }
  }

  private function isTokenValid()
  {
    // do the token validation

    return true;
  }
}
