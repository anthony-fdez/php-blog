<?php

class Post
{
  private $conn;
  private $table = "posts";

  // Post properties
  public $postId;
  public $categoryId;
  public $categoryName;
  public $title;
  public $body;
  public $author;
  public $createdAt;

  // Constructor
  public function __construct($database)
  {
    $this->conn = $database;
  }

  public function readAll()
  {
    // Create querry

    $query =
      "SELECT * FROM `posts`";

    $statement  = $this->conn->prepare($query);

    $statement->execute();

    return $statement;
  }

  public function addNewPost($title, $body, $author)
  {
    $query =
      "INSERT INTO 
      `posts` 
      (`title`, `body`, `author`) 
      VALUES
      (:title, :body, :author)";

    $statement = $this->conn->prepare($query);

    $statement->execute(["title" => $title, "body" => $body, "author" => $author]);

    return $statement;
  }

  public function getById($id)
  {
    $query = "SELECT * FROM `posts` WHERE `id` = :id LIMIT 1";

    $statement = $this->conn->prepare($query);

    $statement->execute(["id" => $id]);

    return $statement;
  }
}
