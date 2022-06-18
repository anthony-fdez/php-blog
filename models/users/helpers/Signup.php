<?php

require_once dirname(__FILE__) . "/../Users.php";
require_once dirname(__FILE__) . "/../../../config/Database.php";


class Signup extends Users
{
  public function __construct($database, $email, $password, $name)
  {
    parent::__construct($database, $email, $password, $name);
  }

  public function createNewUser()
  {
    $hashedPassword = $this->hashPassword($this->password);

    $query = "INSERT INTO 
    `users`
    (`email`, `password`, `name`)
    VALUES
    (:email, :password, :name)";

    $statement = $this->conn->prepare($query);
    $statement->execute(["email" => $this->email, "password" => $hashedPassword, "name" => $this->name]);

    return $statement;
  }

  private function hashPassword($password)
  {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    return $hashedPassword;
  }
}
