<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Logger;
use App\Core\Main;
use Exception;
use PDOException;

class RedirectController extends Controller
{
    public function error()
    {
        $logger = new Logger(Users::class);
        $user = Main::$main->getUsersModel();

        $this->render('redirect/error', 'php', 'defaulterror', ['loggedUser'=>$user]);
    }
}
?>