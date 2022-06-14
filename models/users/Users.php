<?php


class Users
{
  // Protected so the child classes can access it
  protected $conn;
  protected $email;
  protected  $password;

  public function __construct($database, $email, $password)
  {
    $this->conn = $database;
    $this->email = $email;
    $this->password = $password;
  }
}
