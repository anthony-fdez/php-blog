<?php

// Headers
header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once "../../models/Post.php";
include_once "../../config/Database.php";

try {
  if (strtoupper($_SERVER["REQUEST_METHOD"] !== "POST")) {
    throw new Exception("You should do a post request to this endpoint");
  }


  $database = new Database();
  $db = $database->connect();

  $post = new Post($db);


  // get json from request
  $req = file_get_contents("php://input");
  $reqJSON = json_decode($req, true);

  if (isset($reqJSON["categoryId"], $reqJSON["title"], $reqJSON["body"], $reqJSON["author"])) {
    $result = $post->addNewPost($reqJSON["categoryId"], $reqJSON["title"], $reqJSON["body"], $reqJSON["author"]);

    if ($result) {
      echo json_encode(
        array(
          "msg" => "Post saved"
        )
      );
    } else {
      throw new Exception("Error saving the post");
    }
  } else {
    throw new Exception("You are missing a field");
  }
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(
    array(
      "error" => $e->getMessage(),
      "error_code" => $e->getCode(),
    )
  );
}
