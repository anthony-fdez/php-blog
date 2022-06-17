<?php

// Headers
header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once dirname(__FILE__) . "/../../models/users/helpers/DeleteUser.php";
include_once dirname(__FILE__) . "/../../config/Database.php";
include_once dirname(__FILE__) . "/../../middleware/Auth.php";

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
  $auth = new Auth($headers);

  if (!isset($reqJSON["email"])) {
    throw new Exception("Field 'email' is required");
  }

  if (!isset($reqJSON["password"])) {
    throw new Exception("Field 'password' is required");
  }

  if (!filter_var($reqJSON["email"], FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email address");
  }

  $deleteUser = new DeleteUser($db, $reqJSON["email"], $reqJSON["password"]);
  $queryResult = $deleteUser->delete();

  if (!$queryResult) {
    throw new Exception("Could not delete user");
  }

  echo json_encode(array("msg" => "User deleted successfully"));
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(
    array(
      "error" => $e->getMessage(),
      "errorCode" => $e->getCode(),
    )
  );
}
