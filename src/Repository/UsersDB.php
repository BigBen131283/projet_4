<?php

namespace App\Repository;

use App\Core\Db;
use App\Controllers\Users;
use App\Validator\UsersValidator;

class UsersDB extends Db
{
    public function createUser(array $params, Users $usersController)
    {        
        $email = $params['email'];
        $password = $params['pass'];
        $pseudo = $params['pseudo'];
        
        $validator = new UsersValidator;

        $errors = $validator->checkUserEntries($params);
        
        if(empty($errors))
        {    
            $this->db = Db::getInstance();

            // INSERT INTO table (liste de champs ex: email, Password, Pseudo, Status, Role) VALUES (?, ?, ?, ?, ?, ?)
            $statement = $this->db->prepare('INSERT INTO users (email, password, pseudo) VALUES (:email, :password, :pseudo)');

            $statement->bindValue(':email', $email);
            $statement->bindValue(':password', $password);
            $statement->bindValue(':pseudo', $pseudo);

            $statement->execute();
            return true;        
        }
        else
        {
            $usersController->render('users/register', $errors, "html");
        }
    
    }
}

?>