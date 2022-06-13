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

  // get json from request
  $req = file_get_contents("php://input");
  $reqJSON = json_decode($req, true);

  if (!isset($reqJSON["email"])) {
    echo json_encode(array("msg" => "Email is required"));
    die;
  }

  if (!isset($reqJSON["password"])) {
    echo json_encode(array("msg" => "Password is required"));
    die;
  }

  $user = new Signup($db, $reqJSON["email"], $reqJSON["password"]);
  $result = $user->createNewUser();

  echo json_encode(array("msg" => "User created"));
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(
    array(
      "error" => $e->getMessage(),
      "error_code" => $e->getCode(),
    )
  );
}
