<?php

namespace App\Core;

class TokenSelector {

  private $selector;
  private $token;
  // private $hashedtoken; 
  private $expires;
  private $url;
  private const TARGETURL = '/users/registerconfirmed';

  /**
   * TARGETURL est le path dans le routeur pour confirmer l'enregistrement
   */
  public function __construct()
  {
    $host = $_SERVER['SERVER_NAME'];    // To build the URL
    // selector is used to find the user in the resets table
    $this->selector = bin2hex(random_bytes(8));
    // token is used to check the request is safe
    $this->token = random_bytes(32);
    // $this->hashedtoken = password_hash($this->token, PASSWORD_DEFAULT);
    // expires gives a maximum 30 minutes delay for the user to act
    date_default_timezone_set('Europe/Paris');
    $this->expires = date("U") + 1800; 
    $this->url = 'http://'.$host.self::TARGETURL;
    $this->url .= '?selector='.$this->selector.
                    '&token='.bin2hex($this->token);
  }
  // --------------------------------------------------------------------------
  public function getSelector() {
    return $this->selector;
  }
  public function getToken() {
    return $this->token;
  }
  public function getUrl() {
    return $this->url;
  }
  public function getExpires() {
    return $this->expires;
  }
}

?>