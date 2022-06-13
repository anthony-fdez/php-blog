<?php

// Headers
header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json");


include_once "../../config/Database.php";
include_once "../../models/users/helpers/Signup.php";


try {

  if (strtoupper($_SERVER["REQUEST_METHOD"] !== "POST")) {
    throw new Exception("You should do a POST request to this endpoint");
  }

  $database = new Database();
  $db = $database->connect();

  $user = new Signup($db, "anthony", "fernanded");
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(
    array(
      "error" => $e->getMessage(),
      "error_code" => $e->getCode(),
    )
  );
}
