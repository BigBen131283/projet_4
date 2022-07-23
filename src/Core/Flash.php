<?php

namespace App\Core;

use App\Core\Logger;

class Flash {

  private Logger $logger;

  // -------------------------------------------------------------------------------------
  public function __construct(string $name = 'none') {
    $this->logger = new Logger(__CLASS__);
    if(!isset($_SESSION)) {
      session_start();
    }
    else {
      $this->logger->console("Session exists");
      if(isset($_SESSION[$name])) {// Pending flash messages ?
        $this->logger->console("Session data for [".$name."]".' '.$_SESSION[$name]);
      } 
    }
  }
  // -------------------------------------------------------------------------------------
  public function addFlash($name = '', $message = '') {
    if(!empty($name)) {
      $this->logger->console("Save a [".$name."] flash for message: ".$message);
      $_SESSION[$name] = $message;
    }
  }
  // -------------------------------------------------------------------------------------
  public function getFlash($name = '') {
    if(empty($message) && !empty($_SESSION[$name])) {
      $this->logger->console("Render a flash message for ".$name);
      echo '<p>'.$_SESSION[$name].'</p>';
      unset($_SESSION[$name]);
    }
  }
}


?>