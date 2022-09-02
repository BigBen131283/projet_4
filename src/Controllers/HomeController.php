<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Main;
use App\Core\Logger;
use App\Models\UsersModel;
use App\Repository\BilletDB;

class HomeController extends Controller
{
    public function index()
    {
        $user = Main::$main->getUsersModel();
        $logger = new Logger(__CLASS__);
        $billetDB = new BilletDB();
        $billet = $billetDB->getLastAbstract();
        // var_dump($abstract); die;

        $this->render('home/index', 'php', 'default', ['loggedUser'=>$user, 'billet'=>$billet, 'specialFX'=>$billet->abstract]);
    }
}

?>