<?php

// Headers
header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json");


include_once "../../config/Database.php";
include_once "../../models/users/helpers/Signup.php";
require_once dirname(__FILE__) . "/../../models/users/helpers/JwtHandler.php";


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

  if (strlen($reqJSON["password"]) < 8) {
    throw new Exception("Field 'password' must be at least 8 characters long");
  }

  if (!filter_var($reqJSON["email"], FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email address");
  }

  $user = new Signup($db, $reqJSON["email"], $reqJSON["password"]);
  $result = $user->createNewUser();
  $userId = $db->lastInsertId();

  if (!$result) {
    throw new Exception("Could not create user");
  }

  $jwtHandler = new JwtHandler($db, $reqJSON["email"], $userId);
  $jwt = $jwtHandler->generateToken();

  echo json_encode(array(
    "msg" => "User created",
    "jwt" => $jwt,
    "userId" => $userId
  ));
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(
    array(
      "msg" => $e->getMessage(),
      "error_code" => $e->getCode(),
    )
  );
}
