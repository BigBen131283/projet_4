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
        $abstract = html_entity_decode(stripslashes($billet->abstract));
        // var_dump($abstract); die;

        // $splitAbstract = "";
        // if($billet)
        // {
        //     $letters = str_split($billet);
        //     foreach($letters as $letter)
        //     {
        //         $splitAbstract .= "<span>$letter</span>";
        //     }
        // }
        $this->render('home/index', 'php', 'default', ['loggedUser'=>$user, 'billet'=>$billet, 'specialFX'=>$billet->abstract]);
    }
}

?>