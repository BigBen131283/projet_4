<?php

namespace App\Repository;

use App\Core\Db;
use App\Core\Logger;
use App\Core\Main;
use App\Controllers\BilletsController;
use PDO;
use PDOException;

class BilletDB extends Db
{
    private const STATUS_PUBLISHED = 1;
    private const STATUS_DEFERRED = 0;
    private $french_months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
    
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
        $chapterpicture = $params['chapter_picture'];
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

            $statement = $this->db->prepare('INSERT INTO billets (title, abstract, chapter, publish_at, users_id, published, chapter_picture) 
                                                VALUES (:title, :abstract, :chapter, :publish_at, :users_id, :published, :chapter_picture)');
            
            $statement->bindValue(':title', $title);
            $statement->bindValue(':abstract', $abstract);
            $statement->bindValue(':chapter', $chapter);
            $statement->bindValue(':publish_at', $publish_at);
            $statement->bindValue(':users_id', $userId);
            $statement->bindValue(':published', $published);
            $statement->bindValue(':chapter_picture', $chapterpicture);

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
        try
        {
            $id = $params['id'];
            $title = $params['title'];
            $abstract = $params['abstract'];
            $chapter = $params['chapter'];
            $chapter_picture = $params['chapter_picture'];
            $usersModel = Main::$main->getUsersModel();
            $userId = $usersModel->getId();
    
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('UPDATE billets 
                                             SET title = :title, abstract = :abstract, chapter = :chapter, publish_at = :publish_at, 
                                                 users_id = :users_id, chapter_picture = :chapter_picture
                                             WHERE id = :id');
            
            $statement->bindValue(':id', $id);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':abstract', $abstract);
            $statement->bindValue(':chapter', $chapter);
            $statement->bindValue(':chapter_picture', $chapter_picture);
            $statement->bindValue(':publish_at', date('Y-m-d H:i:s'));
            $statement->bindValue(':users_id', $userId);
    
            $statement->execute();
            return true;
        }
        catch (PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }
    }

    public function retrieveBillet($id)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('SELECT id, title, abstract, chapter, chapter_picture FROM billets 
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
            $statement = $this->db->prepare('SELECT id, title, abstract, chapter, publish_at, chapter_picture, DATE_FORMAT(publish_at, "%W %d %m, %H:%i") formatted_date FROM billets 
                                                WHERE id = :id');
            $statement->bindValue(':id', $id);

            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_OBJ);

            $elements = explode(" ", $result->formatted_date);
            $i = intval($elements[2]) - 1;
            $elements[2] = $this->french_months[$i];
            $result->formatted_date = implode(" ", $elements);
    
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
            $statement = $this->db->prepare('SELECT id, title, abstract, publish_at, chapter_picture, DATE_FORMAT(publish_at, "%W %d %m, %H:%i") formatted_date 
                                                FROM billets 
                                                WHERE published = 1');

            $statement->execute();
            $result = $this->dateConverter($statement->fetchAll());

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

    /**Use to fix date format behaviour with PDO*/
    public function dateConverter($data)
    {
        foreach($data as $key => $value)
        {
            $elements = explode(" ", $value->formatted_date);
            $i = intval($elements[2]) - 1;
            $elements[2] = $this->french_months[$i];
            $data[$key]->formatted_date = implode(" ", $elements);
        }
        return $data;
    }

    public function adminBillets($assocFlag = false)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('SELECT id, title, publish_at, DATE_FORMAT(publish_at, "%W %d %m, %H:%i") formatted_date 
                                                FROM billets 
                                                ORDER BY publish_at DESC');

            $statement->execute();
            if($assocFlag)
            {
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            }
            else
            {
                $result = $statement->fetchAll();
            }
            if(!empty($result))
            {
                return $this->dateConverter($result);
            }
            return array();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return array();
        }
    }

    public function deleteBillet($id)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('DELETE FROM likes  
                                                WHERE billets_id = :toto');
            $statement->bindValue(':toto', $id);
            $statement->execute();

            $statement = $this->db->prepare('DELETE FROM comments  
                                                WHERE billet_id = :toto');
            $statement->bindValue(':toto', $id);
            $statement->execute();

            $statement = $this->db->prepare('DELETE FROM billets 
                                                WHERE id = :1');

            $statement->bindValue(':1', $id);

            $statement->execute();
            return $statement->rowCount();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return false;
        }
    }

    public function updatePublishedStatus()
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('UPDATE billets SET published = 1 
                WHERE published = 0 AND publish_at <= NOW()');

            $statement->execute();
            return $statement->rowCount();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return 0;
        }
    }

    public function checkHasAnAdvice($userId, $billetId, $flag)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('SELECT billets_id FROM likes 
                                    WHERE users_id = :userId 
                                    AND billets_id = :billetId AND like_it = :like_it');

            $statement->bindValue(':userId', $userId);
            $statement->bindValue(':billetId', $billetId);
            $statement->bindValue(':like_it', $flag);
            $statement->execute();
            return $statement->rowCount();
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return 0;
        }
    }

