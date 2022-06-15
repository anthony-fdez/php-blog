<?php

declare(strict_types=1);
require_once dirname(__FILE__) . "/../Users.php";

class DeleteUser extends Users
{
  public function __construct($database, $email, $password)
  {
    parent::__construct($database, $email, $password);
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

    $query = "DELETE FROM `users` WHERE `email` = \"$this->email\"";
    $statement = $this->conn->prepare($query);
    $statement->execute();

    return $statement;
  }
}
