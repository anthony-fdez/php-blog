<?php


require_once dirname(__FILE__) . "/../Users.php";
require_once dirname(__FILE__) . "/../../../config/Database.php";


class Signup extends Users
{
  public function __construct($database, $email, $password)
  {
    parent::__construct($database, $email, $password);

    $this->createNewUser($email, $password);
  }

  private function createNewUser($email, $password)
  {

    $query = "INSERT INTO `users`
    (`email`, `password`)
    VALUES
    ($email, $password)";

    $statement = $this->conn->prepare($query);

    $statement->execute();

    return $statement;
  }
}