    public function checkMyAdvice($userId, $billetId)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('SELECT like_it FROM likes 
                                    WHERE users_id = :userId 
                                    AND billets_id = :billetId');

            $statement->bindValue(':userId', $userId);
            $statement->bindValue(':billetId', $billetId);

            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return null;
        }
    }

    public function getCounters($billetId)
    {
        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('SELECT thumbs_up, thumbs_down FROM `billets` WHERE id = :id');

            $statement->bindValue('id', $billetId);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_OBJ);
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return null;
        }
    }
    // Update billet counters.
    // $actionflag = 1, it's a like
    // $actionflag = 0, it's a dislike    
    public function UpdateCounters($billetId, $userid, $actionflag, $actiontype )
    {
        try
        {
            $this->db = Db::getInstance();
            // Check user does not already owns likes or dislikes one
            if($actiontype === BilletsController::ACTION_TYPE_UPDATE) 
            {
                $statement = $this->db->prepare('UPDATE likes SET like_it = :likeflag 
                                                    WHERE billets_id = :billetId 
                                                        AND users_id = :userId');                
                $statement->bindValue(':userId', $userid);
                $statement->bindValue(':billetId', $billetId);
                $statement->bindValue(':likeflag', $actionflag);
                $result =  $statement->execute();                
                if($actionflag === BilletsController::ACTION_LIKE) 
                { 
                    $statement = $this->db->prepare('UPDATE billets 
                                            SET thumbs_up = thumbs_up + 1, 
                                                thumbs_down =  thumbs_down - 1
                                            WHERE id = :id;');
                }
                else 
                {
                    $statement = $this->db->prepare('UPDATE billets 
                                            SET thumbs_up = thumbs_up - 1, 
                                                thumbs_down =  thumbs_down + 1
                                            WHERE id = :id;');
                }
                $statement->bindValue(':id', $billetId);
                return $statement->execute();            
            }
            if($actiontype === BilletsController::ACTION_TYPE_INSERT) 
            {
                $statement = $this->db->prepare('INSERT INTO likes (billets_id, users_id, like_it) 
                                                    VALUES (:billetId, :userId, :likeit)');                
                $statement->bindValue(':userId', $userid);
                $statement->bindValue(':billetId', $billetId);
                $statement->bindValue(':likeit', $actionflag);
                $result =  $statement->execute();                
                if($actionflag === BilletsController::ACTION_LIKE) 
                { 
                    $statement = $this->db->prepare('UPDATE billets 
                                            SET thumbs_up = thumbs_up + 1
                                            WHERE id = :id;');
                }
                else 
                {
                    $statement = $this->db->prepare('UPDATE billets 
                                            SET thumbs_down =  thumbs_down + 1
                                            WHERE id = :id;');
                }
                $statement->bindValue(':id', $billetId);
                return $statement->execute();            
            }
            if($actiontype === BilletsController::ACTION_TYPE_DELETE) 
            {
                $statement = $this->db->prepare('DELETE FROM likes WHERE billets_id = :billetId 
                                                                    AND users_id = :userId');                     
                $statement->bindValue(':userId', $userid);
                $statement->bindValue(':billetId', $billetId);
                $result =  $statement->execute();                
                if($actionflag === BilletsController::ACTION_LIKE) 
                { 
                    $statement = $this->db->prepare('UPDATE billets 
                                            SET thumbs_up = thumbs_up - 1
                                            WHERE id = :id;');
                }
                else 
                {
                    $statement = $this->db->prepare('UPDATE billets 
                                            SET thumbs_down =  thumbs_down - 1
                                            WHERE id = :id;');
                }
                $statement->bindValue(':id', $billetId);
                return $statement->execute();
            }
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return 0;
        }
    }
}

?>