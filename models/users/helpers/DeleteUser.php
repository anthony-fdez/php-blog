<?php

declare(strict_types=1);
require_once dirname(__FILE__) . "/../Users.php";

class DeleteUser extends Users
{
  public function __construct($database, $email, $password)
  {
    parent::__construct($database, $email, $password, null);
  }

  public function delete()
  {
    $passwordMatch = $this->verifyPassword();

    if (!$passwordMatch) {
      echo json_encode(array(
        "msg" => "Passwords do not match"
      ));

      die;
    }

    try {
      $query = "DELETE FROM `users` WHERE `email` = :email";
      $statement = $this->conn->prepare($query);
      $statement->execute(["email" => $this->email]);

      $this->deleteTokens();

      return $statement;
    } catch (Exception $e) {
      http_response_code(400);
      echo json_encode(
        array(
          "debug" => "Failed to delete the user DeleteUser.php",
          "msg" => $e->getMessage(),
          "error_code" => $e->getCode(),
        )
      );
    }
  }

  private function deleteTokens()
  {
    try {
      $query = "DELETE FROM `authTokens` WHERE `email` = :email";
      $statement = $this->conn->prepare($query);
      $statement->execute(["email" => $this->email]);

      return $statement;
    } catch (Exception $e) {
      http_response_code(400);
      echo json_encode(
        array(
          "debug" => "Failed to delete the user tokens DeleteUser.php",
          "msg" => $e->getMessage(),
          "error_code" => $e->getCode(),
        )
      );
    }
  }
}
