<?php

require_once dirname(__FILE__) . "/../../../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JwtHandler
{
  private $secretKey = "dksf8asdfli38223u893h2r8139e2r";
  private $identifier;
  private $email;
  private $jwt;
  private $userId;
  private $conn;                                 // Retrieved from filtered POST data


  public function __construct($db, $email, $userId)
  {
    $this->issuedAt = new DateTimeImmutable();
    $this->identifier = "anthony.fernandez.app";
    $this->conn = $db;

    $this->email = $email;
    $this->userId = $userId;
  }

  public function generateToken()
  {
    $data = [
      'iat' => $this->issuedAt->getTimestamp(),         // Issued at: time when the token was generated
      'iss' => $this->identifier,                       // Issuer
      'nbf' => $this->issuedAt->getTimestamp(),         // Not before
      'userName' => $this->email,                     // User name
    ];


    $createdJwt =  JWT::encode(
      $data,
      $this->secretKey,
      'HS512'
    );

    $this->jwt = $createdJwt;

    $savingTokenToDbResult = $this->saveTokenToDb($createdJwt, $this->email, $this->userId);

    if (!$savingTokenToDbResult) {
      http_response_code(400);
      echo json_encode(array("msg" => "Could not save token to db"));
    }

    return $createdJwt;
  }


  private function saveTokenToDb($jwt, $email, $id)
  {
    try {
      $query = "INSERT INTO `authTokens` (`email`, `token`, `userId`) VALUES (\"$email\", \"$jwt\", $id)";

      $statement = $this->conn->prepare($query);
      $statement->execute();

      return $statement;
    } catch (Exception $e) {
      echo json_encode(
        array(
          "debug" => "Error saving token to db in JwtHandler",
          "msg" => $e->getMessage(),
          "error_code" => $e->getCode(),
        )
      );
      die;
    }
  }
}
