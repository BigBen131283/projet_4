<?php

namespace App\Repository;

use App\Core\Db;
use App\Core\Logger;
use App\Core\Main;
use PDO;
use PDOException;

class CommentsDB extends Db
{
    
    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger(__CLASS__);
    }

    public function createComment(array $params)
    {        
        $billetID = $params['billetID'];
        $content = $params['content'];
        $usersModel = Main::$main->getUsersModel();
        $userId = $usersModel->getId();
        date_default_timezone_set('Europe/Brussels');
        
        try
        {
            $this->db = Db::getInstance();

            $statement = $this->db->prepare('INSERT INTO comments (content, users_id, billet_id) 
                                                VALUES (:content, :users_id, :billet_id)');
            
            $statement->bindValue(':content', $content);
            $statement->bindValue(':users_id', $userId);
            $statement->bindValue(':billet_id', $billetID);

            $statement->execute();
            return true; 
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }       
    }

}

?>