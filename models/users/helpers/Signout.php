<?php

include_once dirname(__FILE__) . "/../Users.php";

class Signout extends Users
{

  public function __construct($database)
  {
    // No need to use the email or passwords
    // since i'll just delete the token 
    // and they need to be loged in to be able to log out...
    parent::__construct($database, null, null, null);
  }

  // To sign out a user i only need to delete the token they send from the db
  public function signOutUser($jwt)
  {
    try {
      $query = "DELETE FROM `authTokens` WHERE `token` = :jwt";
      $statement = $this->conn->prepare($query);
      $result = $statement->execute(["jwt" => $jwt]);

      if (!$result) {
        throw new Exception("Could not delete token from the database");
      }

      echo json_encode(array(
        "msg" => "Logged out successfully",
        "isLoggedIn" => false
      ));

      die;
    } catch (Exception $e) {
      http_response_code(400);
      echo json_encode(
        array(
          "debug" => "Failed to sign out the user Signout.php",
          "msg" => $e->getMessage(),
          "error_code" => $e->getCode(),
        )
      );
    }
  }

  public function signOutAll($email)
  {
    // Find every row with their email and remove it in the authTokens table

    try {
      $query = "DELETE FROM `authTokens` WHERE `email` = :email";
      $statement = $this->conn->prepare($query);
      $result = $statement->execute(["email" => $email]);

      if (!$result) {
        throw new Exception("Could not delete all tokens from the database");
      }

      echo json_encode(array(
        "msg" => "Logged out on all devices successfully",
        "isLoggedIn" => false
      ));

      die;
    } catch (Exception $e) {
      http_response_code(400);
      echo json_encode(
        array(
          "debug" => "Failed to sign out the user Signout.php",
          "msg" => $e->getMessage(),
          "error_code" => $e->getCode(),
        )
      );
    }
  }
}
