<?php

namespace App\Repository;

use App\Core\Db;
use App\Controllers\UsersController;
use App\Validator\UsersValidator;

class UsersDB extends Db
{
    public function createUser(array $params, UsersController $usersController)
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
}

?>