<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Main;
use App\Core\Logger;
use App\Models\UsersModel;




class HomeController extends Controller
{
    public function index()
    {
        $user = Main::$main->getUsersModel();
        $logger = new Logger(__CLASS__);
        $logger->console('Call Home Index, for user : '.$user->getPseudo());
        $this->render('home/index', 'php');
    }
}

?>