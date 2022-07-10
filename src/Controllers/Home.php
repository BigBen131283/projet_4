<?php

namespace App\Controllers;

use App\Core\Controller;



class Home extends Controller
{
    public function index()
    {
        echo "Ceci est la page d'accueil";
        include ROOT.'./src/View/Home/index.php';
    }
}

?>