<?php

require_once dirname(__FILE__) . "/../../../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JwtHandler
{
  private $secretKey = "dksf8asdfli38223u893h2r8139e2r";
  private $identifier;
  private $email;
  private $jwt;                                       // Retrieved from filtered POST data


  public function __construct()
  {
    $this->issuedAt = new DateTimeImmutable();
    $this->identifier = "anthony.fernandez.app";
  }

  public function generateToken($email)
  {
    $this->email = $email;

    $data = [
      'iat' => $this->issuedAt->getTimestamp(),         // Issued at: time when the token was generated
      'iss' => $this->identifier,                       // Issuer
      'nbf' => $this->issuedAt->getTimestamp(),         // Not before
      'userName' => $this->email,                     // User name
    ];


    $this->jwt = JWT::encode(
      $data,
      $this->secretKey,
      'HS512'
    );


    return $this->jwt;
  }

  public function validateToken($jwt)
  {
    $token = JWT::decode($jwt, new Key($this->secretKey, 'HS512'));

    if (!$token || $token->iss !== $this->identifier) {
      return false;
    }

    return true;
  }
}
