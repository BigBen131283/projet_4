<?php
namespace App\Repository;

use App\Core\Db;
use App\Core\Logger;
use PDO;
use PDOException;

class AdminDB extends Db
{
    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger(__CLASS__);
    }

    public function siteStatistics()
    {
        $allStatistics = array();

        try
        {
            $this->db = Db::getInstance();
            $statement = $this->db->prepare('SELECT COUNT(*) countAllBillets FROM billets WHERE published = 1');
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            $allStatistics["publishedBillets"] = $result["countAllBillets"];
            
            $statement = $this->db->prepare('SELECT COUNT(*) countAllUsers FROM users WHERE status = 20');
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            $allStatistics["allUsers"] = $result["countAllUsers"];

            $statement = $this->db->prepare('SELECT COUNT(*) countAllComments FROM comments');
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            $allStatistics["allComments"] = $result["countAllComments"];

            $statement = $this->db->prepare('SELECT COUNT(*) countAllModerate FROM comments where report = 20');
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            $allStatistics["allModerate"] = $result["countAllModerate"];

            return (object)$allStatistics; //ça s'appelle un CAST https://stackoverflow.com/questions/1869091/how-to-convert-an-array-to-object-in-php
        }
        catch(PDOException $e)
        {
            $this->logger->console($e->getMessage());
            return null;
        }
    }
}
?>