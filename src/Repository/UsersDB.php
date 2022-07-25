<?php

namespace App\Repository;

use App\Core\Db;
use App\Core\Logger;
use Exception;
use PDOException;

class UsersDB extends Db
{
    private const STATUS_REGISTERED = 10;
    private const STATUS_CONFIRMED = 20;
    private const STATUS_SUSPENDED = 30;
    private const STATUS_DELETED = 40;
    private const ROLE_AUTHOR = 10;
    private const ROLE_READER = 20;
    private const ROLE_SITEADMIN = 30;

    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger(__CLASS__);
    }

    public function createUser(array $params)
    {        
        $email = $params['email'];
        $password = $params['pass'];
        $pseudo = $params['pseudo'];
                
        try
        {
            $this->db = Db::getInstance();

            // INSERT INTO table (liste de champs ex: email, Password, Pseudo, Status, Role) VALUES (?, ?, ?, ?, ?, ?)
            $statement = $this->db->prepare('INSERT INTO users (email, password, pseudo, status) 
                                                VALUES (:email, :password, :pseudo, :status)');
            
            $password = password_hash($password, PASSWORD_ARGON2I);
    
            $statement->bindValue(':email', $email);
            $statement->bindValue(':password', $password);
            $statement->bindValue(':pseudo', $pseudo);
            $statement->bindValue(':status', self::STATUS_REGISTERED);
    
            $statement->execute();
            return true; 
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }       
    }

    public function login(array $params)
    {
        $password = $params['pass'];
        $pseudo = $params['pseudo'];

        // $logger = new Logger(UsersDB::class);
        // $logger->console('Je passe là');
        try
        {
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
                if(!password_verify($password, $credentials->password))
                {
                    return null;
                }
                return $credentials;
            }
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return null;
        }
    }

    public function getUser($id)
    {
        $this->db = Db::getInstance();

        $statement = $this->db->prepare('SELECT id, pseudo, email, profile_picture, role FROM users WHERE id = :id');

        $statement->bindValue(':id', $id);

        $statement->execute();

        $result = $statement->fetchObject(static::class);

        if($result)
        {
            return $result;
        }
        return null;
    }

    public function updateUser($id, $email, $pseudo, $picture)
    {
        try 
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('UPDATE users 
                            SET email = :email, pseudo = :pseudo, profile_picture = :pf
                                WHERE id = :id');
            $statement->bindValue(':id', $id);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':pseudo', $pseudo);
            $statement->bindValue(':pf', $picture);
            return $statement->execute();
        }
        catch(PDOException $e) 
        {
            $this->logger->console($e->getMessage());
            return false;
        }
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