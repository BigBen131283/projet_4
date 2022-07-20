<?php

namespace App\Repository;

use App\Core\Db;
use App\Core\Logger;

use PDOException;

/**
 * Insère des messages de tracking pour les demandes d'enregistrement
 */
class ResetDB extends Db 
{

  private  const STATUS_REQUESTED = 0;  // The mail confirmation request is pending
  private  const STATUS_PROCESSED = 1;  // The mail confirmation has been validated
  private  const STATUS_REJECTED = 2;   // The mail confirmation has been rejected
  private  const STATUS_EXPIRED = 3;    // The mail confirmation has expired before user answered the email

  private string $userpseudo;
  public $resetid;

  // -----------------------------------------------------------------------------------
  public function request($actiontype = 'Generic', $pseudo, $selector, $token, $expires)
    {    
        $logger = new Logger(__CLASS__);
        $this->db = Db::getInstance();
        $statement = $this->db->prepare('INSERT INTO resets (resetactiontype, pseudo, selector, token, expires, resetstatus) 
                                      VALUES (:resetactiontype, :pseudo, :selector, :token, :expires, :resetstatus)');
        $statement->bindValue(':resetactiontype', '['.__CLASS__.']'.$actiontype);
        $statement->bindValue(':pseudo', $pseudo);
        $statement->bindValue(':selector', $selector);
        $statement->bindValue(':token', $token);
        $statement->bindValue(':expires', $expires);
        $statement->bindValue(':resetstatus', self::STATUS_REQUESTED);
        try 
        {
          $statement->execute();
        }
        catch(PDOException $e) {
          // Do nothing, hide the exception ;-)
          // This is a really really really bad practice !!!!
          $logger->db($e->getMessage());
        }
        //Même en cas d'erreur on passe dans le finally
        finally 
        {
          return true;        
        }
    }
  // -----------------------------------------------------------------------------------
  public function update($resetstatus) 
  {
    $logger = new Logger(__CLASS__);
    $logger->db("resetstatus : ".$resetstatus);
    $this->db = Db::getInstance();
    $statement = $this->db->prepare('UPDATE resets SET resetstatus = :resetstatus, processedtime = CURRENT_TIMESTAMP
                            WHERE resetid = :resetid ');
    $statement->bindValue(':resetid', $this->resetid);
    $statement->bindValue(':resetstatus', $resetstatus);
    $statement->execute();    
  }
  // -----------------------------------------------------------------------------------
  public function verify($selector, $token) 
  {
    $logger = new Logger(__CLASS__);
    $this->db = Db::getInstance();
    // 1st, check the resets table to validate the register confirmation request
    $statement = $this->db->prepare('SELECT resetid, pseudo, token, expires FROM resets 
                            WHERE selector = :selector AND resetstatus = '.self::STATUS_REQUESTED);
    $statement->bindValue(':selector', $selector);
    $statement->execute();
    
    if($statement) 
    {
      $record = $statement->fetchObject(static::class);
      // Ok found a request record with this selector
      $requesttoken = hex2bin($token);  // Convert request token back to DB format
      // Is the validation token correct ?
      if($requesttoken === $record->token) 
      {  
        $this->resetid = $record->resetid;
        $this->userpseudo = $record->pseudo;
        // Expired request ?? 
        $currentdate = date("U");
        $logger->db($currentdate);
        $logger->db($record->expires);
        if($currentdate <= $record->expires) 
        { 
          $this->update(self::STATUS_PROCESSED);
          return true;
        }
        $this->update(self::STATUS_EXPIRED);
        return false;  
      }
    }
    // Either record not found or invalid token
    $this->update(self::STATUS_REJECTED);
    return false;
  }
  // -----------------------------------------------------------------------------------
  public function getUserPseudo() 
  {
    return $this->userpseudo;
  }
}
?>