<?php

namespace app\core;

class Request {

    //-----------------------------------------------------------------------------
    /**
     * Analyse la requête qui arrive
     *
     * @return string
     */
    public function getPath() {
      $path = $_SERVER['REQUEST_URI'] ?? '/';
      $position = strpos($path, '?');
      if ($position === false) {
        return $path;             // No parameter on request
      }
      return substr($path, 0, $position);
    }
    //-----------------------------------------------------------------------------
    /**
     * Analyse la méthode utilisée, retourne GET ou POST
     * Sert à la fonction getBody()
     *
     * @return string
     */
    public function method() {
      return strtolower($_SERVER['REQUEST_METHOD']);
    }
    //-----------------------------------------------------------------------------
    public function isGet() { return $this->method() === 'get'; }
    //-----------------------------------------------------------------------------
    public function isPost() { return $this->method() === 'post'; }
    //-----------------------------------------------------------------------------
    /**
     * Retourne les paramètres
     *
     * @return array
     */
    public function getBody() {
      $body = [];
      if($this->method() === 'get') {
        foreach( $_GET as $key => $value) {
          $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
      }
      if($this->method() === 'post') {
        foreach( $_POST as $key => $value) {
          $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
      }
      return $body;
    }
}