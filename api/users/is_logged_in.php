<?php

// Headers
header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once dirname(__FILE__) . "/../../config/Database.php";
require_once dirname(__FILE__) . "/../../middleware/Auth.php";

try {
  if (strtoupper($_SERVER["REQUEST_METHOD"] !== "POST")) {
    throw new Exception("You should do a POST request to this endpoint");
  }

  $database = new Database();
  $db = $database->connect();

  // get json from request
  $req = file_get_contents("php://input");
  $reqJSON = json_decode($req, true);

  $headers = apache_request_headers();
  $auth = new Auth($db, $headers);

  echo json_encode(array(
    "msg" => "You are logged in",
    "isLoggedIn" => true
  ));
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(
    array(
      "error" => $e->getMessage(),
      "error_code" => $e->getCode(),
    )
  );
}
