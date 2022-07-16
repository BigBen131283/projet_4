<?php

namespace App\Repository;

use App\Core\Db;
use App\Core\Logger;
use Exception;

class UsersDB extends Db
{
    public function createUser(array $params)
    {        
        $email = $params['email'];
        $password = $params['pass'];
        $pseudo = $params['pseudo'];
                
        $this->db = Db::getInstance();

        // INSERT INTO table (liste de champs ex: email, Password, Pseudo, Status, Role) VALUES (?, ?, ?, ?, ?, ?)
        $statement = $this->db->prepare('INSERT INTO users (email, password, pseudo) VALUES (:email, :password, :pseudo)');

        $statement->bindValue(':email', $email);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':pseudo', $pseudo);

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

        $statement = $this->db->prepare('SELECT id, password FROM users WHERE pseudo = :pseudo');
        $statement->bindValue(':pseudo', $pseudo);

        $statement->execute();
        $credentials = $statement->fetchObject(static::class);

        if(empty($credentials))
        {
            return false;
        }
        else
        {
            if($credentials->password !== $password)
            {
                return false;
            }
            return true;
        }
        

    }
}

?>