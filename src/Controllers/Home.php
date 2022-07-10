<?php

namespace App\Controllers;

use App\Core\Controller;

include ROOT.'./src/View/Home/index.php';

class Home extends Controller
{
    public function index()
    {
        echo "Ceci est la page d'accueil";
    }
}

?>