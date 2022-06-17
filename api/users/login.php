<?php

// Headers
header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json");


require_once "../../config/Database.php";
require_once "../../models/users/helpers/Login.php";

try {
  if (strtoupper($_SERVER["REQUEST_METHOD"] !== "POST")) {
    throw new Exception("You should do a POST request to this endpoint");
  }

  $database = new Database();
  $db = $database->connect();

  // get json from request
  $req = file_get_contents("php://input");
  $reqJSON = json_decode($req, true);

  if (!isset($reqJSON["email"])) {
    throw new Exception("Field 'email' is required");
  }

  if (!isset($reqJSON["password"])) {
    throw new Exception("Field 'password' is required");
  }

  if (!filter_var($reqJSON["email"], FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email address");
  }

  $user = new Login($db, $reqJSON["email"], $reqJSON["password"]);
  $user->loginUser();
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(
    array(
      "msg" => $e->getMessage(),
      "error_code" => $e->getCode(),
    )
  );
}
