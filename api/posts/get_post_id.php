<?php

// Headers
header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once dirname(__FILE__) . "/../../config/Database.php";
require_once dirname(__FILE__) . "/../../models/posts/Post.php";

try {
  if (strtoupper($_SERVER["REQUEST_METHOD"] !== "POST")) {
    throw new Exception("You should do a POST request to this endpoint");
  }

  $database = new Database();
  $db = $database->connect();

  $post = new Post($db);

  // get json from request
  $req = file_get_contents("php://input");
  $reqJSON = json_decode($req, true);

  if (isset($reqJSON["id"])) {
    $result = $post->getById($reqJSON["id"]);

    if (!$result) throw new Exception("Error saving the post");
    if ($result->rowCount() < 1) throw new Exception("No post found with that id");

    echo json_encode($result->fetch(PDO::FETCH_ASSOC));
  } else {
    throw new Exception("You need to pass 'id' field.");
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
