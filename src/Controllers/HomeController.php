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
        $this->render('home/index', 'php', 'default', ['loggedUser'=>$user]);
    }
}

?>