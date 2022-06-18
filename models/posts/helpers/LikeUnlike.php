<?php

require_once dirname(__FILE__) . "/../Post.php";

class LikeUnlike extends Post
{
  private $postId;
  private $userId;

  public function __construct($database, $postId, $userId)
  {
    parent::__construct($database);
    $this->postId = $postId;
    $this->userId = $userId;
  }

  public function likeUnlike()
  {
    if ($this->postExists() == false) {
      http_response_code(400);
      echo json_encode(array("status" => "error", "msg" => "Post with specified id does not exist"));
      die;
    }

    if ($this->isPostLikedAlready()) {
      $this->unlike();
      die;
    }

    $this->like();
  }

  private function isPostLikedAlready()
  {
    $query = "SELECT * FROM `likes` WHERE
    `userId` = :userId AND `postId` = :postId";

    $statement = $this->conn->prepare($query);
    $statement->execute(["userId" => $this->userId, "postId" => $this->postId]);

    if ($statement->rowCount() >= 1) {
      return true;
    }

    return false;
  }

  private function like()
  {
    $query = "INSERT INTO `likes` 
    (`userId`, `postId`) 
    VALUES 
    (:userId, :postId)";

    $statement = $this->conn->prepare($query);
    $statement->execute(["userId" => $this->userId, "postId" => $this->postId]);

    echo json_encode(array("status" => "OK", "msg" => "Post liked"));
  }

  private function unlike()
  {
    $query = "DELETE FROM `likes` WHERE
    `userId` = :userId AND `postId` = :postId";

    $statement = $this->conn->prepare($query);
    $statement->execute(["userId" => $this->userId, "postId" => $this->postId]);

    if (!$statement) {
      http_response_code(400);
      echo json_encode(array("msg" => "Could not unlike post", "status" => "error"));
      die;
    }

    echo json_encode(array("status" => "OK", "msg" => "Post unliked"));
  }

  private function postExists()
  {
    $query = "SELECT * FROM `posts` WHERE
    `id` = :postId";

    $statement = $this->conn->prepare($query);
    $statement->execute(["postId" => $this->postId]);


    if ($statement->rowCount() >= 1) {
      return true;
    }

    return false;
  }
}
