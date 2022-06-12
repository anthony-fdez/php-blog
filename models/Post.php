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
      "SELECT
        c.name as category_name,
        p.id,
        p.category_id,
        p.title,
        p.body,
        p.author,
        p.created_at
      FROM " . $this->table . " p
      LEFT JOIN 
        categories c ON p.category_ID = c.id
      ORDER BY
        p.created_at DESC";

    $statement  = $this->conn->prepare($query);

    $statement->execute();

    return $statement;
  }

  public function addNewPost($categoryId, $title, $body, $author)
  {
    $query =
      "INSERT INTO 
      `posts` 
      (`category_id`, `title`, `body`, `author`) 
      VALUES
      ($categoryId, \"$title\", \"$body\", \"$author\")";

    $statement = $this->conn->prepare($query);

    $statement->execute();

    return $statement;
  }

  public function getById($id)
  {
    $query = "SELECT * FROM `posts` WHERE `id` = $id LIMIT 1";

    $statement = $this->conn->prepare($query);

    $statement->execute();

    return $statement;
  }
}
