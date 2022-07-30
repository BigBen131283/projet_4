<?php

namespace App\Repository;

use App\Core\Db;
use App\Core\Logger;
use App\Core\Main;
use PDO;
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
        $publish_at = $params['publish_at'];
        $published = 1;
        $usersModel = Main::$main->getUsersModel();
        $userId = $usersModel->getId();
        date_default_timezone_set('Europe/Brussels');
        
        try
        {
            $this->db = Db::getInstance();

            $wishPublish_at = strtotime($publish_at);
            $current = strtotime(date('Y-m-d H:i:s'));
            if($wishPublish_at > $current)
            {
                $published = 0;
            }

            $statement = $this->db->prepare('INSERT INTO billets (title, abstract, chapter, publish_at, users_id, published) 
                                                VALUES (:title, :abstract, :chapter, :publish_at, :users_id, :published)');
            
            $statement->bindValue(':title', $title);
            $statement->bindValue(':abstract', $abstract);
            $statement->bindValue(':chapter', $chapter);
            $statement->bindValue(':publish_at', $publish_at);
            $statement->bindValue(':users_id', $userId);
            $statement->bindValue(':published', $published);

            $statement->execute();
            return true; 
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }       
    }

    public function updateBillet($params)
    {
        $title = $params['title'];
        $abstract = $params['abstract'];
        $chapter = $params['chapter'];
        $usersModel = Main::$main->getUsersModel();
        $userId = $usersModel->getId();

        $this->db = Db::getInstance();
        $statement = $this->db->prepare('UPDATE billets SET abstract = :abstract, chapter = :chapter, publish_at = :publish_at, users_id = :users_id 
                                            WHERE title = :title');
        
        $statement->bindValue(':title', $title);
        $statement->bindValue(':abstract', $abstract);
        $statement->bindValue(':chapter', $chapter);
        $statement->bindValue(':publish_at', date('Y-m-d H:i:s'));
        $statement->bindValue(':users_id', $userId);

        $statement->execute();
        return true;
    }

    public function retrieveBillet($id)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('SELECT id, title, abstract, chapter FROM billets 
                                                WHERE id = :id');
            $statement->bindValue(':id', $id);

            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
    
            if(!empty($result))
            {
                return $result;
            }
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }
    }

    public function readBillet($id)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('SELECT id, title, chapter, publish_at FROM billets 
                                                WHERE id = :id');
            $statement->bindValue(':id', $id);

            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_OBJ);
    
            if(!empty($result))
            {
                return $result;
            }
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }
    }

    public function publishedBillets()
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('SELECT id, title, abstract, publish_at FROM billets 
                                                WHERE published = 1');

            $statement->execute();
            $result = $statement->fetchAll();
            // var_dump($result);die;

            if(!empty($result))
            {
                return $result;
            }
            return array();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }
    }

    public function deleteBillet($id)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('DELETE FROM billets 
                                                WHERE id = :1');

            $statement->bindValue(':1', $id);

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