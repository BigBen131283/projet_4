<?php

namespace App\Core;

use App\Repository\LoggerDB;

class Logger
{

  private $module = 'Anonymous';

  //-----------------------------------------------------------------------------
  public function __construct($module = '') {
    if ($module) { $this->module = $module; }
  }
  //-----------------------------------------------------------------------------
  public function log($data) {
    echo "<br>";
    if($this->module) {
      echo "<br>".$this->module."</br>";
    }
    var_dump($data);
    echo "</br>";
  }
  //-----------------------------------------------------------------------------
  public function console($data) {
    $header = '';
    if($this->module) {
        $header = "{ Module: $this->module }";
    }
    if(is_array($data)) {
      foreach($data as $field => $value) {
        if(is_array($value)) {    // If array only display 1st element
          echo "<script>console.log(\"$header $field = $value[0]\");</script>";
        }
        else {
          echo "<script>console.log(\"$header$field = $value\");</script>";
        }
      }
    }
    else {
      echo "<script>console.log(\"$header $data\");</script>";
    }
  }
  //-----------------------------------------------------------------------------
  public function db($data) {
    $loggerdb = new LoggerDB();
    $loggerdb->log("{ Module: $this->module } ".$data);
  }

}

?>