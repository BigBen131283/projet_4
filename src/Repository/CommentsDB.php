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

    public function getComments($billetID)
    {
        try
        {   
            $this->db = Db::getInstance();

            $statement = $this->db->prepare('SELECT content, publish_at, users_id, pseudo, c.id, c.report, DATE_FORMAT(publish_at, "%W %d %M, %H:%i") formatted_date FROM comments c, users u 
                                            WHERE billet_id = :billetID AND users_id = u.id AND report >= 30 ORDER BY formatted_date DESC');
            
            $statement->bindValue(':billetID', $billetID);
            $statement->execute();
            $result = $statement->fetchAll();

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

    public function getSignaledComments()
    {
        try
        {
            $this->db = Db::getInstance();

            $statement = $this->db->prepare('SELECT content, c.publish_at, c.users_id, c.id, pseudo, billet_id, title, DATE_FORMAT(c.publish_at, "%W %d %M, %H:%i") formatted_date 
                                             FROM comments c, users u, billets b 
                                             WHERE c.users_id = u.id AND c.billet_id = b.id AND c.report = 20 
                                             ORDER BY c.publish_at DESC;');
                        
            $statement->execute();
            $result = $statement->fetchAll();

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

    public function signalComment($id)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('UPDATE comments SET report = 20 
                                             WHERE id = :id');

            $statement->bindValue(':id', $id);
            return $statement->execute();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }
    }

    public function acceptComment($id)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('UPDATE comments SET report = 40 
                                             WHERE id = :id');

            $statement->bindValue(':id', $id);
            return $statement->execute();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }
    }

    public function rejectComment($id)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('UPDATE comments SET report = 10 
                                             WHERE id = :id');

            $statement->bindValue(':id', $id);
            return $statement->execute();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }
    }
}

?>