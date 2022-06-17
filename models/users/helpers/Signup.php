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
    $hashedPassword = $this->hashPassword($this->password);

    $query = "INSERT INTO 
    `users`
    (`email`, `password`)
    VALUES
    (:email, :password)";

    $statement = $this->conn->prepare($query);
    $statement->execute(["email" => $this->email, "password" => $hashedPassword]);

    return $statement;
  }

  private function hashPassword($password)
  {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    return $hashedPassword;
  }
}
