<?php

namespace App\Repository;

use App\Core\Db;
use App\Core\Logger;
use App\Core\Main;
use Exception;
use PDOException;

class BilletDB extends Db
{
    private const STATUS_PUBLISHED = 1;
    private const STATUS_DEFERRED = 0;
    
    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger(__CLASS__);
    }

    public function createBillet(array $params)
    {        
        $title = $params['title'];
        $abstract = $params['abstract'];
        $chapter = $params['chapter'];
        $usersModel = Main::$main->getUsersModel();
        $userId = $usersModel->getId();
        
        try
        {
            $this->db = Db::getInstance();

            $statement = $this->db->prepare('INSERT INTO billets (title, abstract, chapter, publish_at, users_id) 
                                                VALUES (:title, :abstract, :chapter, :publish_at, :users_id)');
            
            $statement->bindValue(':title', $title);
            $statement->bindValue(':abstract', $abstract);
            $statement->bindValue(':chapter', $chapter);
            $statement->bindValue(':publish_at', date('Y-m-d H:i:s'));
            $statement->bindValue(':users_id', $userId);

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