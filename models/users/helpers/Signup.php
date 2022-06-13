<?php


require_once dirname(__FILE__) . "/../Users.php";
require_once dirname(__FILE__) . "/../../../config/Database.php";


class Signup extends Users
{
  public function __construct($database, $email, $password)
  {
    parent::__construct($database, $email, $password);
  }

  public function createNewUser()
  {
    $query = "INSERT INTO 
    `users`
    (`email`, `password`)
    VALUES
    (\"$this->email\", \"$this->password\")";

    $statement = $this->conn->prepare($query);

    $statement->execute();

    return $statement;
  }
}
