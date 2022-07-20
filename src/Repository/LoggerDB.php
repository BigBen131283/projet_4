<?php

namespace App\Repository;

use App\Core\Db;
use PDOException;


/**
 * Au lieu de mettre un message dans la console ou sur l'écran
 * On met le message dans une base
 * Ici on se connecte à la base et on insère les messages
 */
class LoggerDB extends Db {
  public function log(string $message)
    {        
        $this->db = Db::getInstance();
        $statement = $this->db->prepare('INSERT INTO logs (logmessage) VALUES (:message)');
        $statement->bindValue(':message', $message);
        try {
          $statement->execute();
        }
        catch(PDOException $e) {
          // Do nothing, hide the exception ;-)
          // This is a really really really bad practice !!!!
          $x = 0;
        }
        finally {
          return true;        
        }
    }
}

?>