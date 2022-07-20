<?php

namespace App\Repository;

use App\Core\Db;
use App\Core\Logger;
use Exception;

class UsersDB extends Db
{
    private const STATUS_REGISTERED = 10;
    private const STATUS_CONFIRMED = 20;
    private const STATUS_SUSPENDED = 30;
    private const STATUS_DELETED = 40;
    private const ROLE_AUTHOR = 10;
    private const ROLE_READER = 20;
    private const ROLE_SITEADMIN = 30;

    public function createUser(array $params)
    {        
        $email = $params['email'];
        $password = $params['pass'];
        $pseudo = $params['pseudo'];
                
        $this->db = Db::getInstance();

        // INSERT INTO table (liste de champs ex: email, Password, Pseudo, Status, Role) VALUES (?, ?, ?, ?, ?, ?)
        $statement = $this->db->prepare('INSERT INTO users (email, password, pseudo, status) 
                                            VALUES (:email, :password, :pseudo, :status)');

        $statement->bindValue(':email', $email);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':pseudo', $pseudo);
        $statement->bindValue(':status', self::STATUS_REGISTERED);

        $statement->execute();
        return true;        
    }

    public function login(array $params)
    {
        $password = $params['pass'];
        $pseudo = $params['pseudo'];

        // $logger = new Logger(UsersDB::class);
        // $logger->console('Je passe là');

        $this->db = Db::getInstance();

        $statement = $this->db->prepare('SELECT id, password FROM users WHERE pseudo = :pseudo AND status='.self::STATUS_CONFIRMED);
        $statement->bindValue(':pseudo', $pseudo);

        $statement->execute();
        $credentials = $statement->fetchObject(static::class);

        if(empty($credentials))
        {
            return null;
        }
        else
        {
            if($credentials->password !== $password)
            {
                return null;
            }
            return $credentials;
        }
    }

    public function getUser($id)
    {
        $this->db = Db::getInstance();

        $statement = $this->db->prepare('SELECT id, pseudo, email, role FROM users WHERE id = :id');

        $statement->bindValue(':id', $id);

        $statement->execute();

        $result = $statement->fetchObject(static::class);

        if($result)
        {
            return $result;
        }
        return null;
    }

    public function confirmRegistration($selector, $token) 
    {
        $this->db = Db::getInstance();
        $resetdb = new ResetDB();
        // 1st, check the resets table to validate the register confirmation request
        if($resetdb->verify($selector, $token)) 
        {
            $userpseudo = $resetdb->getUserPseudo();
            $statement = $this->db->prepare('UPDATE users SET status = :statusvalue WHERE pseudo = :pseudo');
            $statement->bindValue(':statusvalue', self::STATUS_CONFIRMED);
            $statement->bindValue(':pseudo', $userpseudo);
            $statement->execute();      // Should normally not send an error ;-)
            return true;
        }   
        else 
        {
            return false;
        }
    }
}

?>