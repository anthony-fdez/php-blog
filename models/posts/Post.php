<?php

class Post
{
  protected $conn;

  // Constructor
  public function __construct($database)
  {
    $this->conn = $database;
  }

  public function readAll()
  {
    // Create querry

    $query =
      "SELECT 
      p.*, 
      COUNT(l.userId) as likes 
      FROM posts p 
      LEFT JOIN likes l 
      ON l.postId = p.id GROUP BY p.id";

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
    $query = "SELECT 
    p.*,
    COUNT(l.userId) as likes 
    FROM `posts` p
    LEFT JOIN `likes` l
    ON l.postId = p.id
    WHERE p.id = :id 
    LIMIT 1";

    $statement = $this->conn->prepare($query);

    $statement->execute(["id" => $id]);

    return $statement;
  }
}
