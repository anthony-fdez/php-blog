<?php

// Headers
header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once dirname(__FILE__) . "/../../config/Database.php";
require_once dirname(__FILE__) . "/../../models/posts/Post.php";

$database = new Database();
$db = $database->connect();

$post = new Post($db);

$result = $post->readAll();
$row_count = $result->rowCount();


if ($row_count > 0) {
  $posts_arr = array();
  $posts_arr["data"] = array();

  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $post_item = array(
      "id" => $row["id"],
      "title" => $row["title"],
      "body" => $row["body"],
      "author" => $row["author"],
      "created_at" => $row["created_at"]
    );

    array_push($posts_arr["data"], $post_item);

    // Turn to json 
  }


  echo json_encode($posts_arr);
} else {

  echo json_encode(
    array("message" => "No posts found")
  );
}
